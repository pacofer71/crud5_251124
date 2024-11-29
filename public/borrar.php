<?php

use App\Bd\User;


session_start();
$id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
if (!$id || $id <= 0) {
   header("Location:users.php");
   die();
}
require __DIR__ . "/../vendor/autoload.php";
if (!$imagen = User::getImagenById($id)) {
    header("Location:users.php");
    die();
}
User::delete($id);
if(basename($imagen)!='noimage.png'){
    unlink($imagen);
}
$_SESSION['mensaje']="Usuario eliminado.";
header("Location:users.php");