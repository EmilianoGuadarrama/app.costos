<?php
$file = __DIR__ . '/resources/views/obras/presupuesto_unificado.blade.php';
$content = file_get_contents($file);

$find = "const csrfToken = '{{ csrf_token() }}';\n";
$replace = "const csrfToken = '{{ csrf_token() }}';\nwindow._csrfToken = csrfToken;\nwindow._apiUrls = {\n    unidad:    '{{ route(\"api.unidades.storeRapida\") }}',\n    area:      '{{ route(\"api.areas.storeRapida\") }}',\n    bloque:    '{{ route(\"api.bloques.storeRapida\") }}',\n    material:  '{{ route(\"api.materiales.storeRapida\") }}',\n    mano_obra: '{{ route(\"api.mano_obra.storeRapida\") }}',\n    maquinaria:'{{ route(\"api.maquinaria.storeRapida\") }}'\n};\n";

if (strpos($content, $find) === false) {
    echo "ERROR: Texto no encontrado\n";
    exit(1);
}

$newContent = str_replace($find, $replace, $content);
file_put_contents($file, $newContent);
echo "OK: API URLs inyectadas\n";
