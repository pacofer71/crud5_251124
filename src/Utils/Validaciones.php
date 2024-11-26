<?php

namespace App\Utils;

use App\Bd\Provincia;
use App\Bd\User;

const TIPOS_MIME = [
    'image/gif',
    'image/png',
    'image/jpeg',
    'image/bmp',
    'image/webp'
];

class Validaciones
{
    public static function sanearCadena(string $cad): string
    {
        return htmlspecialchars(trim($cad));
    }
    public static function longitudUsername(string $valor, int $lMin, int $lMax): bool
    {
        if (strlen($valor) < $lMin || strlen($valor) > $lMax) {
            $_SESSION['err_username'] = "*** Error, introduzca ente $lMin y $lMax caracteres.";
            return false;
        }
        return true;
    }
    public static function emailValido(string $email): bool
    {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $_SESSION['err_email'] = "*** Error, se esperaba una dirección de email válida.";
            return false;
        }
        return true;
    }
    public static function provinciaCorrecta($prov): bool
    {
        if (!in_array($prov, Provincia::getProvArrayId())) {
            $_SESSION['err_provincia_id'] = "*** Error provincia inválida o no seleccionó ninguna";
            return false;
        }
        return true;
    }
    public static function imagenValida(string $tipo, int $size): bool {
        if(!in_array($tipo, TIPOS_MIME)){
            $_SESSION['err_imagen']="*** Error, se esperaba un fichero de imagen";
            return false;
        }
        if($size>200000){
            $_SESSION['err_imagen']="*** Error, la imagen no puede superar los 2MB";
            return false;
        }
        return true;
    }
    public static function isCampoUnico(string $nomCampo, string $valorCampo, ?int $id=null):bool{
        if(User::existeCampo($nomCampo, $valorCampo, $id)){
            $_SESSION["err_$nomCampo"]="*** Error $valorCampo ya está registrado.";
            return false;
        }
        return true;
    }
    public static function pintarError(string $nomError){
        if(isset($_SESSION[$nomError])){
            echo "<p class='text-sm italic mt-2 text-red-600'>{$_SESSION[$nomError]}</p>";
            unset($_SESSION[$nomError]);
        }
    }
}
