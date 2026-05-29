@extends('layout')
@section('title', 'Confirmar Fechas — ' . ($obra->datosDeObra?->nombre ?? 'Obra'))
@section('content')
<style>
.form-wrap { max-width: 860px; margin: 0 auto; }
.form-title { font-size: 1.7rem; font-weight: 800; color: #111; margin: 0 0 4px; }
.form-sub   { color: #6b7280; font-size: .9rem; margin: 0 0 28px; }
.fs-card {
    background: #fff; border: 1px solid #e5e7eb; border-radius: 16px;
    padding: 24px 28px; margin-bottom: 20px;
    box-shadow: 0 2px 8px rgba(0,0,0,.04);
}
.fs-card-title {
    font-size: .68rem; font-weight: 700; text-transform: uppercase;
    letter-spacing: 2px; color: #9ca3af;
    margin-bottom: 20px; padding-bottom: 10px;
    border-bottom: 1px solid #f3f4f6;
    display: flex; align-items: center; gap: 8px;
}
.fs-card-title i { color: #059669; }
.fg-3 { display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 16px; }
label.fl { font-size: .8rem; font-weight: 700; color: #374151; display: block; margin-bottom: 5px; }
label.fl span { color: #dc2626; }
.fc {
    width: 100%; padding: .55rem .85rem;
    border: 1.5px solid #e5e7eb; border-radius: 9px;
    font-size: .86rem; background: #fff; color: #111; transition: border-color .2s;
}
.fc:focus { border-color: #059669; outline: none; box-shadow: 0 0 0 3px rgba(5,150,105,.1); }
.form-actions { display: flex; justify-content: flex-end; gap: 12px; margin-top: 8px; }
.btn-save {
    background: #059669; color: #fff; border: none; border-radius: 11px;
    padding: .75rem 2rem; font-size: .9rem; font-weight: 700; cursor: pointer;
    display: inline-flex; align-items: center; gap: 7px;
}
.btn-save:hover { background: #047857; }

/* CALENDARIO DÍAS INHÁBILES */
.inhab-cal-wrap { margin-top: 25px; padding: 18px; border: 1.5px dashed #e5e7eb; border-radius: 12px; background: #f9fafb; display: block; }
.cal-hdr { display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px; }
.cal-hdr button { background: none; border: none; font-size: 1.1rem; color: #4b5563; cursor: pointer; }
.cal-hdr span { font-weight: 700; font-size: .9rem; color: #111; text-transform: uppercase; }
.cal-grid { display: grid; grid-template-columns: repeat(7, 1fr); gap: 4px; text-align: center; }
.cal-grid-hdr { font-size: .7rem; font-weight: 700; color: #6b7280; padding-bottom: 6px; }
.day-cell { padding: 8px 0; border-radius: 8px; font-size: .85rem; cursor: pointer; transition: .2s; color: #111; border: 1.5px solid transparent; }
.day-cell:hover:not(.other-month):not(.disabled-day) { background: #e5e7eb; }
.day-cell.other-month { color: transparent; pointer-events: none; }
.day-cell.sunday { color: #dc2626; opacity: 0.6; pointer-events: none; }
.day-cell.selected { background: #fee2e2; border-color: #fca5a5; color: #b91c1c; font-weight: 700; }
.day-cell.saved-inhabil { background: #fef08a; border-color: #fde047; color: #854d0e; font-weight: 700; }
.inhab-chips { display: flex; flex-wrap: wrap; gap: 8px; margin-top: 15px; }
.inhab-chip { background: #f3f4f6; border: 1px solid #e5e7eb; border-radius: 20px; padding: 4px 12px; font-size: .75rem; font-weight: 600; color: #374151; display: inline-flex; align-items: center; gap: 6px; }
.btn-rm-chip { background: none; border: none; color: #9ca3af; font-size: .8rem; cursor: pointer; padding: 0; }
.btn-rm-chip:hover { color: #dc2626; }
</style>

<div class="form-wrap">
    @if(session('success'))
    <div style="background: #ecfdf5; border: 1px solid #a7f3d0; border-radius: 10px; padding: 12px 16px; color: #047857; font-size: .85rem; margin-bottom: 18px; font-weight:600;">
        <i class="bi bi-check-circle me-1"></i> {{ session('success') }}
    </div>
    @endif

    <h1 class="form-title">Confirmar Fechas de la Obra</h1>
    <p class="form-sub"><strong>{{ $obra->datosDeObra?->nombre ?? "Obra #$obra->id" }}</strong> — Por favor ajusta las fechas iniciales y los días inhábiles si es necesario antes de continuar.</p>

    <form action="{{ route('obras_proceso.fechas.update', $obra->id) }}" method="POST" id="formFechas">
        @csrf

        <div class="fs-card">
            <div class="fs-card-title"><i class="bi bi-calendar-event"></i> Fechas y Días Inhábiles</div>
            <div class="fg-3">
                <div>
                    <label class="fl" for="fecha_inicio">Fecha de Inicio <span>*</span></label>
                    <input type="date" id="fecha_inicio" name="fecha_inicio" class="fc" required
                           value="{{ old('fecha_inicio', $obra->fecha_inicio?->format('Y-m-d') ?? date('Y-m-d')) }}">
                </div>
                <div>
                    <label class="fl" for="duracion">Duración (días) <span>*</span></label>
                    <input type="number" id="duracion" name="duracion" class="fc" min="1" required
                           value="{{ old('duracion', $obra->duracion ?? 30) }}">
                </div>
                <div>
                    <label class="fl">Fecha est. entrega</label>
                    <input type="text" id="fecha_entrega_display" class="fc" readonly
                           style="background:#f9fafb;color:#6b7280; font-weight:600;">
                    <input type="hidden" id="estimacion_de_entrega_ymd" name="estimacion_de_entrega_ymd">
                </div>
            </div>

            <!-- CALENDARIO DE DÍAS INHÁBILES -->
            <div class="inhab-cal-wrap" id="inhabCal">
                <div class="cal-hdr">
                    <button type="button" onclick="cambiarMes(-1)"><i class="bi bi-chevron-left"></i></button>
                    <span id="mesLabel"></span>
                    <button type="button" onclick="cambiarMes(1)"><i class="bi bi-chevron-right"></i></button>
                </div>
                <div class="cal-grid">
                    <div class="cal-grid-hdr">Dom</div>
                    <div class="cal-grid-hdr">Lun</div>
                    <div class="cal-grid-hdr">Mar</div>
                    <div class="cal-grid-hdr">Mié</div>
                    <div class="cal-grid-hdr">Jue</div>
                    <div class="cal-grid-hdr">Vie</div>
                    <div class="cal-grid-hdr">Sáb</div>
                </div>
                <div class="cal-grid" id="diasGrid"></div>
                
                <div style="margin-top:20px;">
                    <label class="fl">Días inhábiles seleccionados (se omitirán en el cálculo):</label>
                    <div class="inhab-chips" id="inhabChips">Ninguno</div>
                </div>
                <div style="margin-top:10px;font-size:.72rem;color:#92400e;"><i class="bi bi-info-circle me-1"></i>Los domingos ya no se cuentan automáticamente. Haz clic en un día para marcarlo/desmarcarlo como inhábil.</div>
            </div>
            
            <input type="hidden" id="diasInhabilesJson" name="dias_inhabiles_json" value="[]">
        </div>

        <div class="form-actions">
            <button type="submit" class="btn-save">
                <i class="bi bi-play-circle me-1"></i> Iniciar Obra en Proceso
            </button>
        </div>
    </form>
</div>

<script>
let inhabLocales = {}; // {YYYY-MM-DD: 'descripcion'}

const inhabBD = @json(isset($diasInhabile) ? $diasInhabile->map(fn($d) => ['fecha' => $d->fecha->format('Y-m-d'), 'descripcion' => $d->descripcion ?? '']) : collect());
inhabBD.forEach(d => { inhabLocales[d.fecha] = d.descripcion || 'Día inhábil'; });

function calcularFechaEntrega() {
    const fi  = document.getElementById('fecha_inicio').value;
    const dur = parseInt(document.getElementById('duracion').value);
    const out = document.getElementById('fecha_entrega_display');
    const outHidden = document.getElementById('estimacion_de_entrega_ymd');
    
    if (!fi || !dur || dur < 1) { 
        out.value = ''; 
        outHidden.value = '';
        return; 
    }

    let d = new Date(fi + 'T12:00:00'); // Mediodía para evitar problema de TZ
    let diasRestantes = dur;

    while (diasRestantes > 0) {
        d.setDate(d.getDate() + 1);
        const dayOfWeek = d.getDay(); // 0 = domingo
        const key = d.toISOString().slice(0,10);
        
        if (dayOfWeek === 0) continue;         // Omitir domingos
        if (inhabLocales[key] !== undefined) continue; // Omitir inhábiles
        
        diasRestantes--;
    }

    out.value = d.toLocaleDateString('es-MX', { day:'2-digit', month:'2-digit', year:'numeric' });
    outHidden.value = d.toISOString().slice(0,10);
}

document.getElementById('fecha_inicio').addEventListener('input', () => {
    calcularFechaEntrega();
    renderCal();
});
document.getElementById('duracion').addEventListener('input', calcularFechaEntrega);

let calMes  = new Date().getMonth();
let calAnio = new Date().getFullYear();

function cambiarMes(delta) {
    calMes += delta;
    if (calMes < 0)  { calMes = 11; calAnio--; }
    if (calMes > 11) { calMes = 0;  calAnio++; }
    renderCal();
}

function renderCal() {
    const meses = ['Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre'];
    document.getElementById('mesLabel').textContent = meses[calMes] + ' ' + calAnio;

    const grid = document.getElementById('diasGrid');
    grid.innerHTML = '';

    const primerDia = new Date(calAnio, calMes, 1).getDay(); // 0=dom
    const diasEnMes = new Date(calAnio, calMes + 1, 0).getDate();

    for (let i = 0; i < primerDia; i++) {
        const div = document.createElement('div');
        div.className = 'day-cell other-month';
        grid.appendChild(div);
    }

    const fi_val = document.getElementById('fecha_inicio').value;
    const fechaInicioObj = fi_val ? new Date(fi_val + 'T00:00:00') : null;

    for (let d = 1; d <= diasEnMes; d++) {
        const fecha = new Date(calAnio, calMes, d);
        const key   = fecha.toISOString().slice(0,10);
        const dow   = fecha.getDay();

        const div = document.createElement('div');
        div.className = 'day-cell' + (dow===0 ? ' sunday' : '');
        div.textContent = d;

        if (inhabBD.find(x => x.fecha === key)) div.classList.add('saved-inhabil');
        else if (inhabLocales[key] !== undefined) div.classList.add('selected');

        const esAnterior = (fechaInicioObj && fecha < fechaInicioObj);

        if (esAnterior) {
            div.classList.add('disabled-day');
            div.title = 'No se puede seleccionar antes de la fecha de inicio';
            div.style.opacity = '0.4';
            div.style.cursor = 'not-allowed';
            div.style.background = '#e5e7eb';
        } else if (dow !== 0) {
            div.onclick = () => toggleInhabil(key, d);
        }
        grid.appendChild(div);
    }
    renderChips();
}

function toggleInhabil(key, dia) {
    if (inhabLocales[key] !== undefined) {
        delete inhabLocales[key];
    } else {
        inhabLocales[key] = 'Día inhábil personalizado';
    }
    
    // Solo guardar los locales nuevos en el JSON (no los de BD)
    const localesAGuardar = {};
    for (const [k, v] of Object.entries(inhabLocales)) {
        if (!inhabBD.find(x => x.fecha === k)) {
            localesAGuardar[k] = v;
        }
    }
    document.getElementById('diasInhabilesJson').value = JSON.stringify(localesAGuardar);
    
    renderCal();
    calcularFechaEntrega();
}

function renderChips() {
    const chips = document.getElementById('inhabChips');
    const claves = Object.keys(inhabLocales);
    if (!claves.length) { chips.textContent = 'Ninguno'; return; }
    chips.innerHTML = claves.sort().map(k => {
        const [y,m,d] = k.split('-');
        return `<span class="inhab-chip">${d}/${m}/${y}
            <button type="button" class="btn-rm-chip" onclick="toggleInhabil('${k}')">✕</button>
        </span>`;
    }).join('');
}

// Inicializar
calcularFechaEntrega();
renderCal();

</script>
@endsection
