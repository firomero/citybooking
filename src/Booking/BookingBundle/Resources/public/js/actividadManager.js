'use strict';
var actividadManager = angular.module('actividadManager',['ui.bootstrap']);
actividadManager.controller('managerController',function($scope,$http){


    $(document).ready(function () {

        var oTable;
        oTable = $('#tabla').dataTable(
            {
                "oLanguage": $language,
                "aLengthMenu": [5, 10, 15],
                "sPaginationType": "full_numbers",
                "pageLength": 10,
                "bLengthChange": true,
                "processing": true,
                "bJQueryUI": true,
                "bServerSide": true,
                "sAjaxSource": Routing.generate('actividad_listar'),
                "aoColumns": [
                    {"bSortable": false},
                    {"bSortable": false},
                    {'sClass': 'actividad-text', "bSortable": false},
                    {'sClass': 'actividad-text', "bSortable": false},
                    {'sClass': 'actividad-text', "bSortable": false},
                    {'sClass': 'actividad-text', "bSortable": false}
                ],
                "drawCallback":$actividadDataTable.postDraw
            }
        );

    });

    //Modal-Functions
    $scope.modalAdd = function(){

        var urlform = Routing.generate('actividad_new_form');
        var html = '';
        $http.get(urlform).success(function(data,textStatus,xhr){
            html =  data.form;
            uiWindow.form('Adicionar Actividad','',html,[{event:'shown.bs.modal', callback:function(){
                $('.dating').datepicker();
            }}]);

        });

    }

});
