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
                $('.dating').datepicker({ autoclose:true});
                $('.timing').timepicker({
                    minuteStep: 1,
                    showMeridian: false,
                    defaultTime: 'current'
                });
                //Can't use angular in jQuery modal,so let's use jQuery
                $('[data-ng-model="closest"]').change(function(){
                    console.log('like angular');
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
                                     alert(item);
                                    $ul.append('<li data-id="'+item[0]+'">'+item[1]+':'+item[2]+':'+item[3]+':'+item[4]+'CUC'+'</li>');
                                });
                            }
                        },"json");
                    }
                });


            }}]);

        });

    }

});


actividadManager.controller('formController',function($scope,$http){
    $scope.loadClosest=function(){
        var closestUrl = Routing.generate('actividad_closest',{date:$scope.date,type:$scope.type});
        $http.get(closestUrl).success(
            function(data,textStatus,xhr){
                $scope.activities = data.activities;

            }
        ).error(function(data,textStatus,xhr){
                console.log(data);
            })
    }
});
