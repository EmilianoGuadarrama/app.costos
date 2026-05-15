<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$req = new \Illuminate\Http\Request();
$req->merge(['items' => ['concepto' => [1 => ['pu' => 600, 'cantidad' => 3]]]]);
$controller = new \App\Http\Controllers\PresupuestoController();
$result = $controller->updateAll($req, 1);
var_dump($result);
