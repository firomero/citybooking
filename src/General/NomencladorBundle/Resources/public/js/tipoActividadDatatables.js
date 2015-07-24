/**
 * Created by Ruben on 30/05/2015.
 */
'use strict';

/*
 * show.bs.modal
 * shown.bs.modal
 * hide.bs.modal
 * hidden.bs.modal
 *
 * */
var $tipoActividadTable = { };


$tipoActividadTable.postDraw = function (){

    var $td = $('#act_tabla').find('tbody tr td:first-child');
    $td.each(function(){
        if (!$(this).hasClass('dataTables_empty')) {

            $(this).removeAttr('class');
            $(this).addClass('td-table');
            var html =  $(this).html();
            $(this).empty();
            var $input = $('<input type="checkbox" name="delete_all['+html+']" value="'+html+'" class="msf" id="'+html+'"> <label for="'+html+'"><span class="lbl"> </span></label>');
            $(this).append($input);
            var $column = $('<td></td>');
            //Botones
            var $btnGroup = $('<div class="btn-group"></div>');
            var $btnEditar = $('<button class="btn btn-mini info edit" ></button>');
            $btnEditar.append($('<i class="icon-edit bigger-125"></i>'));
            $btnEditar.attr('data-id',html);
            var $parent = $(this).closest('tr');
            var $text = $parent.find('.tipoactividad-text');
            $btnEditar.attr('data-name',$text.html());

            var $btnEliminar = $('<a class="btn btn-mini danger"></a>');
            $btnEliminar.attr('data-toggle','modal');
            $btnEliminar.attr('data-target','#confirm');
            $btnEliminar.attr('data-id',html);
            $btnEliminar.append($('<i class="icon-trash bigger-125"></i>'));

            $btnGroup.append($btnEditar).append($btnEliminar);
            $column.append($btnGroup);
            $(this).closest('tr').append($column);
            //Events

            //Editar  Actividad
            $('.btn.btn-mini.edit').click(function () {

                var $btnEdit = $(this);
                var $saveBtn = $('.btn.btn-primary.action');

                $saveBtn.off('click');
                $saveBtn.click(function () {
                    $tipoActividadTable.editActividad($btnEdit);
                });
                var $modalView = $('#myModalDialog');
                $modalView.modal();
                $modalView.find('#myModalLabelConfirmar').text('Editar Tipo de Actividad');
                $modalView.find('#actividadText').val($btnEdit.data('name'));
                $modalView.on('hide.bs.modal', function () {
                    $saveBtn.off('click');
                    $saveBtn.click(function (event) {
                        $tipoActividadTable.addActividad();
                    });
                    $modalView.find('#myModalLabelConfirmar').text('Adicionar Tipo de Actividad');
                });
                $modalView.modal('show');
            });

            //Eliminar Actividad
            $btnEliminar.click(function(){
                var $acept = $('.btn.btn-primary.delete');
                var id = $(this).attr('data-id');
                $acept.click(function(){
                    $tipoActividadTable.deleteActividad(id);
                });

            });
        }
    });
};

$tipoActividadTable.addActividad = function(){
    var name = $('#actividadText').val();
    var $modalView = $('#myModalDialog');
    if (name != '') {
        $('.se-pre-con').removeClass('hidden');
        $.post(
            Routing.generate('tipoactividad_ajax_add'),
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
                $tipoActividadTable.insertError();
            });
    }
    else {
        $tipoActividadTable.insertError();
    }
};

$tipoActividadTable.editActividad = function(object){
    var name = $('#actividadText').val();
    var id = $(object).data('id');
    var $modalView = $('#myModalDialog');
    if (name != '') {
        $('.se-pre-con').removeClass('hidden');
        $.post(
            Routing.generate('tipoactividad_ajax_edit'),
            {
                name: name,
                id:id
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
                $tipoActividadTable.insertError();
            });
    }
    else {
        $tipoActividadTable.insertError();
    }
};

$tipoActividadTable.deleteActividad = function (id) {
    $('.se-pre-con').removeClass('hidden');
    $.post(
        Routing.generate('tipoactividad_ajax_delete'),
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
            $tipoActividadTable.insertError();
        });
};

$tipoActividadTable.insertError=function()
{
    var $modalView = $('#myModalDialog');
    $modalView.find('.alert.alert-danger').remove();
    var $error = $('<div class="alert alert-danger"><button class="close" data-dismiss="alert" type="button"></button>Por favor, verifique sus datos.<strong class="icon-remove close"></strong> </div>');
    $modalView.find('.modal-body').append($error);
    $modalView.find('.close').click(function () {
        $(this).closest('.alert.alert-danger').remove();
    });
};

$(function(){
    //Adicionar Actividad
    var $btnAction = $('.btn.btn-primary.action');
    $btnAction.click(function(event){
        $tipoActividadTable.addActividad();
    });
});