<?php

use Carbon\Carbon;

require "vendor/autoload.php";

//require 'classes/Calcul.php';

$instance = new App\Calcul();

$resultat = $instance->additionner(10, 20);

var_dump($resultat);

$aujourdhui = Carbon::now();

var_dump($aujourdhui->format('d/m/y'));

printf("Right now is %s", Carbon::now()->toDateTimeString());
printf("Right now in Vancouver is %s", Carbon::now('America/Vancouver'));

echo "Hello Word !";
