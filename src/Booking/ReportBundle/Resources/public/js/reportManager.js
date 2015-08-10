'use strict'
var $reportManager = angular.module('reportManager',[]);
$reportManager.controller('managerController',function($scope,$http){
   /*
   UI configuration jQuery
    */
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
    /*
    UI Configuration ReportManager     */

    var $panelBody = $('.panel-body');
    //$('.date').datepicker({ autoclose:true});
    $('.daterange').daterangepicker({
        locale : {
            applyLabel: 'Aplicar',
            cancelLabel: 'Cancelar',
            fromLabel: 'Desde',
            toLabel: 'Hasta',
            weekLabel: 'W',
            customRangeLabel: 'Rango',
            firstDay: 0
        },
        format:"DD/MM/YYYY"
    });
    //$('#dpMonths').datepicker({ autoclose:true, dateFormat:'mm/yyyy'});
    $('#dpMonths').datepicker();
    $('#dpMonths1').datepicker();
    $('#dpMonths2').datepicker();
    var cachedHuses = [];
    var urlCasa = Routing.generate('ajax_casa_listar');
    $scope.meses = ['Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre'];

    $http.get(urlCasa).
        success(
        function(data, status, headers, config)
        {

            $scope.houses = data.aaData;

            var cachedHuses = data.aaData.map(function(array){

                return array[1];
            });
            $('.casas').autocomplete(
                {
                    source:cachedHuses
                }
            );



        }
    ).
        error(
        function(data, status, headers, config){
            $panelBody.find('.alert').detach();
            var $msg = $('<div class="alert alert-danger"></div>');
            $msg.html(data);
            $panelBody.append($msg);
        });

    /*
    Controller functions
     */



    $scope.doSearch = function(state){

        $panelBody.find('.alert').detach();
        var urlseek = Routing.generate('report_options_dayseek',{date:$('.daterange').val(),state:state});
        $http.get(urlseek).success(
            function(data, status, headers, config)
            {
                 $scope.casas = data.casas
            }
        ).error(
            function(data, status, headers, config){

            var $msg = $('<div class="alert alert-danger"></div>');
            $msg.html(data);
            $panelBody.append($msg);
        });

    }


    $scope.monthly = function(){
        if ($scope.casa!="") {
            $panelBody.find('.alert').detach();
            var urlBook = Routing.generate('report_options_book_house',{date: $scope.mes, casa: $scope.casa});
            $http.get(urlBook).success(
                function(data, status, headers, config){
                $scope.booking = data.booking;
            }).error(
                function(data, status, headers, config){

                    var $msg = $('<div class="alert alert-danger"></div>');
                    $msg.html(data);
                    $panelBody.append($msg);
                });

        }

    }

    $scope.houseGenerate = function(id){
       var urlReport = Routing.generate('report_listReserv_view',{casa:id});
        document.location.href = urlReport;
    }

    $scope.bookGenerate = function(){
        var urlBook = Routing.generate('report_options_book_house_month',{date:$scope.monthbook});
        document.location.href = urlBook;
    }

    $scope.tourGenerate = function(){
        var urlTour = Routing.generate('report_options_tour_house_month',{date:$scope.tourbook});
        document.location.href = urlTour;
    }
});

