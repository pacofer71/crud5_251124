<?php
use App\Bd\Provincia;
use App\Bd\User;
use App\Utils\Validaciones;

session_start();
$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if (!$id || $id <= 0) {
   header("Location:users.php");
   die();
}


require __DIR__ . "/../vendor/autoload.php";

$provincias = Provincia::read();

if(!$usuario=User::getUserById($id)){
    header("Location:users.php");
   die();
}

if(isset($_POST['username'])){
    $username=Validaciones::sanearCadena($_POST['username']);
    $email=Validaciones::sanearCadena($_POST['email']);
    $provincia_id=Validaciones::sanearCadena($_POST['provincia_id']);

    $errores=false;
    
    if(!Validaciones::longitudUsername($username, 5, 20)){
        $errores=true;
    }else{
        if(!Validaciones::isCampoUnico('username', $username)){
            $errores=true;
        }
    }
    
    if(!Validaciones::emailValido($email)){
        $errores=true;
    }else{
        if(!Validaciones::isCampoUnico('email', $email)){
            $errores=true;
        }
    }

    if(!Validaciones::provinciaCorrecta($provincia_id)){
        $errores=true;
    }
    $imagen="img/noimage.png";
    if(is_uploaded_file($_FILES['imagen']['tmp_name'])){
        //el usuario subió un archivo
        if(!Validaciones::imagenValida($_FILES['imagen']['type'], $_FILES['imagen']['size'])){
            $errores=true;
        }else{
            //la imagen es válida y el tamaño correcto
            $imagen="img/".uniqid()."_".$_FILES['imagen']['name']; //"img/123233_nombre.jpg";
            //intentamos guardar la imagen en la ubucacion definitiva
            if(!move_uploaded_file($_FILES['imagen']['tmp_name'], $imagen)){
                $errores=true;
                $_SESSION['err_imagen']="*** Error no se pudo guardar la imagen";

            }
        }

    }

    if($errores){
        header("Location:update.php?id=$id");
        die();
    }
    // todo correcto guardo el usuario
    (new User)
    ->setUsername($username)
    ->setEmail($email)
    ->setImagen($imagen)
    ->setProvinciaId($provincia_id)
    ->create();
    $_SESSION['mensaje']=" Se guardó el usuario";
    header("Location:users.php");
    die();

}

?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Users</title>
    <!-- CDN sweetalert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- CDN tailwind css -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- CDN FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>

<body class="bg-purple-200 p-8">
    <h3 class="py-2 text-center text-xl">Editar usuario</h3>
    <div class="mx-auto w-1/2 rounded-xl shadow-xl border-2 border-black p-6">
        <form method="POST" action="<?= $_SERVER['PHP_SELF']."?id=$id" ?>" enctype="multipart/form-data">
            <div class="mb-5">
                <label for="username" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Username</label>
                <input type="text" id="username" name="username" value="<?= $usuario->getUsername(); ?>" 
                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Username..." />
                <?php
                Validaciones::pintarError('err_username');
                ?>
            </div>
            <div class="mb-5">
                <label for="email" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Email</label>
                <input type="email" id="email" name="email" value="<?= $usuario->getEmail(); ?>" 
                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Email..." />
                <?php
                Validaciones::pintarError('err_email');
                ?>
            </div>
            <div class="mb-5">
                <label for="provincia_id" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Provincia</label>
                <select name="provincia_id" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                    <option>__ Seleccione una provincia __</option>
                    <?php foreach ($provincias as $item): 
                        $selected=($usuario->getProvinciaId()==$item->id) ? "selected" : "";
                    ?>
                        <option value="<?= $item->id ?>" <?=$selected ?>><?= $item->nombre ?></option>
                    <?php endforeach; ?>
                </select>
                <?php
                Validaciones::pintarError('err_provincia_id');
                ?>
            </div>
            <div class="mb-5">
                <label for="imagen" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Imagen</label>
                <div class="flex justify-between">
                    <div>
                        <input type="file" name="imagen" accept="image/*" oninput="imgpreview.src=window.URL.createObjectURL(this.files[0])" />
                        <?php
                        Validaciones::pintarError('err_imagen');
                        ?>
                    </div>
                    <div class="w-full ml-8">
                        <img src="<?= $usuario->getImagen(); ?>" id="imgpreview" class="h-56 w-56 w-full rounded object-fill" />
                    </div>
                </div>
            </div>
            <div class="flex flex-row-reverse mb-2">
                <button type="submit" class="font-bold text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                    <i class="fas fa-edit mr-2"></i>EDITAR
                </button>
                <a href="users.php" class="mr-2 font-bold text-white bg-green-700 hover:bg-green-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                    <i class="fas fa-home mr-2"></i>VOLVER
                </a>
            </div>

        </form>
    </div>
</body>

</html>