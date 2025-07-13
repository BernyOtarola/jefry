<div class="col-sm-2 m-0 p-0 sidebar sidebar-offcanvas" id="sidebar-menu" role="navigation">
    <nav class="nav-container m-0">
        <div class="row m-0 d-sm-none text-white">
            <center class="sidebar-profile"><img src="../public/img/user.png" alt="" class="mt-2 mx-center"></center>
            <?= $nombre; ?>
            <?= $apellido; ?>
        </div>
        <div class="publico m-0">
            <a href="#"><i class='bx bx-home'></i><span>Dashboard</span></a>
            <a href="./proveedor.php" id="provedor"><i class='bx bx-category'></i><span>Proveedor</span></a>
            <a href="./cliente.php" id="cliente"><i class='bx bx-category'></i><span>Cliente</span></a>
            <a href="./productos.php" id="productos"><i class='bx bx-category'></i><span>Productos</span></a>
            
        </div>
        <div class="configuracion">
            <a href="#" onclick="logout()"><i class='bx bx-log-out'></i><span>Salir</span></a>
        </div>
    </nav>
</div>