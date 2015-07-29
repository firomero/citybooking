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
    $('.date').datepicker({ autoclose:true});
    //$('#dpMonths').datepicker({ autoclose:true, dateFormat:'mm/yyyy'});
    $('#dpMonths').datepicker();
    var cachedHuses = [];
    var urlCasa = Routing.generate('ajax_casa_listar');

    $http.get(urlCasa).
        success(
        function(data, status, headers, config)
        {

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
        var urlseek = Routing.generate('report_options_dayseek',{date:$scope.daterange,state:state});
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
});

