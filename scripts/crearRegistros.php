<?php

use App\Bd\Provincia;
use App\Bd\User;

require __DIR__."/../vendor/autoload.php";
$cant=0;
do{
    $cant=(int)readline("Dame el número de registros (5-50), '0' para salir:");
    if($cant==0){
        echo "\nSaliendo a petición del usuario...";
        break;
    }
    if($cant<5 || $cant>50){
        echo "\nSe esperaba una cantidad entre 5 y 50, '0' para salir";
    }
}while($cant<5 || $cant>50);
if($cant){
    Provincia::generarProvincias();
    User::crearUsersRandom($cant);
    echo "\nSe ha creado $cant usuarios...".PHP_EOL;
}