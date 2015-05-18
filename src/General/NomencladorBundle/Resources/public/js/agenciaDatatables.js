/**
 * Created by firomero on 15/05/15.
 */
'use strict';
var $agenciaTable = { };


$agenciaTable.postDraw = function (){
	
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
	        var $btnEditar = $('<button class="btn btn-mini info edit" ></button>')
	        $btnEditar.append($('<i class="icon-edit bigger-125"></i>'));
	        var $btnEliminar = $('<a class="btn btn-mini danger"></a>');
	        $btnEliminar.attr('data-toggle','modal');
	        $btnEliminar.attr('data-target','#confirm');
	        $btnEliminar.append($('<i class="icon-trash bigger-125"></i>'));
	        $btnGroup.append($btnEditar).append($btnEliminar);
	        $column.append($btnGroup);
	        $(this).closest('tr').append($column);
	        //Events

	        
        }



    });

};