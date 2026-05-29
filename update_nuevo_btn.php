<?php
$file = __DIR__ . '/resources/views/obras/presupuesto_unificado.blade.php';
$content = file_get_contents($file);

// The old text (using actual special chars as they appear in the file)
$old = "        // Opci\u00f3n \"Registrar como nuevo\" solo si no hay coincidencia exacta\n        if (q.length > 0) {\n            const divNuevo = document.createElement('div');\n            divNuevo.className   = 'ac-item nuevo';\n            divNuevo.innerHTML   = '<i class=\"bi bi-plus-circle me-1\"></i> Registrar como nuevo';\n            divNuevo.onclick = () => { idFld.value = ''; list.style.display = 'none'; };\n            list.appendChild(divNuevo);\n        }";

$new = "        // Opcion \"Registrar como nuevo\" - abre modal completo si hay tipo de entidad
        if (q.length > 0) {
            const divNuevo = document.createElement('div');
            if (tipoEntidad && typeof abrirModal === 'function') {
                divNuevo.className = 'ac-item nuevo-full';
                const _labelTipo = { unidad:'unidad de medida', area:'area', bloque:'bloque', material:'material', mano_obra:'mano de obra', maquinaria:'maquinaria/equipo' }[tipoEntidad] || tipoEntidad;
                divNuevo.innerHTML = '<i class=\"bi bi-plus-circle-fill me-1\"></i>Registrar \"<strong>' + escHtml(q) + '</strong>\" como nuevo ' + _labelTipo;
                const _valCapturado = q;
                divNuevo.onclick = () => {
                    list.style.display = 'none';
                    abrirModal(tipoEntidad, _valCapturado, (resultado) => {
                        inp.value   = resultado.texto;
                        idFld.value = resultado.id;
                        if (puFld && resultado.pu != null) puFld.value = resultado.pu;
                        if (ii != null) {
                            const _uTxt = document.getElementById('i_uni_txt_' + ii);
                            const _uHid = document.getElementById('i_uni_hid_' + ii);
                            if (_uTxt && resultado.uniTxt) { _uTxt.value = resultado.uniTxt; _uHid.value = resultado.uni; }
                            if (cardCi != null) updateSubtotal(ii, cardCi);
                        }
                        if (ci != null) {
                            const _cUTxt = document.getElementById('c_uni_txt_' + ci);
                            const _cUId  = document.getElementById('c_uni_id_' + ci);
                            if (_cUTxt && resultado.uniTxt) { _cUTxt.value = resultado.uniTxt; _cUId.value = resultado.uni; }
                        }
                    });
                };
            } else {
                divNuevo.className = 'ac-item nuevo';
                divNuevo.innerHTML = '<i class=\"bi bi-plus-circle me-1\"></i> Registrar como nuevo';
                divNuevo.onclick = () => { idFld.value = ''; list.style.display = 'none'; };
            }
            list.appendChild(divNuevo);
        }";

if (strpos($content, $old) !== false) {
    $content = str_replace($old, $new, $content);
    file_put_contents($file, $content);
    echo "OK: setupAC 'Registrar' actualizado con modal\n";
} else {
    // Try simpler search
    $simpleSearch = "divNuevo.className   = 'ac-item nuevo';";
    $pos = strpos($content, $simpleSearch);
    if ($pos !== false) {
        // Find the full block
        $blockStart = strrpos(substr($content, 0, $pos), "if (q.length > 0) {");
        $blockEnd   = strpos($content, "list.appendChild(divNuevo);\n        }", $pos) + strlen("list.appendChild(divNuevo);\n        }");
        $oldBlock   = substr($content, $blockStart, $blockEnd - $blockStart);
        $content    = str_replace($oldBlock, $new, $content);
        file_put_contents($file, $content);
        echo "OK (alternativo): Bloque reemplazado\n";
    } else {
        echo "ERROR: No se encontro el patron\n";
    }
}
