<?php
$title = 'Cliente';
ob_start();
?>

<style>
    #listadoregistros {
        display: none;
    }
</style>

<!-- Cliente Modal -->
<div class="modal" id="cli-modal">
    <div class="modal-dialog">
        <div class="modal-content">

            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>

            <div class="modal-body" id="eliminar-body">
                ¿Seguro deseas eliminar el cliente?
            </div>

            <div class="modal-footer" id="eliminar-footer">
                <button type="button" class="btn btn-success" data-dismiss="modal"><i class="fa fa-chevron-left"></i> Salir</button>
                <button type="button" class="btn btn-danger" onclick="eliminar()"><i class="fa fa-trash"></i> Eliminar</button>
            </div>

            <input type="hidden" id="modal-cliente-id">
        </div>
    </div>
</div>
<!-- Cliente Modal End -->

<h1 class="display-4"><?= $title ?></h1>

<div class="mb-3 card p-3 d-none" id="top-form">
    <div class="row">
        <div class="col-sm-1">
            <label for="id">ID:</label>
            <input readonly class="form-control" type="text" id="id" name="id">
        </div>
        <div class="col-sm-3">
            <label for="nombre">Nombre:</label>
            <input class="form-control" type="text" id="nombre" name="nombre">
        </div>
        <div class="col-sm-3">
            <label for="telefono">Teléfono:</label>
            <input class="form-control" type="text" id="telefono" name="telefono">
        </div>
        <div class="col-sm-5">
            <label for="correo">Correo:</label>
            <input class="form-control" type="email" id="correo" name="correo">
        </div>
        <input type="hidden" id="nuevo">
    </div>
    <div class="row p-3">
        <button type="button" class="col-sm-2 mr-1 btn btn-success" id="Guardar" onclick="guardar()" disabled><i class="fa fa-save"></i> Guardar</button>
        <button type="button" class="col-sm-2 mr-1 btn btn-secondary" id="Cancelar" onclick="cancelar()"><i class="fa fa-times"></i> Cancelar</button>
    </div>
</div>

<div class="mb-3 card p-3" id="listadoregistros">
    <div class="row table-responsive pl-3">
        <button class="btn btn-success my-3 float-right" onclick="agregar()"><i class="fa fa-plus"></i> Agregar</button>

        <table id="tbllistado" class="table table-striped table-bordered table-condensed table-hover">
            <thead>
                <th>ID</th>
                <th>Nombre</th>
                <th>Teléfono</th>
                <th>Correo</th>
                <th>Opciones</th>
            </thead>
            <tbody></tbody>
            <tfoot>
                <th>ID</th>
                <th>Nombre</th>
                <th>Teléfono</th>
                <th>Correo</th>
                <th>Opciones</th>
            </tfoot>
        </table>
    </div>
</div>

<?php
$content = ob_get_clean();
include './includes/layout.php';
?>

<script>
    listar();

    function habilitar_botones() {
        $("#Cancelar").prop("disabled", false);
        $("#Guardar").prop("disabled", false);
    }

    function desabilitar_botones() {
        $("#Cancelar").prop("disabled", true);
        $("#Guardar").prop("disabled", true);
    }

    function agregar() {
        $("#top-form").removeClass('d-none');
        $("#listadoregistros").hide();
        habilitar_botones();
        $("#id").val("");
        $("#nombre").val("");
        $("#telefono").val("");
        $("#correo").val("");
        $("#nuevo").val(1);
    }

    function showModal(id) {
        $('#modal-cliente-id').val(id);
        $('#cli-modal').modal('show');
    }

    function eliminar() {
        var id = $('#modal-cliente-id').val();
        $.post("../ajax/cliente.php?op=eliminar", { id }, function(response) {
            Swal.fire(response);
            listar();
        });
        $('#cli-modal').modal('hide');
    }

    function guardar() {
        var id = $("#id").val();
        var nombre = $("#nombre").val();
        var telefono = $("#telefono").val();
        var correo = $("#correo").val();
        var nuevo = $("#nuevo").val();
        var url = nuevo == 1 ? "../ajax/cliente.php?op=guardar" : "../ajax/cliente.php?op=editar";

        if (nombre === '' || telefono === '' || correo === '') {
            Swal.fire('Faltan datos');
        } else {
            $.post(url, { id, nombre, telefono, correo }, function(response) {
                Swal.fire(response);
                listar();
            });
        }
    }

    function editar(idcliente) {
        habilitar_botones();
        $("#listadoregistros").hide();
        $("#top-form").removeClass('d-none');
        $("#nuevo").val(0);

        $.post("../ajax/cliente.php?op=mostrar", { id: idcliente }, function(response) {
            var data = JSON.parse(response);
            $("#id").val(data.id);
            $("#nombre").val(data.nombre);
            $("#telefono").val(data.telefono);
            $("#correo").val(data.correo);
        });
    }

    function cancelar() {
        $("#id").val("");
        $("#nombre").val("");
        $("#telefono").val("");
        $("#correo").val("");
        desabilitar_botones();
        listar();
    }

    function listar() {
        $("#listadoregistros").show();
        $("#top-form").addClass('d-none');

        tabla = $('#tbllistado').dataTable({
            "aProcessing": true,
            "aServerSide": true,
            dom: 'Bfrtip',
            buttons: ['copyHtml5', 'excelHtml5', 'csvHtml5', 'pdf'],
            "ajax": {
                url: "../ajax/cliente.php?op=listar",
                type: "get",
                dataType: "json",
                error: function(e) {
                    console.log(e.responseText);
                }
            },
            "bDestroy": true,
            "iDisplayLength": 5,
            "order": [[0, "asc"]]
        }).DataTable();
    }
</script>
</body>
</html>
<?php