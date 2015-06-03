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
var $casaTable = { };


$casaTable.postDraw = function (){

    var $td = $('#tabla').find('tbody tr td:first-child');
    $td.each(function(){
        if (!$(this).hasClass('dataTables_empty')) {

            $(this).removeAttr('class');
            $(this).addClass('td-table');
            var html =  $(this).html();
            $(this).empty();
            var $input = $('<input type="checkbox" name="delete_all['+html+']" value="'+html+'" class="msf" id="'+html+'"> <label for="'+html+'"><span class="lbl"> </span></label>');
            $(this).append($input).append(html);
            var $column = $('<td></td>');
            //Botones
            var $btnGroup = $('<div class="btn-group"></div>');
            var $btnEditar = $('<button class="btn btn-mini info edit" ></button>')
            $btnEditar.append($('<i class="icon-edit bigger-125"></i>'));
            $btnEditar.attr('data-id',html);
            var $parent = $(this).closest('tr');
            var $text = $parent.find('.casa-text');
            $btnEditar.attr('data-name',$text.html());

            var $btnEliminar = $('<a class="btn btn-mini danger"></a>');
            $btnEliminar.attr('data-toggle','modal');
            $btnEliminar.attr('data-target','#doDelete');
            $btnEliminar.attr('data-id',html);
            $btnEliminar.append($('<i class="icon-trash bigger-125"></i>'));

            $btnGroup.append($btnEditar).append($btnEliminar);
            $column.append($btnGroup);
            $(this).closest('tr').append($column);
            //Events

            //Adicionar Casa

            //Eliminar Casa
            $btnEliminar.click(function(){
                var $acept = $('.btn.btn-default.delete');
                var id = $(this).attr('data-id');
                $acept.click(function(){
                    $casaTable.deleteCasa(id);
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

};

$casaTable.addCasa= function(){

}

$casaTable.editCasa= function(object){
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
                $casaTable.insertError();
            });
    }
    else {

        $casaTable.insertError();
    }
}

$casaTable.deleteCasa = function (id) {
    $('.se-pre-con').removeClass('hidden');
    $.post(
        Routing.generate('casa_ajax_delete'),
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
            $casaTable.insertError();
        });
}

$casaTable.insertError=function()
{
    var $modalView = $('#myModalDialog');
    $modalView.find('.alert.alert-danger').remove();
    var $error = $('<div class="alert alert-danger"><button class="close" data-dismiss="alert" type="button"></button>Por favor, verifique sus datos.<strong class="icon-remove close"></strong> </div>');
    $modalView.find('.modal-body').append($error);
    $modalView.find('.close').click(function () {
        $(this).closest('.alert.alert-danger').remove();
    });
}

$(function(){
    //Adicionar Casa
    var $btnAction = $('.btn.btn-default.action');
    $btnAction.click(function(event){
        $('.se-pre-con').removeClass('hidden');
            $('.ajaxForm').submit();
    });

    //$('body').on('submit', '#myModalDialog form',function(event){
    //    event.preventDefault();
    //
    //    $.ajax({
    //        type: $(this).attr('method'),
    //        url: $(this).attr('action'),
    //        method: "post",
    //        data: $(this).serialize(),
    //        dataType: "json"
    //    }).done(function(data){
    //        console.log(data)
    //        $('.se-pre-con').addClass('hidden');
    //        $('#myModalDialog').modal('hide');
    //        window.location.reload();
    //    }).fail(function(data){
    //        $casaTable.insertError();
    //    })
    //});
});

