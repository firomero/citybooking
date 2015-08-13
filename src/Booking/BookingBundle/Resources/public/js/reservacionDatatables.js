'use strict';

/*
 * show.bs.modal
 * shown.bs.modal
 * hide.bs.modal
 * hidden.bs.modal
 *
 * */
var $reservacionTable = { };


$reservacionTable.postDraw = function (){

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
            var $btnGroup = $('<div class="btn-group hidden-phone visible-desktop"></div>');
            var $btnEditar = $('<button class="btn btn-mini info edit" ></button>');
            $btnEditar.append($('<i class="icon-edit bigger-125"></i>'));
            $btnEditar.attr('data-id',html);

            var $btnActivity = $('<button class="btn btn-mini info activity" ></button>');
            $btnActivity.append($('<i class="icon-list bigger-125"></i>'));
            $btnActivity.attr('data-id',html);

            var $btnInvoice = $('<button class="btn btn-mini info invoice" ></button>');
            $btnInvoice.append($('<i class="icon-money bigger-125"></i>'));
            $btnInvoice.attr('data-id',html);

            var $parent = $(this).closest('tr');
            var $text = $parent.find('.reservacion-text');
            $btnEditar.attr('data-name',$text.html());

            var $btnEliminar = $('<button class="btn btn-mini danger"></button>');
            $btnEliminar.attr('data-toggle','modal');
            $btnEliminar.attr('data-target','#doDelete');
            $btnEliminar.attr('data-id',html);
            $btnEliminar.append($('<i class="icon-trash bigger-125"></i>'));

            $btnGroup.append($btnEditar).append($btnEliminar).append($btnActivity).append($btnInvoice);
            $column.append($btnGroup);
            $(this).closest('tr').append($column);

        }
    });


    //Events

    //Editar Reservacion
    $('.btn.btn-mini.edit').click(function () {

        var $btnEdit = $(this);


        var $modalView = $('#myModalDialog');
        $modalView.modal();
        $modalView.find('#myModalLabelConfirmar').text('Editar Reservación');
        $modalView.on('shown.bs.modal',function(){
            $.post(
                Routing.generate('reservacion_editar'),
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

                        var $options = $modalView.find('select.tipoHab option');
                        var $selected = $modalView.find('select.tipoHab option:selected');
                        var data = [];
                        var def = [];
                        $options.each(function(){
                            data.push({id:$(this).val(),value:$(this).html()});
                        });

                        $selected.each(function(){
                            def.push({id:$(this).val(),value:$(this).html()});
                        });



                        var aSelectable = $('select.tipoHab').editablelist({
                            data:data,
                            selected:def
                        });

                    }
                },
                "json"
            ).fail(function () {
                    $reservacionTable.insertError();
                });
        });
        $modalView.modal('show');
    });

    //Asociar Actividad
    $('.btn.btn-mini.info.activity').click(function(){
        var $btnAssoc = $(this);
        var urlActivityForm = Routing.generate('reservacion_activity_form',{id:$btnAssoc.data('id')});
        $.get(urlActivityForm,{},function(data,status,xhr){
            if (xhr.status==200) {
                var html = data.form;
                uiWindow.form('Adicionar Actividad','',html,[{event: 'shown.bs.modal',callback:function(){
                    $('.dating').datepicker({ autoclose:true, dateFormat:'Y-m-d'});
                    $('.timing').timepicker({
                        minuteStep: 1,
                        showMeridian: false,
                        defaultTime: 'current'
                    });

                    //Activity Type
                    var urlActivityList = Routing.generate('tipoactividad_ajax_listar');
                    $.get(urlActivityList,{},function(data,status,xhr){
                        var types = data.aaData
                        types.forEach(function(item){
                            var $opt = $('<option></option>');
                            $('#activity_type').append($opt.val(item[0]).html(item[1]));
                        });
                    },"json");

                    //Can't use angular in jQuery modal,so let's use jQuery
                    $('[data-ng-model="closest"]').change(function(){
                        var date = $('[data-ng-model="date"]').val();
                        var type = $(this).val();
                        if (type!="" && date!="") {
                            var closestUrl = Routing.generate('actividad_closest',{date:date,type:type});
                            $.get(closestUrl,{},function(data,status,xhr){
                                if (xhr.status==200) {
                                    var activities = data.activities;
                                    var $ul = $('#jModal').find('.nav');
                                    $ul.empty();
                                    activities.forEach(function(item){
                                        $ul.append('<li data-id="'+item[0]+'">'+item[1]+':'+item[2]+':'+item[3]+'</li>');
                                    });
                                }
                            },"json");
                        }
                    });

                    //Now bind the simulated submit
                    $('#doAction').click(function(){
                        var $errorShow = $('.col-md-12');
                        $errorShow.empty();

                        var  fecha = $('#activity_date').val();
                        var  hora = $('#activity_hour').val();
                        var  lugar = $('#activity_place').val();
                        var  precio = $('#activity_price').val();
                        var  coordinacion = $('#activity_coordinate').val();
                        var  pax = $('#activity_pax').val();
                        var  tipo = $('#activity_type').val();
                        var  booking = $('#booking').val();
                        var urlAssoc = Routing.generate('reservacion_associate',{
                            fecha:fecha,
                            hora:hora,
                            lugar:lugar,
                            precio:precio,
                            coordinacion:coordinacion,
                            pax:pax,
                            tipo:tipo,
                            booking:booking


                        });
                        $.get(urlAssoc,{},function(data,status,xhr){
                            if (xhr.status==200) {
                                $('.ajaxForm input[type="text"]').val('');
                                $errorShow.append($('<div class="alert alert-info"></div>'));
                                $errorShow.find('.alert').append("Actividad adicionada correctamente").fadeOut(600);
                            }
                        },"json").fail(function(data,status,xhr){
                            $errorShow.empty();
                            $errorShow.append($('<div class="alert alert-danger"></div>'));
                            var text="";
                            if (data.status==400) {
                                text="Hay error en los datos de entrada, por favor revise sus datos";
                            }
                            else{
                                text="Ha ocurrido un error de servidor";
                            }
                            $errorShow.find('.alert').append(text);


                        });
                    });

                }}]);
            }
        },"json");
    });

    //Invoice for a custom booking and tour
    $('.invoice').click(function(){
        document.location.href=Routing.generate('report_custom_invoice',{id:$(this).data('id')});
    });


    //Eliminar Reservacion
    $('.btn.btn-mini.danger').click(function(){
        var $acept = $('#doDelete').find('.delete');
        var id = $(this).attr('data-id');
        $acept.click(function(){
            $reservacionTable.deleteReservacion(id);
        }).fail(function(data,status,xhr){

        });

    });

    $('.dating').datepicker();

};

$reservacionTable.addReservacion = function(){
    var name = $('#reservacionText').val();
    var $modalView = $('#myModalDialog');
    if (name != '') {
        $('.se-pre-con').removeClass('hidden');
        $.post(
            Routing.generate('reservacion_crear'),
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
                $reservacionTable.insertError();
            });
    }
    else {
        $reservacionTable.insertError();
    }
};

$reservacionTable.editReservacion = function(object){

    var id = $(object).data('id');
    var $modalView = $('#myModalDialog');
    if (id != '') {
        $('.se-pre-con').removeClass('hidden');
        $.post(
            Routing.generate('reservacion_editar'),
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
                $reservacionTable.insertError();
            });
    }
    else {
        $reservacionTable.insertError();
    }
};

$reservacionTable.deleteReservacion = function (id) {
    $('.se-pre-con').removeClass('hidden');
    $.post(
        Routing.generate('reservacion_cancelar'),
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
            $reservacionTable.insertErrorConfirm(data);
        });
};

$reservacionTable.insertError=function()
{
    var $modalView = $('#myModalDialog');
    $modalView.find('.alert.alert-danger').remove();
    var $error = $('<div class="alert alert-danger"><button class="close" data-dismiss="alert" type="button"></button>Por favor, verifique sus datos.<strong class="icon-remove close"></strong> </div>');
    $modalView.find('.modal-body').append($error);
    $modalView.find('.close').click(function () {
        $(this).closest('.alert.alert-danger').remove();
    });
};

$reservacionTable.insertErrorConfirm=function(data)
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
    $.widget( "ui.autocomplete", $.ui.autocomplete, {
        options: {
            messages: {
                noResults: "Sin resultados.",
                results: function( amount ) {
                    return "";
                }
            }
        },

        __response: function( content ) {
            var message;
            this._superApply( arguments );
            if ( this.options.disabled || this.cancelSearch ) {
                return;
            }
            if ( content && content.length ) {
                message = this.options.messages.results( content.length );
            } else {
                message = this.options.messages.noResults;
            }
            this.liveRegion.text( message );
        }
    });


    //Modal de Reservación
    $('.btn.btn-mini.pull-right.add').click(function(){
        var $editModal = $('#myEditDialog');
        $editModal.modal();
//todo revisar el multiple data and display
        /*Aqui se cargan los autocomplete necesarios, agencia, cliente*/
        $editModal.on('shown.bs.modal',function(){

            /*Agencia*/
            var agenciaUrl = Routing.generate('ajax_agencia_listar');
            $.get(
                agenciaUrl,
                {

                },
                function(status, data,response)
                {
                   console.log(status.aaData);
                    var serverData = status.aaData;
                    var mapped = serverData.map(function(array){

                        return array[1];
                    });
                    console.log(mapped);
                    if (response.status==200) {


                        var $agenciaComplete = $('.agencia-name').autocomplete({
                            source:mapped

                        });

                        $agenciaComplete.autocomplete( "option", "appendTo", "#myEditDialog" );

                    }
                },
                "json"
            )

            /*Cliente*/
            var clienteUrl = Routing.generate('ajax_cliente_listar');

            $.get(
                clienteUrl,
                {

                },
                function(status, data,response)
                {
                    var serverData = status.aaData;
                    var mapped = serverData.map(function(array){

                        return {
                            name: array[1],
                            ref: array[2]
                        };
                    });



                    if (response.status==200) {

                        var $clienteComplete = $('.cliente-name').autocomplete({
                            source:mapped,
                            select: function(event,ui){
                                $( ".referencia-name" ).val( ui.item.ref );
                                $( ".cliente-name" ).val( ui.item.name );
                                return  false;
                            }

                        }).data( "ui-autocomplete" )._renderItem = function( ul, item ) {
                            return $( "<li>" )
                                .append( "<a>" + item.name + "</a>" )
                                .appendTo( ul );
                        };

                        //$clienteComplete.autocomplete( "option", "appendTo", "#myEditDialog" );



                    }
                },
                "json"
            )
            ;


            /*Casas Disponibles*/
            var urlCasa = Routing.generate('reservacion_casas_disponibles');


            var $casaComplete = $('.casa-name').autocomplete({
                source:function(request, response){


                    var checkin = $('.checkin').val();
                    var checkout = $('.checkout').val();
                    var pax = $('.pax').val();
                    var tipoHab = $('.tipoHab').val();
                    $.ajax({
                        url: urlCasa,
                        dataType: "json",
                        data: {
                            checkin: checkin,
                            checkout: checkout,
                            pax: pax,
                            habitaciones: tipoHab
                        },
                        success: function( data, text, xhr ) {

                            response( $.map( data.casasDisponibles, function( item ) {
                                return {
                                    label: item[1],
                                    value: item[1]
                                }
                            }));
                        },
                        error: function(data, text, xhr ){

                            if (data.status==400) {
                                alert(data.responseText);
                            }
                        }

                    })
                },
                minLength: 2
            });

            $casaComplete.autocomplete( "option", "appendTo", "#myEditDialog" );

        });

        $editModal.modal('show');

    });
    //Adicionar Reservacion
    var $btnAction = $('.btn.btn-default.action');
    $btnAction.click(function(event){

        $reservacionTable.addReservacion();
    });


});
