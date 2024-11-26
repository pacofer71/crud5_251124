<?php

use App\Bd\User;

session_start();
require __DIR__ . "/../vendor/autoload.php";
$users = User::read();
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
    <h3 class="py-2 text-center text-xl">Listados de Usuarios</h3>
    <!-- --------------------------- Tabla users --------------------------- -->


    <div class="w-1/2 mx-auto p-2">
        <div class="flex flex-row-reverse mb-2">
            <a href="nuevo.php" class="p-2 rounded-xl bg-green-500 hover:bg-green-700">
                <i class="fas fa-add mr-2"></i>NUEVO
            </a>
        </div>
        <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                <tr>
                    <th scope="col" class="px-6 py-3">
                        Name
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Provincia
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Action
                    </th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($users as $item): ?>
                    <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                        <th scope="row" class="flex items-center px-6 py-4 text-gray-900 whitespace-nowrap dark:text-white">
                            <img class="w-10 h-10 rounded-full" src="<?= $item->imagen ?>" alt="Jese image">
                            <div class="ps-3">
                                <div class="text-base font-semibold"><?= $item->username ?></div>
                                <div class="font-normal text-gray-500"><?= $item->email ?></div>
                            </div>
                        </th>
                        <td class="px-6 py-4">
                            <p
                                class="p-2 rounded-xl border-2 border-black text-center text-black"
                                style="background-color:<?= $item->color ?>"><?= $item->nombre ?></p>
                        </td>
                        <td class="px-6 py-4">
                            <a href="#" class="font-medium text-blue-600 dark:text-blue-500 hover:underline">Edit user</a>
                        </td>
                    </tr>
                <?php endforeach; ?>

            </tbody>
        </table>
    </div>

    <!-- -------------------------- Fin Tabla ----------------------------- -->
    <!-- Mensajeria -->
    <?php if (isset($_SESSION['mensaje'])) : ?>
        <script>
            Swal.fire({
                icon: "success",
                title: "<?= $_SESSION['mensaje'] ?>",
                showConfirmButton: false,
                timer: 1500
            });
        </script>
    <?php
        unset($_SESSION['mensaje']);
    endif;
    ?>
</body>

</html>