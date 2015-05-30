/**
 * Created by manytostao on 26/05/15.
 */
'use strict';

/*
 * show.bs.modal
 * shown.bs.modal
 * hide.bs.modal
 * hidden.bs.modal
 *
 * */
var $tipohabTable = {};


$tipohabTable.postDraw = function () {

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
            var $text = $parent.find('.tipohab-text');
            $btnEditar.attr('data-name', $text.html());

            var $btnEliminar = $('<a class="btn btn-mini danger"></a>');
            $btnEliminar.attr('data-toggle', 'modal');
            $btnEliminar.attr('data-target', '#confirm');
            $btnEliminar.attr('data-id', html);
            $btnEliminar.append($('<i class="icon-trash bigger-125"></i>'));

            $btnGroup.append($btnEditar).append($btnEliminar);
            $column.append($btnGroup);
            $(this).closest('tr').append($column);
            //Events

            //Editar Tipo de Habitacion
            $('.btn.btn-mini.edit').click(function () {

                var $btnEdit = $(this);
                var $saveBtn = $('.btn.btn-default.action');

                $saveBtn.off('click');
                $saveBtn.click(function () {
                    $tipohabTable.editTipoHab($btnEdit);
                });
                var $modalView = $('#myModalDialog');
                $modalView.modal();
                $modalView.find('#myModalLabelConfirmar').text('Editar Tipo de Habitación');
                $modalView.find('#general_nomencladorbundle_tipohab_nombre').val($btnEdit.data('name'));
                $modalView.on('hide.bs.modal', function () {
                    $saveBtn.off('click');
                    $saveBtn.click(function (event) {

                        $tipohabTable.addTipoHab();
                    });
                    $modalView.find('#myModalLabelConfirmar').text('Adicionar Tipo de Habitación');
                });
                $modalView.modal('show');
            });

            //Eliminar Tipo de Habitacion
            $btnEliminar.click(function () {
                var $acept = $('.btn.btn-default.delete');
                var id = $(this).attr('data-id');
                $acept.click(function () {
                    $tipohabTable.deleteTipoHab(id);
                });

            });


        }


    });

};

$tipohabTable.addTipoHab = function () {
    var name = $('#general_nomencladorbundle_tipohab_nombre').val();
    var $modalView = $('#myModalDialog');
    if (name != '') {
        $('.se-pre-con').removeClass('hidden');
        $.post(
            Routing.generate('tipohab_ajax_add'),
            {
                name: name
            },
            function (data, text, response) {
                if (response.status == 200) {

                    $('.se-pre-con').addClass('hidden');
                    $modalView.modal('hide');
                    location.reload();


                }
            },
            "json"
        ).fail(function () {
                $tipohabTable.insertError();
            });
    }
    else {

        $tipohabTable.insertError();
    }
};

$tipohabTable.editTipoHab = function (object) {
    var name = $('#general_nomencladorbundle_tipohab_nombre').val();
    var id = $(object).data('id');
    var $modalView = $('#myModalDialog');
    if (name != '') {
        $('.se-pre-con').removeClass('hidden');
        $.post(
            Routing.generate('tipohab_ajax_edit'),
            {
                name: name,
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
        ).fail(function () {
                $tipohabTable.insertError();
            });
    }
    else {

        $tipohabTable.insertError();
    }
};

$tipohabTable.deleteTipoHab = function (id) {
    $('.se-pre-con').removeClass('hidden');
    $.post(
        Routing.generate('tipohab_ajax_delete'),
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
    ).fail(function () {
            $tipohabTable.insertError();
        });
};

$tipohabTable.insertError = function () {
    var $modalView = $('#myModalDialog');
    $modalView.find('.alert.alert-danger').remove();
    var $error = $('<div class="alert alert-danger"><button class="close" data-dismiss="alert" type="button"></button>Por favor, verifique sus datos.<strong class="icon-remove close"></strong> </div>');
    $modalView.find('.modal-body').append($error);
    $modalView.find('.close').click(function () {
        $(this).closest('.alert.alert-danger').remove();
    });
};

$(function () {
    //Adicionar Tipo de Habitacion
    var $btnAction = $('.btn.btn-default.action');
    $btnAction.click(function (event) {

        $tipohabTable.addTipoHab();
    });
});