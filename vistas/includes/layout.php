<?php
/*session_start();

if (!isset($_SESSION["id"])) {
    header("Location: login.php");
} else {
    $user_id = isset($_SESSION["id"]) ? $_SESSION["id"] : null;
    $nombre = isset($_SESSION["nombre"]) ? $_SESSION["nombre"] : null;
    $apellido = isset($_SESSION["apellido"]) ? $_SESSION["apellido"] : null;
    $email = isset($_SESSION["email"]) ? $_SESSION["email"] : null;
}*/
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <?php include('head_tags.php'); ?>
</head>

<body>
    <?php include('encabezado.php'); ?>

    <main class="container-fluid">
        <div class="row row-offcanvas row-offcanvas-left">
            <?php include('../vistas/menu.php'); ?>

            <div class="col main pt-5 mt-3">
                <?= $content ?>
            </div>
        </div>
    </main>

    <?php include('footer.php'); ?>
</body>

</html>