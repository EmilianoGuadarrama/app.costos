<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

Schema::table('egresos_totales', function(Blueprint $t){
    $t->unsignedInteger('id_material')->nullable()->after('id_obra');
    $t->foreign('id_material')->references('id')->on('materiales')->onDelete('set null');
});
echo "Done\n";
