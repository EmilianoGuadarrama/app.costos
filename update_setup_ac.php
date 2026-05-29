<?php
$file = __DIR__ . '/resources/views/obras/presupuesto_unificado.blade.php';
$content = file_get_contents($file);

// 1. Add the script tag for modal-rapido.js before </script> at the end
$scriptTag = "\n// Cargar modal de registro rapido\n";
$endScript = '</script>';

// 2. Fix the setupAC in addInsumo to pass tipoEntidad
$content = str_replace(
    "setupAC(\n        document.getElementById(`i_txt_\${ii}`),\n        document.getElementById(`i_id_\${ii}`),\n        document.getElementById(`i_list_\${ii}`),\n        cat, false,\n        document.getElementById(`row_\${ii}`).querySelector('.i-pu'),\n        null,\n        null, ii, ci\n    );",
    "// Detectar tipo de entidad para el modal\n    const tipoInsumo = (tbId.startsWith('tb_mat_')) ? 'material' : (tbId.startsWith('tb_maq_')) ? 'maquinaria' : 'mano_obra';\n    setupAC(\n        document.getElementById(`i_txt_\${ii}`),\n        document.getElementById(`i_id_\${ii}`),\n        document.getElementById(`i_list_\${ii}`),\n        cat, false,\n        document.getElementById(`row_\${ii}`).querySelector('.i-pu'),\n        null,\n        null, ii, ci, tipoInsumo\n    );",
    $content
);

// 3. Update global bloque and area setupAC calls to include entity type
$content = str_replace(
    "setupAC(document.getElementById('g_bloque_txt'), document.getElementById('g_bloque'), document.getElementById('g_bloque_list'), catBloques, false, null, null, null, null, null, 'bloque');",
    "setupAC(document.getElementById('g_bloque_txt'), document.getElementById('g_bloque'), document.getElementById('g_bloque_list'), catBloques, false, null, null, null, null, null, 'bloque');",
    $content
);

// 4. Update per-concepto setupAC for bloque/area/concepto
$content = str_replace(
    "setupAC(document.getElementById(`b_txt_\${ci}`), document.getElementById(`b_id_\${ci}`), document.getElementById(`b_list_\${ci}`), catBloques, false, null, null, null);\n    setupAC(document.getElementById(`a_txt_\${ci}`), document.getElementById(`a_id_\${ci}`), document.getElementById(`a_list_\${ci}`), catAreas, false, null, null, null);",
    "setupAC(document.getElementById(`b_txt_\${ci}`), document.getElementById(`b_id_\${ci}`), document.getElementById(`b_list_\${ci}`), catBloques, false, null, null, null, null, null, 'bloque');\n    setupAC(document.getElementById(`a_txt_\${ci}`), document.getElementById(`a_id_\${ci}`), document.getElementById(`a_list_\${ci}`), catAreas, false, null, null, null, null, null, 'area');",
    $content
);

// 5. Add setupUniAC modal integration — update "Registrar nueva unidad" to use abrirModal
$oldUniModal = "divNew.onclick = async () => {\n                acList.style.display = 'none';\n                divNew.textContent = 'Registrando...';\n                const creada = await crearUnidadRapida(this.value.trim());\n                if (creada) {\n                    inp.value = creada.abreviatura;\n                    idFld.value = creada.id;\n                    showToast('✓ Unidad \"' . creada.abreviatura . '\" registrada', 'ok');\n                    if (onSelect) onSelect({ id: creada.id, texto: creada.abreviatura });\n                } else {\n                    showToast('Error al registrar la unidad', 'err');\n                }\n            };";

// Change divNew class and onclick to use modal instead
$content = str_replace(
    "divNew.className = 'ac-item nuevo';",
    "divNew.className = 'ac-item nuevo-full';",
    $content
);
$content = str_replace(
    "divNew.innerHTML = `<i class=\"bi bi-plus-circle me-1\"></i>Registrar unidad \"<strong>\${escHtml(this.value)}</strong>\"`;",
    "divNew.innerHTML = `<i class=\"bi bi-plus-circle-fill me-1\"></i>Registrar \"<strong>\${escHtml(this.value)}</strong>\" como nueva unidad de medida`;",
    $content
);

// Replace the old onclick of divNew (unidad) with abrirModal call
$oldOnclick = 'divNew.onclick = async () => {
                acList.style.display = \'none\';
                divNew.textContent = \'Registrando...\';
                const creada = await crearUnidadRapida(this.value.trim());
                if (creada) {
                    inp.value = creada.abreviatura;
                    idFld.value = creada.id;
                    showToast(\'\u2713 Unidad "\' + creada.abreviatura + \'" registrada\', \'ok\');
                    if (onSelect) onSelect({ id: creada.id, texto: creada.abreviatura });
                } else {
                    showToast(\'Error al registrar la unidad\', \'err\');
                }
            };';

$newOnclick = 'const valEscrito = this.value.trim();
            divNew.onclick = () => {
                acList.style.display = \'none\';
                abrirModal(\'unidad\', valEscrito, (u) => {
                    inp.value   = u.texto;
                    idFld.value = u.id;
                    if (onSelect) onSelect(u);
                });
            };';

$content = str_replace($oldOnclick, $newOnclick, $content);

file_put_contents($file, $content);
echo "OK: setupAC actualizado con tipos de entidad y modal\n";
