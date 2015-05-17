/**
 * Created by firomero on 15/05/15.
 */
'use strict';

var $agenciaTable = { };
$agenciaTable.postDraw = function (){

}
$(function(){
    $(document).ready(function() {

        var oTable;
        oTable = $('#tabla').dataTable(
            {
                "oLanguage": $language,
                "aLengthMenu": [5, 10, 15],
                "sPaginationType": "full_numbers",
                "pageLength": 10,
                "bLengthChange": true,
                "processing": true,
                bJQueryUI: true,
                "bServerSide": true,
                "sAjaxSource": Routing.generate('ajax_agencia_listar'),
                "aoColumns": [
                    {"bSortable": false}, null
                ]
            }
        );
    } );
});