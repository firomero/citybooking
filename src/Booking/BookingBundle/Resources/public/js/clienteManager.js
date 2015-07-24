'use strict';
var clienteManager = angular.module('clienteManager',['ui.bootstrap']);
clienteManager.controller('managerController',function($scope,$http){


       //Modal-Functions
    $scope.modalAdd = function(){

        var urlform = Routing.generate('cliente_edit_form');
        var html = '';
        $http.get(urlform).success(function(data,textStatus,xhr){
            html =  data.form;
            uiWindow.form('Adicionar Actividad','',html,'');

        });

    }

});
