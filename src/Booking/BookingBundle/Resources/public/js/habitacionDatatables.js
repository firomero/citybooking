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
var $habitacionTable = { };


$habitacionTable.postDraw = function (){

    var $td = $('#tabla').find('tbody tr td:first-child');
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
            var $text = $parent.find('.habitacion-text');
            $btnEditar.attr('data-name',$text.html());

            var $btnEliminar = $('<button class="btn btn-mini danger"></button>');
            $btnEliminar.attr('data-toggle','modal');
            $btnEliminar.attr('data-target','#doDelete');
            $btnEliminar.attr('data-id',html);
            $btnEliminar.append($('<i class="icon-trash bigger-125"></i>'));

            $btnGroup.append($btnEditar).append($btnEliminar);
            $column.append($btnGroup);
            $(this).closest('tr').append($column);
            //Events

            //Editar  Habitacion
            $('.btn.btn-mini.edit').click(function () {

                var $btnEdit = $(this);


                var $modalView = $('#myModalDialog');
                $modalView.modal();
                $modalView.find('#myModalLabelConfirmar').text('Editar Habitación');
                $modalView.on('shown.bs.modal',function(){
                    $.post(
                        Routing.generate('habitacion_edit'),
                        {

                            id:$btnEdit.data('id')
                        },
                        function (data, text, response) {
                            if (response.status == 200) {

                                $('.se-pre-con').addClass('hidden');
                                $modalView.find('.modal-body').empty();
                                $modalView.find('.modal-body').append('<div class="se-pre-con hidden"></div>').append(data.form);
                                $modalView.find('.btn.btn-default.action.edit').click(function(){
                                    $('.se-pre-con').removeClass('hidden');
                                    $modalView.find('form').submit();

                                });

                            }
                        },
                        "json"
                    ).fail(function () {
                            $habitacionTable.insertError();
                        });
                });
                $modalView.modal('show');
            });

            //Eliminar Habitacion
            $btnEliminar.click(function(){
                var $acept = $('#doDelete .delete');
                var id = $(this).attr('data-id');
                $acept.click(function(){
                    $habitacionTable.deleteHabitacion(id);
                });

            });
        }
    });
};

$habitacionTable.addHabitacion = function(){
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
                $habitacionTable.insertError();
            });
    }
    else {
        $habitacionTable.insertError();
    }
};

$habitacionTable.editHabitacion = function(object){

    var id = $(object).data('id');
    var $modalView = $('#myModalDialog');
    if (id != '') {
        $('.se-pre-con').removeClass('hidden');
        $.post(
            Routing.generate('habitacion_edit'),
            {

                id:id
            },
            function (data, text, response) {
                if (response.status == 200) {

                    $('.se-pre-con').addClass('hidden');
                    $modalView.find('.modal-body').empty();
                    $modalView.find('.modal-body').append('<div class="se-pre-con hidden"></div>').append(data.form);
                    $modalView.find('.btn.btn-default.action.edit').click(function(){
                        $('.se-pre-con').removeClass('hidden');
                        $modalView.find('form').submit();

                    });

                }
            },
            "json"
        ).fail(function () {
                $habitacionTable.insertError();
            });
    }
    else {
        $habitacionTable.insertError();
    }
};

$habitacionTable.deleteHabitacion = function (id) {
    $('.se-pre-con').removeClass('hidden');
    $.post(
        Routing.generate('habitacion_delete'),
        {
            id: id
        },
        function (data, text, response) {
            if (response.status == 204 || response.status == 200) {

                $('.se-pre-con').addClass('hidden');
                $('#doDelete').modal('hide');
                location.reload();
            }
        },
        "json"
    ).fail(function (data, text, response) {
            $habitacionTable.insertErrorConfirm(data);
        });
};

$habitacionTable.insertError=function()
{
    var $modalView = $('#myModalDialog');
    $modalView.find('.alert.alert-danger').remove();
    var $error = $('<div class="alert alert-danger"><button class="close" data-dismiss="alert" type="button"></button>Por favor, verifique sus datos.<strong class="icon-remove close"></strong> </div>');
    $modalView.find('.modal-body').append($error);
    $modalView.find('.close').click(function () {
        $(this).closest('.alert.alert-danger').remove();
    });
};

$habitacionTable.insertErrorConfirm=function(data)
{
    var $modalView = $('#doDelete');
    $modalView.find('.alert.alert-danger').remove();
    var $error = $('<div class="alert alert-danger"><button class="close" data-dismiss="alert" type="button"></button>Ha ocurrido un error.<strong class="icon-remove close"></strong> </div>');
    $modalView.find('.modal-body').append($error);
    $modalView.find('.close').click(function () {
        $(this).closest('.alert.alert-danger').remove();
    });
};

$(function(){
    //Adicionar Habitacion
    var $btnAction = $('.btn.btn-primary.action');
    $btnAction.click(function(event){
        $habitacionTable.addHabitacion();
    });
});
