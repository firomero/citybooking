/**
 * Created by aioria on 15/05/15.
 */
'use strict';

/*
 * show.bs.modal
 * shown.bs.modal
 * hide.bs.modal
 * hidden.bs.modal
 *
 * */
var $casaDataTable = { };


$casaDataTable.postDraw = function (){

    var $td = $('#tabla').find('tbody tr td:first-child');
    $td.each(function(){
        if (!$(this).hasClass('dataTables_empty')) {

            $(this).removeAttr('class');
            $(this).addClass('td-table');
            var html =  $(this).html();
            $(this).empty();
            var $input = $('<input type="checkbox" name="delete_all['+html+']" value="'+html+'" class="msf" id="'+html+'"> <label for="'+html+'"><span class="lbl"> </span></label>');
            $(this).append($input);
            var $column = $('<td class="td-actions"></td>');
            //Botones
            var $btnGroup = $('<div class="btn-group hidden-phone visible-desktop"></div>');
            var $btnEditar = $('<button class="btn btn-mini info edit" ></button>');
            $btnEditar.append($('<i class="icon-edit bigger-125"></i>'));
            $btnEditar.attr('data-id',html);
            var $parent = $(this).closest('tr');
            var $text = $parent.find('.casa-text');
            $btnEditar.attr('data-name',$text.html());

            var $btnRoom = $('<button class="btn btn-mini info room" ></button>');
            $btnRoom.append($('<i class="icon-list bigger-125"></i>'));
            $btnRoom.attr('data-id',html);

            var $btnEliminar = $('<button type="button" class="btn btn-mini danger"></button>');
            $btnEliminar.attr('data-toggle','modal');
            $btnEliminar.attr('data-target','#doDelete');
            $btnEliminar.attr('data-id',html);
            $btnEliminar.append($('<i class="icon-trash bigger-125"></i>'));

            $btnGroup.append($btnEditar).append($btnEliminar).append($btnRoom);
            $column.append($btnGroup);
            $(this).closest('tr').append($column);
            //Events

            //Adicionar Casa

            //Eliminar Casa
            $btnEliminar.click(function(){
                var $acept = $('#doDelete').find('.delete');
                var id = $(this).attr('data-id');
                $acept.click(function(){
                    $casaDataTable.deleteCasa(id);
                });

            });


        }





    });

    //Editar  Casa
    $('.btn.btn-mini.edit').click(function () {

        var $btnEdit = $(this);
        var $modalView = $('#myModalDialog');
        $modalView.modal();
        $modalView.find('#myModalLabelConfirmar').text('Editar Casa');



        $modalView.on('shown.bs.modal',function(){

            $.post(Routing.generate('ajax_form'),{
                    id:$btnEdit.data('id')
                },
                function(data, statusText, ajax){
                    if (ajax.status==200) {
                        $modalView.find('.modal-body').empty();
                        $modalView.find('.modal-body').append('<div class="se-pre-con hidden"></div>').append(data.form);
                        $modalView.find('.btn.btn-default.action.edit').click(function(){
                            $('.se-pre-con').removeClass('hidden');
                            $modalView.find('form').submit();

                        });

                    }
                },
                "json"
            );
        });

        $modalView.modal('show');


    });

    /**
     * Aqui se construye la lógica de adicionarles habitaciones a la casa.
     * */

    $('.btn.btn-mini.info.room').click(function(){
        var id = $(this).data('id');
        var $tabsModal = $('#tabsModal');

        $tabsModal.modal('show');
        $tabsModal.find('.modal-body').empty();
        var url = Routing.generate('ajax_tabs');
        $.get(url,{},function(data,response,status){
            $tabsModal.find('.modal-body').append(data.tabs);
            $tabsModal.attr('data-id',id);

            var $fnLoadTipo = function(item){
                var $option = $('<option></option>');
                $option.val(item.id);
                $option.html(item.tipo);
                $tabsModal.find('.tipoHab').append($option);
            }
            var urlTipo = Routing.generate('ajax_type');
            $.get(urlTipo,{},function(data,response,status){

                var list = data.tipo;
                list.forEach($fnLoadTipo);
                $tabsModal.find('.btn.btn-mini.pull-right').click(function(){
                    var $select = $('.tipoHab');
                    var value = $select.val();
                    var casaid = $tabsModal.data('id');
                    var adroom = Routing.generate('ajax_room_add');
                    $.get(adroom,{
                        'id':casaid,
                        'type':value
                    },function(status, data,response){
                        if (response.status==200) {

                            var $list = $('.habList');
                            var $listElement =$('<li><span></span><i class="doDelete icon-minus"></i></li>') ;
                            $listElement.find('span').append(status.habs.tipo);
                            $listElement.attr('data-id',status.habs.id);
                            $list.append($listElement);
                            $listElement.find('i').click(function(){

                                var $li = $(this).parent();
                                var deleteroom = Routing.generate('ajax_room_delete');
                                $.get(deleteroom,{
                                    'hab':$listElement.data('id'),
                                    'id':casaid
                                },function(status, data, response){
                                    $li.remove();
                                })

                            });

                            $('.typesHabs').show();

                        }
                        else{
                            $casaDataTable.insertError($tabsModal);
                        }

                    });

                })
            });

        }).success(function(){
            var roomByHouse = Routing.generate('ajax_habsByhouse');
            $.get(roomByHouse,{id:$tabsModal.data('id')},function(status, response, data){

                var habitaciones = status.habs;

                var fnFetch = function(item){
                    var $list = $('.habList');
                    var $listElement =$('<li><span></span><i class="doDelete icon-minus"></i></li>') ;
                    $listElement.find('span').append(item.tipo);
                    $listElement.attr('data-id',item.id);
                    $list.append($listElement);
                    $listElement.find('i').click(function(){

                        var $li = $(this).parent();
                        var deleteroom = Routing.generate('ajax_room_delete');
                        $.get(deleteroom,{
                            'hab':$listElement.data('id'),
                            'id':id
                        },function(status, data, response){
                            $li.remove();
                        })

                    });

                }

                habitaciones.forEach(fnFetch);
                $('.typesHabs').show();

            });
        });

    });


};

$casaDataTable.editCasa= function(object){
    var name = $('#casaText').val();
    var id = $(object).data('id');
    var $modalView = $('#myModalDialog');
    if (name != '') {
        $('.se-pre-con').removeClass('hidden');
        $.post(
            Routing.generate('casa_ajax_edit'),
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
                $casaDataTable.insertError();
            });
    }
    else {

        $casaDataTable.insertError();
    }
}

$casaDataTable.deleteCasa = function (id) {
    $('.se-pre-con').removeClass('hidden');
    $.post(
        Routing.generate('casa_delete'),
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
    ).fail(function () {
            var $modalView = $('#doDelete');
            $modalView.find('.alert.alert-danger').remove();
            var $error = $('<div class="alert alert-danger"><button class="close" data-dismiss="alert" type="button"></button>Por favor, verifique sus datos.<strong class="icon-remove close"></strong> </div>');
            $modalView.find('.modal-body').append($error);
            $modalView.find('.close').click(function () {
                $(this).closest('.alert.alert-danger').remove();
            });
        });
};

$casaDataTable.insertError=function(parent)
{
    var $modalView = undefined;
    if (parent==undefined) {
         $modalView = $('#myModalDialog');
    }
    else{
         $modalView = $(parent);
    }
    $modalView.find('.alert.alert-danger').remove();
    var $error = $('<div class="alert alert-danger"><button class="close" data-dismiss="alert" type="button"></button>Por favor, verifique sus datos.<strong class="icon-remove close"></strong> </div>');
    $modalView.find('.modal-body').append($error);
    $modalView.find('.close').click(function () {
        $(this).closest('.alert.alert-danger').remove();
    });
};

$(function(){
    //Adicionar Casa
    var $btnAction = $('.btn.btn-primary.action');
    $btnAction.click(function(event){
        $('.se-pre-con').removeClass('hidden');
            $('.ajaxForm').submit();
    });


});

/*ROOM MANAGER HANDLERS*/

