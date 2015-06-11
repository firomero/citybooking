/**
 * Created by manytostao on 28/05/15.
 */
'use strict';

/*
 * show.bs.modal
 * shown.bs.modal
 * hide.bs.modal
 * hidden.bs.modal
 *
 * */
var $clienteTable = {};


$clienteTable.postDraw = function () {

    var $td = $('#tabla').find('tbody tr td:first-child');
    $td.each(function () {
        if (!$(this).hasClass('dataTables_empty')) {

            $(this).removeAttr('class');
            $(this).addClass('td-table');
            var html = $(this).html();
            $(this).empty();
            var $input = $('<input type="checkbox" name="delete_all[' + html + ']" value="' + html + '" class="msf" id="' + html + '"> <label for="' + html + '"><span class="lbl"> </span></label>');
            $(this).append($input);
            var $column = $('<td></td>');
            //Botones
            var $btnGroup = $('<div class="btn-group"></div>');
            var $btnEditar = $('<button class="btn btn-mini info edit" ></button>');
            $btnEditar.append($('<i class="icon-edit bigger-125"></i>'));
            $btnEditar.attr('data-id', html);
            var $parent = $(this).closest('tr');
            var $name = $parent.find('.cliente-name');
            var $reference = $parent.find('.cliente-reference');
            $btnEditar.attr('data-name', $name.html());
            $btnEditar.attr('data-reference', $reference.html());

            var $btnEliminar = $('<a class="btn btn-mini danger"></a>');
            $btnEliminar.attr('data-toggle', 'modal');
            $btnEliminar.attr('data-target', '#confirm');
            $btnEliminar.attr('data-id', html);
            $btnEliminar.append($('<i class="icon-trash bigger-125"></i>'));

            $btnGroup.append($btnEditar).append($btnEliminar);
            $column.append($btnGroup);
            $(this).closest('tr').append($column);
            //Events

            //Editar  cliente
            $('.btn.btn-mini.edit').click(function () {

                var $btnEdit = $(this);
                var $saveBtn = $('.btn.btn-default.action');

                $saveBtn.off('click');
                $saveBtn.click(function () {
                    $clienteTable.editCliente($btnEdit);
                });
                var $modalView = $('#myModalDialog');
                $modalView.modal();
                $modalView.find('#myModalLabelConfirmar').text('Editar Cliente');
                $modalView.find('#booking_bookingbundle_cliente_nombre').val($btnEdit.data('name'));
                $modalView.find('#booking_bookingbundle_cliente_referencia').val($btnEdit.data('reference'));
                $modalView.on('hide.bs.modal', function () {
                    $saveBtn.off('click');
                    $saveBtn.click(function (event) {

                        $clienteTable.addCliente();
                    });
                    $modalView.find('#myModalLabelConfirmar').text('Adicionar Cliente');
                });
                $modalView.modal('show');
            });

            //Eliminar Cliente
            $btnEliminar.click(function () {
                var $acept = $('.btn.btn-default.delete');
                var id = $(this).attr('data-id');
                $acept.click(function () {
                    $clienteTable.deleteCliente(id);
                });

            });


        }


    });

};

$clienteTable.addCliente = function () {
    var nombre = $('#booking_bookingbundle_cliente_nombre').val();
    var referencia = $('#booking_bookingbundle_cliente_referencia').val();
    var $modalView = $('#myModalDialog');
    if (nombre != '' || referencia != '') {
        $('.se-pre-con').removeClass('hidden');
        $.post(
            Routing.generate('cliente_ajax_add'),
            {
                nombre: nombre,
                referencia: referencia
            },
            function (data, text, response) {
                if (response.status == 200) {

                    $('.se-pre-con').addClass('hidden');
                    $modalView.modal('hide');
                    location.reload();


                }
            },
            "json"
        ).fail(function (response) {
                var error = $.parseJSON(response.responseText);
                $clienteTable.insertError(error.message);
            });
    }
    else {

        $clienteTable.insertError();
    }
};

$clienteTable.editCliente = function (object) {
    var nombre = $('#booking_bookingbundle_cliente_nombre').val();
    var referencia = $('#booking_bookingbundle_cliente_referencia').val();
    var id = $(object).data('id');
    var $modalView = $('#myModalDialog');
    if (nombre != '' || referencia != '') {
        $('.se-pre-con').removeClass('hidden');
        $.post(
            Routing.generate('cliente_ajax_edit'),
            {
                nombre: nombre,
                referencia: referencia,
                id: id
            },
            function (data, text, response) {
                if (response.status == 200) {

                    $('.se-pre-con').addClass('hidden');
                    $modalView.modal('hide');
                    location.reload();


                }
            },
            "json"
        ).fail(function (response) {
                var error = $.parseJSON(response.responseText);
                $clienteTable.insertError(error.message);
            });
    }
    else {

        $clienteTable.insertError();
    }
};

$clienteTable.deleteCliente = function (id) {
    $('.se-pre-con').removeClass('hidden');
    $.post(
        Routing.generate('cliente_ajax_delete'),
        {
            id: id
        },
        function (data, text, response) {
            if (response.status == 204 || response.status == 200) {

                $('.se-pre-con').addClass('hidden');
                $('#myModalDialog').modal('hide');
                location.reload();
            }
        },
        "json"
    ).fail(function (response) {
            var error = $.parseJSON(response.responseText);
            $clienteTable.insertError(error.message);
        });
};

$clienteTable.insertError = function (errorText) {
    var $modalView = $('#myModalDialog');
    $modalView.find('.alert.alert-danger').remove();
    var $cleanedText = errorText.replace('Object(Booking\\BookingBundle\\Entity\\Cliente).', '');
    var $nombreErrorText;
    if ($cleanedText.indexOf('nombre:') != -1)
        $nombreErrorText = $cleanedText.substring($cleanedText.indexOf('nombre:') + 7, $cleanedText.indexOf('.') + 1);
    var $nombreError;
    if ($nombreErrorText != null) {
        $nombreError = $('<div class="alert alert-danger"><button class="close" data-dismiss="alert" type="button"></button>' + $nombreErrorText + '<strong class="icon-remove close"></strong> </div>');
    }
    $modalView.find('#booking_bookingbundle_cliente_nombre').parent().append($nombreError);
    $modalView.find('.close').click(function () {
        $(this).closest('.alert.alert-danger').remove();
    });
};

$(function () {
    //Adicionar Cliente
    var $btnAction = $('.btn.btn-default.action');
    $btnAction.click(function (event) {

        $clienteTable.addCliente();
    });
});