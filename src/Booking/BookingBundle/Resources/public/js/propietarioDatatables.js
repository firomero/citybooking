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
var $propietarioTable = {};


$propietarioTable.postDraw = function () {

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
            var $name = $parent.find('.propietario-name');
            var $ci = $parent.find('.propietario-ci');
            $btnEditar.attr('data-name', $name.html());
            $btnEditar.attr('data-ci', $ci.html());

            var $btnEliminar = $('<a class="btn btn-mini danger"></a>');
            $btnEliminar.attr('data-toggle', 'modal');
            $btnEliminar.attr('data-target', '#confirm');
            $btnEliminar.attr('data-id', html);
            $btnEliminar.append($('<i class="icon-trash bigger-125"></i>'));

            $btnGroup.append($btnEditar).append($btnEliminar);
            $column.append($btnGroup);
            $(this).closest('tr').append($column);
            //Events

            //Editar  propietario
            $('.btn.btn-mini.edit').click(function () {

                var $btnEdit = $(this);
                var $saveBtn = $('.btn.btn-default.action');

                $saveBtn.off('click');
                $saveBtn.click(function () {
                    $propietarioTable.editPropietario($btnEdit);
                });
                var $modalView = $('#myModalDialog');
                $modalView.modal();
                $modalView.find('#myModalLabelConfirmar').text('Editar Propietario');
                $modalView.find('#booking_bookingbundle_propietario_nombre').val($btnEdit.data('name'));
                $modalView.find('#booking_bookingbundle_propietario_ci').val($btnEdit.data('ci'));
                $modalView.on('hide.bs.modal', function () {
                    $saveBtn.off('click');
                    $saveBtn.click(function (event) {

                        $propietarioTable.addPropietario();
                    });
                    $modalView.find('#myModalLabelConfirmar').text('Adicionar Propietario');
                });
                $modalView.modal('show');
            });

            //Eliminar Propietario
            $btnEliminar.click(function () {
                var $acept = $('.btn.btn-default.delete');
                var id = $(this).attr('data-id');
                $acept.click(function () {
                    $propietarioTable.deletePropietario(id);
                });

            });


        }


    });

};

$propietarioTable.addPropietario = function () {
    var nombre = $('#booking_bookingbundle_propietario_nombre').val();
    var ci = $('#booking_bookingbundle_propietario_ci').val();
    var $modalView = $('#myModalDialog');
    if (nombre != '' || ci != '') {
        $('.se-pre-con').removeClass('hidden');
        $.post(
            Routing.generate('propietario_ajax_add'),
            {
                nombre: nombre,
                ci: ci
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
                $propietarioTable.insertError(error.message);
            });
    }
    else {

        $propietarioTable.insertError();
    }
};

$propietarioTable.editPropietario = function (object) {
    var nombre = $('#booking_bookingbundle_propietario_nombre').val();
    var ci = $('#booking_bookingbundle_propietario_ci').val();
    var id = $(object).data('id');
    var $modalView = $('#myModalDialog');
    if (nombre != '' || ci != '') {
        $('.se-pre-con').removeClass('hidden');
        $.post(
            Routing.generate('propietario_ajax_edit'),
            {
                nombre: nombre,
                ci: ci,
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
                $propietarioTable.insertError(error.message);
            });
    }
    else {

        $propietarioTable.insertError();
    }
};

$propietarioTable.deletePropietario = function (id) {
    $('.se-pre-con').removeClass('hidden');
    $.post(
        Routing.generate('propietario_ajax_delete'),
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
            $propietarioTable.insertError(error.message);
        });
};

$propietarioTable.insertError = function (errorText) {
    var $modalView = $('#myModalDialog');
    $modalView.find('.alert.alert-danger').remove();
    var $cleanedText = errorText.replace('Object(Booking\\BookingBundle\\Entity\\Propietario).', '').replace('Object(Booking\\BookingBundle\\Entity\\Propietario).', '');
    var $nombreErrorText;
    var $ciErrorText;
    if ($cleanedText.indexOf('nombre:') != -1)
        $nombreErrorText = $cleanedText.substring($cleanedText.indexOf('nombre:') + 7, $cleanedText.indexOf('.') + 1);
    if ($cleanedText.indexOf('ci:') != -1)
        $ciErrorText = $cleanedText.substring($cleanedText.indexOf('ci:') + 3, $cleanedText.lastIndexOf('.') + 1);
    var $nombreError;
    if ($nombreErrorText != null) {
        $nombreError = $('<div class="alert alert-danger"><button class="close" data-dismiss="alert" type="button"></button>' + $nombreErrorText + '<strong class="icon-remove close"></strong> </div>');
    }
    var $ciError;
    if ($ciErrorText != null) {
        $ciError = $('<div class="alert alert-danger"><button class="close" data-dismiss="alert" type="button"></button>' + $ciErrorText + '<strong class="icon-remove close"></strong> </div>');
    }
    $modalView.find('#booking_bookingbundle_propietario_nombre').parent().append($nombreError);
    $modalView.find('#booking_bookingbundle_propietario_ci').parent().append($ciError);
    $modalView.find('.close').click(function () {
        $(this).closest('.alert.alert-danger').remove();
    });
};

$(function () {
    //Adicionar Propietario
    var $btnAction = $('.btn.btn-default.action');
    $btnAction.click(function (event) {

        $propietarioTable.addPropietario();
    });
});