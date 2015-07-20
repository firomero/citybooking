'use strict';
/*
 * show.bs.modal
 * shown.bs.modal
 * hide.bs.modal
 * hidden.bs.modal
 *
 * */

var $actividadDataTable = { };
$actividadDataTable.postDraw=function(){
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
            /*
             Botones
             */
            var $btnGroup = $('<div class="btn-group hidden-phone visible-desktop"></div>');
            var $btnEditar = $('<button class="btn btn-mini info edit" ></button>');
            $btnEditar.append($('<i class="icon-edit bigger-125"></i>'));
            $btnEditar.attr('data-id',html);
            var $parent = $(this).closest('tr');
            var $text = $parent.find('.casa-text');
            $btnEditar.attr('data-name',$text.html());

            var $btnEliminar = $('<button type="button" class="btn btn-mini danger"></button>');
            $btnEliminar.attr('data-id',html);
            $btnEliminar.append($('<i class="icon-trash bigger-125"></i>'));

            $btnGroup.append($btnEditar).append($btnEliminar);
            $column.append($btnGroup);
            $(this).closest('tr').append($column);
            /**
             * Events
             */

            /*
             Eliminar Casa
             */
            $btnEliminar.click(function(){
                 var id = $(this).attr('data-id');

                 uiWindow.confirm('Eliminar Actividad','¿Desea eliminar la actividad?',function(){
                
                  $actividadDataTable.deleteActividad(id);

               });
                

            });

            /*
            Editar Actividad
             */
            $btnEditar.click(function(){
                var ida = $(this).data('id');
                var urlEditForm = Routing.generate('actividad_edit_form')
                $.post(
                    urlEditForm,
                    {
                        id: ida
                    },
                    function(data,response,xhr){
                        if (xhr.status==200) {
                            uiWindow.form('Editar Actividad','',data.form,'');
                            $(document).delegate('.modal.fade','shown.bs.modal',function(){
                                $('.dating').datepicker({dateFormat:'Y-m-d'});
                                $('.dating').datepicker('option', 'dateFormat', 'Y-m-d');
                            });
                        }
                    }
                );
            });



        }

    });
}
$actividadDataTable.deleteActividad=function(id){
    $('.se-pre-con').removeClass('hidden');
    $.post(
        Routing.generate('actividad_delete'),
        {
            id: id
        },
        function (data, text, response) {
            if (response.status == 204 || response.status == 200) {

                $('.se-pre-con').addClass('hidden');
                $('#doDelete').modal('hide');
                location.reload();
            }
            else{

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

}


$actividadDataTable.insertError=function(parent,error)
{
    var $modalView = undefined;
    if (parent==undefined) {
        $modalView = $('#myModalDialog');
    }
    else{
        $modalView = $(parent);
    }

    $modalView.find('.alert.alert-danger').remove();
    var $error = $('<div class="alert alert-danger"><button class="close" data-dismiss="alert" type="button"></button><span class="caption">Por favor, verifique sus datos.</span></span><strong class="icon-remove close"></strong> </div>');
    if (error!=undefined) {
        $error.find('span.caption').empty().append(error);
    }
    $modalView.find('.modal-body').append($error);
    $modalView.find('.close').click(function () {
        $(this).closest('.alert.alert-danger').remove();
    });
};
