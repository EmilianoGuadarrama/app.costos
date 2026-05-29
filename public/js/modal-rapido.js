/**
 * Sistema de Modal de Registro Rápido para el formulario de Presupuesto Unificado
 * Se inicializa cuando el DOM está listo — las variables globales (csrfToken, catUnidades, etc.)
 * deben estar definidas antes de cargar este archivo.
 */

// ── Estado del modal ─────────────────────────────────────────────────────────
let modalState = { tipo: null, callback: null, valorInicial: '' };

// ── Configuración por tipo de entidad ────────────────────────────────────────
function getModalConfig() {
    return {
        unidad: {
            titulo: 'Nueva Unidad de Medida',
            icono: 'bi-rulers',
            sub: 'Registra la unidad de medida que necesitas usar.',
            url: window._apiUrls.unidad,
            campos: () => `
                <div class="m-grid-2">
                    <div class="m-field">
                        <label class="m-label">Abreviatura <span>*</span></label>
                        <input class="m-ctrl" id="mf_abreviatura" placeholder="Ej. m², pza, kg" maxlength="50" required>
                        <p class="m-hint">Símbolo corto que aparecerá en los campos.</p>
                    </div>
                    <div class="m-field">
                        <label class="m-label">Nombre completo <span>*</span></label>
                        <input class="m-ctrl" id="mf_nombre" placeholder="Ej. Metro cuadrado" maxlength="255" required>
                    </div>
                </div>`,
            payload: () => ({
                abreviatura: document.getElementById('mf_abreviatura').value.trim(),
                nombre:      document.getElementById('mf_nombre').value.trim()
            }),
            rellenar: (d) => ({ id: d.id, texto: d.abreviatura, nombre: d.texto || d.abreviatura })
        },

        area: {
            titulo: 'Nueva Área',
            icono: 'bi-grid-3x3-gap',
            sub: 'Define un área o partida para clasificar los conceptos.',
            url: window._apiUrls.area,
            campos: () => `
                <div class="m-grid-2">
                    <div class="m-field">
                        <label class="m-label">Abreviatura <span>*</span></label>
                        <input class="m-ctrl" id="mf_abreviatura" placeholder="Ej. INST, EST, ARQ" maxlength="50" required>
                    </div>
                    <div class="m-field">
                        <label class="m-label">Descripción <span>*</span></label>
                        <input class="m-ctrl" id="mf_descripcion" placeholder="Ej. Instalaciones eléctricas" maxlength="255" required>
                    </div>
                </div>`,
            payload: () => ({
                abreviatura: document.getElementById('mf_abreviatura').value.trim(),
                descripcion: document.getElementById('mf_descripcion').value.trim()
            }),
            rellenar: (d) => ({ id: d.id, texto: d.abreviatura + ' — ' + d.descripcion })
        },

        bloque: {
            titulo: 'Nuevo Bloque',
            icono: 'bi-columns-gap',
            sub: 'Los bloques agrupan los conceptos dentro del presupuesto.',
            url: window._apiUrls.bloque,
            campos: () => `
                <div class="m-field">
                    <label class="m-label">Descripción del Bloque <span>*</span></label>
                    <input class="m-ctrl" id="mf_descripcion" placeholder="Ej. Preliminares, Estructura, Acabados" maxlength="255" required>
                    <p class="m-hint">Escribe el nombre del bloque tal como aparecerá en el presupuesto.</p>
                </div>`,
            payload: () => ({ descripcion: document.getElementById('mf_descripcion').value.trim() }),
            rellenar: (d) => ({ id: d.id, texto: d.descripcion })
        },

        material: {
            titulo: 'Nuevo Material',
            icono: 'bi-box-seam',
            sub: 'Registra el material con su precio unitario.',
            url: window._apiUrls.material,
            campos: () => `
                <div class="m-field">
                    <label class="m-label">Nombre del material <span>*</span></label>
                    <input class="m-ctrl" id="mf_nombre" placeholder="Ej. Varilla de acero 3/8" maxlength="255" required>
                </div>
                <div class="m-grid-2">
                    <div class="m-field">
                        <label class="m-label">Descripción</label>
                        <input class="m-ctrl" id="mf_descripcion" placeholder="Detalles adicionales">
                    </div>
                    <div class="m-field">
                        <label class="m-label">Marca</label>
                        <input class="m-ctrl" id="mf_marca" placeholder="Ej. AHMSA, Cemex">
                    </div>
                </div>
                <div class="m-grid-2">
                    <div class="m-field">
                        <label class="m-label">Unidad de medida</label>
                        <div style="position:relative;">
                            <input class="m-ctrl" id="mf_uni_txt" placeholder="Buscar unidad..." autocomplete="off">
                            <input type="hidden" id="mf_uni_id">
                            <div class="ac-list" id="mf_uni_list" style="z-index:10001;"></div>
                        </div>
                    </div>
                    <div class="m-field">
                        <label class="m-label">Precio por unidad ($) <span>*</span></label>
                        <input class="m-ctrl" id="mf_precio" type="number" min="0" step="0.01" placeholder="0.00" required>
                    </div>
                </div>`,
            payload: () => ({
                nombre:           document.getElementById('mf_nombre').value.trim(),
                descripcion:      document.getElementById('mf_descripcion')?.value.trim() || '',
                marca:            document.getElementById('mf_marca')?.value.trim() || '',
                id_unidad_medida: document.getElementById('mf_uni_id')?.value || null,
                precio_x_unidad:  document.getElementById('mf_precio').value
            }),
            rellenar: (d) => ({ id: d.id, texto: d.texto, pu: d.pu, uni: d.uni, uniTxt: d.uniTxt })
        },

        mano_obra: {
            titulo: 'Nueva Mano de Obra',
            icono: 'bi-person-lines-fill',
            sub: 'Registra la categoría de mano de obra con su precio por unidad.',
            url: window._apiUrls.mano_obra,
            campos: () => `
                <div class="m-field">
                    <label class="m-label">Nombre / Categoria <span>*</span></label>
                    <input class="m-ctrl" id="mf_nombre" placeholder="Ej. Albanil, Peon, Oficial" maxlength="255" required>
                </div>
                <div class="m-grid-2">
                    <div class="m-field">
                        <label class="m-label">Unidad de medida</label>
                        <div style="position:relative;">
                            <input class="m-ctrl" id="mf_uni_txt" placeholder="Buscar unidad..." autocomplete="off">
                            <input type="hidden" id="mf_uni_id">
                            <div class="ac-list" id="mf_uni_list" style="z-index:10001;"></div>
                        </div>
                    </div>
                    <div class="m-field">
                        <label class="m-label">Precio por unidad ($) <span>*</span></label>
                        <input class="m-ctrl" id="mf_precio" type="number" min="0" step="0.01" placeholder="0.00" required>
                    </div>
                </div>`,
            payload: () => ({
                nombre:           document.getElementById('mf_nombre').value.trim(),
                id_unidad_medida: document.getElementById('mf_uni_id')?.value || null,
                precio_x_unidad:  document.getElementById('mf_precio').value
            }),
            rellenar: (d) => ({ id: d.id, texto: d.texto, pu: d.pu, uni: d.uni, uniTxt: d.uniTxt })
        },

        maquinaria: {
            titulo: 'Nueva Maquinaria / Equipo',
            icono: 'bi-truck',
            sub: 'Registra la maquinaria o equipo con su costo por unidad.',
            url: window._apiUrls.maquinaria,
            campos: () => `
                <div class="m-field">
                    <label class="m-label">Nombre del equipo <span>*</span></label>
                    <input class="m-ctrl" id="mf_nombre" placeholder="Ej. Retroexcavadora, Vibrador de concreto" maxlength="255" required>
                </div>
                <div class="m-field">
                    <label class="m-label">Descripcion</label>
                    <input class="m-ctrl" id="mf_descripcion" placeholder="Modelo, capacidad u otros datos">
                </div>
                <div class="m-grid-2">
                    <div class="m-field">
                        <label class="m-label">Unidad de medida</label>
                        <div style="position:relative;">
                            <input class="m-ctrl" id="mf_uni_txt" placeholder="Buscar unidad..." autocomplete="off">
                            <input type="hidden" id="mf_uni_id">
                            <div class="ac-list" id="mf_uni_list" style="z-index:10001;"></div>
                        </div>
                    </div>
                    <div class="m-field">
                        <label class="m-label">Precio por unidad ($) <span>*</span></label>
                        <input class="m-ctrl" id="mf_precio" type="number" min="0" step="0.01" placeholder="0.00" required>
                    </div>
                </div>`,
            payload: () => ({
                nombre:           document.getElementById('mf_nombre').value.trim(),
                descripcion:      document.getElementById('mf_descripcion')?.value.trim() || '',
                id_unidad_medida: document.getElementById('mf_uni_id')?.value || null,
                precio_x_unidad:  document.getElementById('mf_precio').value
            }),
            rellenar: (d) => ({ id: d.id, texto: d.texto, pu: d.pu, uni: d.uni, uniTxt: d.uniTxt })
        }
    };
}

window.abrirModal = function(tipo, valorInicial, callback) {
    const modalConfig = getModalConfig();
    const cfg = modalConfig[tipo];
    if (!cfg) return;

    modalState = { tipo, callback, valorInicial };

    document.getElementById('modalIcon').className     = 'bi ' + cfg.icono;
    document.getElementById('modalTitulo').textContent = cfg.titulo;
    document.getElementById('modalSub').textContent    = cfg.sub;
    document.getElementById('modalBody').innerHTML     = cfg.campos();
    document.getElementById('btnModalGuardar').disabled = false;
    document.getElementById('btnModalGuardar').innerHTML = '<i class="bi bi-check-lg me-1"></i>Guardar y usar';

    // Pre-rellenar primer campo con lo que ya escribio el usuario
    const primerCampo = document.getElementById('mf_nombre') ||
                        document.getElementById('mf_abreviatura') ||
                        document.getElementById('mf_descripcion');
    if (primerCampo && valorInicial) primerCampo.value = valorInicial;

    // Activar autocomplete de unidad dentro del modal si aplica
    const mfUniTxt = document.getElementById('mf_uni_txt');
    const mfUniId  = document.getElementById('mf_uni_id');
    if (mfUniTxt && mfUniId && typeof setupUniAC === 'function') {
        setupUniAC(mfUniTxt, mfUniId);
    }

    // Enter para guardar, Escape para cerrar
    document.getElementById('modalBox').onkeydown = function(e) {
        if (e.key === 'Enter' && e.target.tagName !== 'TEXTAREA') {
            e.preventDefault();
            guardarModal();
        }
        if (e.key === 'Escape') cerrarModal();
    };

    document.getElementById('modalOverlay').classList.add('active');
    setTimeout(function() { if (primerCampo) primerCampo.focus(); }, 150);
};

window.cerrarModal = function() {
    document.getElementById('modalOverlay').classList.remove('active');
    modalState = { tipo: null, callback: null, valorInicial: '' };
};

window.guardarModal = async function() {
    const modalConfig = getModalConfig();
    const cfg = modalConfig[modalState.tipo];
    if (!cfg) return;

    const btn = document.getElementById('btnModalGuardar');
    btn.disabled = true;
    btn.innerHTML = '<i class="bi bi-arrow-repeat" style="animation:spin 1s linear infinite;"></i> Guardando...';

    try {
        const payload = cfg.payload();

        // Validacion basica
        const required = document.querySelectorAll('#modalBody .m-ctrl[required]');
        let valid = true;
        required.forEach(function(el) {
            if (!el.value.trim()) {
                el.style.borderColor = '#dc2626';
                if (valid) el.focus();
                valid = false;
            } else {
                el.style.borderColor = '';
            }
        });
        if (!valid) {
            btn.disabled = false;
            btn.innerHTML = '<i class="bi bi-check-lg me-1"></i>Guardar y usar';
            return;
        }

        const resp = await fetch(cfg.url, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': window._csrfToken || document.querySelector('meta[name="csrf-token"]')?.content,
                'Accept': 'application/json'
            },
            body: JSON.stringify(payload)
        });

        const data = await resp.json();

        if (!resp.ok) {
            const msg = data.errors ? Object.values(data.errors).flat().join('\n') : (data.message || 'Error al guardar');
            throw new Error(msg);
        }

        const resultado = cfg.rellenar(data);

        // Agregar al catalogo local para futuros autocompletes
        const tipo = modalState.tipo;
        if (tipo === 'unidad' && typeof catUnidades !== 'undefined') {
            if (!catUnidades.find(function(u) { return u.id == resultado.id; }))
                catUnidades.push({ id: resultado.id, texto: resultado.texto, nombre: resultado.nombre || resultado.texto });
        } else if (tipo === 'area' && typeof catAreas !== 'undefined') {
            if (!catAreas.find(function(a) { return a.id == resultado.id; }))
                catAreas.push({ id: resultado.id, texto: resultado.texto });
        } else if (tipo === 'bloque' && typeof catBloques !== 'undefined') {
            if (!catBloques.find(function(b) { return b.id == resultado.id; }))
                catBloques.push({ id: resultado.id, texto: resultado.texto });
        } else if (tipo === 'material' && typeof catMateriales !== 'undefined') {
            if (!catMateriales.find(function(m) { return m.id == resultado.id; }))
                catMateriales.push({ id: resultado.id, texto: resultado.texto, pu: resultado.pu, uni: resultado.uni, uniTxt: resultado.uniTxt });
        } else if (tipo === 'mano_obra' && typeof catManoObra !== 'undefined') {
            if (!catManoObra.find(function(m) { return m.id == resultado.id; }))
                catManoObra.push({ id: resultado.id, texto: resultado.texto, pu: resultado.pu, uni: resultado.uni, uniTxt: resultado.uniTxt });
        } else if (tipo === 'maquinaria' && typeof catMaquinaria !== 'undefined') {
            if (!catMaquinaria.find(function(m) { return m.id == resultado.id; }))
                catMaquinaria.push({ id: resultado.id, texto: resultado.texto, pu: resultado.pu, uni: resultado.uni, uniTxt: resultado.uniTxt });
        }

        if (typeof showToast === 'function')
            showToast('Registrado correctamente: ' + resultado.texto, 'ok');

        const cb = modalState.callback;
        cerrarModal();
        if (cb) cb(resultado);

    } catch(e) {
        if (typeof showToast === 'function') showToast('Error: ' + e.message, 'err');
        btn.disabled = false;
        btn.innerHTML = '<i class="bi bi-check-lg me-1"></i>Guardar y usar';
    }
};
