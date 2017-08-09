function setNuevoCLienteForm() {
    $("#btn_add_nuevo").click(function (e) {

        e.preventDefault();
        if (clientes < 10) {
            clientes++;
            $("#form_nuevo_cliente").append('<div><div class="col-md-3">' +
                    '<div class="form-group label-floating">' +
                    '<label class="control-label">Nombres:</label>' +
                    '<input type="text" name="nombreNuevoCliente[]" class="form-control">' +
                    '</div>' +
                    '</div>' +
                    '<div class="col-md-3">' +
                    '<div class="form-group label-floating">' +
                    '<label class="control-label">Apellidos:</label>' +
                    '<input type="text" name="apellidoNuevoCliente[]" class="form-control">' +
                    '</div>' +
                    '</div>' +
                    '<div class="col-md-3">' +
                    '<div class="form-group label-floating">' +
                    '<label class="control-label">Cedula:</label>' +
                    '<input type="text" name="cedulanNuevoCliente[]" class="form-control">' +
                    '</div>' +
                    '</div>' +
                    '<div class="col-md-3">' +
                    '<div class="form-group label-floating">' +
                    '<label class="control-label">Dirección:</label>' +
                    '<input type="text" name="direccionNuevoCliente[]" class="form-control">' +
                    '</div></div><br>' +
                    '<button class="remove_field btn btn-primary">Eliminar</button></div>'
                    );
        }
    });
    $("#form_nuevo_cliente").on("click", ".remove_field", function (e) { //user click on remove text
        e.preventDefault();
        $(this).parent('div').remove();
        clientes--;
    });

}

function setNumberItems() {

    $('.numero').maskMoney();
}

function setFechasNuevo() {

    $('.date').datetimepicker({
        format: 'DD/MM/YYYY'
    });
}

function setNuevaObligacion() {

    $("#btn_add_obligacion").click(function (e) {

        e.preventDefault();

        if (obligations < 10) {
            obligations++;
            $("#form_obligacion").append('<div><div class="col-md-3">' +
                    '<div class="form-group label-floating">' +
                    '<label class="control-label">Obligacion</label>' +
                    '<input name="numNuevaObligacion[]" type="text" class="form-control">' +
                    '</div>' +
                    '</div>' +
                    '<div class="col-md-3">' +
                    '<div class="form-group label-floating">' +
                    '<label class="control-label">Pagare</label>' +
                    '<input name="pagareNuevaObligacion[]" type="text" class="form-control">' +
                    '</div>' +
                    '</div>' +
                    '<div class="col-md-2">' +
                    '<div class="form-group label-floating">' +
                    '<label class="control-label">Valor</label>' +
                    '<input name="valorNuevaObligacion[]" type="text" class="form-control numero">' +
                    ' <script>' + 'setNumberItems();' + '</script>' +
                    '</div>' +
                    '</div>' +
                    '<div class="col-md-2">' +
                    '<div class="form-group label-floating">' +
                    '<label class="control-label">Cuantía</label>' +
                    '<input name="cuantiaNuevaObligacion[]" type="text" class="form-control">' +
                    '</div></div><br>' +
                    '<button class="remove_field btn btn-primary">Eliminar</button></div>'
                    );
        }

    });
    $("#form_obligacion").on("click", ".remove_field", function (e) { //user click on remove text
        e.preventDefault();
        $(this).parent('div').remove();
        obligations--;
    });
}

function buscar() {
    var cedula = $("#inputBuscar").val();
    if (cedula === null || cedula === '') {

        $("#inputBuscar").focus();
    } else if ($("#resultBusqueda").is(':empty')) {

        $.ajax({
            type: 'POST',
            url: 'PhpFiles/Functions.php',
            data: {
                functionName: 'buscarProceso',
                cedula: $("#inputBuscar").val()
            },
            success: function (data) {
                var json = JSON.parse(data);
                $("#resultBusqueda").append(json.tabla);
                id = json.id;
                console.log(id);
            }
        });

    }
}

function mostrarModal() {
    $("#myModal").modal('show');
}

function volver() {
    window.location.replace("index.php");
}

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


