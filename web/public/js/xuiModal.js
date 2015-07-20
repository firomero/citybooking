/**
 * Created by firomero on 6/10/15.
 */
'use strict';
/*
*
* type: 'errror'|'success'|'info'
* */

var typer = {
    error:'icon-warning-sign',
    success:'ico-ok',
    info:'icon-alert-custom'
};

var uiWindow = {

    /*
    * El alert es la función para mostrar mensajes informativos y de error
    * uiWindow.alert('info','Información','Lorem Ipsum')
    *
    * */
   alert:function(type,caption,text){
       var $alert = $('<div class="modal fade" id="jModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true"><div class="modal-dialog"><div class="modal-content"><div class="modal-header"><button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button></i><h3 class="modal-title"><i class="courier"></i><span>Modal title</span></h3></div><div class="modal-body"></div><div class="modal-footer"><button type="button" class="btn btn-default" data-dismiss="modal">Aceptar</button></div></div></div></div>');
       if (type!=='undefined') {
           $alert.find('.courier').removeAttr('class').addClass('courier').addClass(typer[type]);
       }
       $alert.find('.modal-title span').empty().text(caption);
       $alert.find('.modal-body').empty().append(text);
       $alert.modal();
       $alert.on('hidden.bs.modal',function(){
           $(this).remove();
       });
       $alert.modal('show');

   },
   confirm:function(caption,text,callback){
       var doAction = false;
       var $confirm = $('<div class="modal fade" id="jModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true"><div class="modal-dialog"><div class="modal-content"><div class="modal-header"><button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button><h3 class="modal-title">Modal title</h3></div><div class="modal-body"></div><div class="modal-footer"><button type="button" class="btn btn-primary" data-dismiss="modal">Aceptar</button><button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button></div></div></div></div>');
       $confirm.find('.modal-title').empty().text(caption);
       $confirm.find('.modal-body').empty().append(text);
       $confirm.find('.btn.btn-primary').click(function(){
           doAction = true;
       });
       $confirm.modal();
       $confirm.on('hidden.bs.modal',function(){
           if (doAction) {
               callback();
           }
           $(this).remove();
       });
       $confirm.modal('show');

   },
   form:function(caption,callback,form,events){
       var $uiform = $('<div class="modal fade" id="jModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true"><div class="modal-dialog"><div class="modal-content"><div class="modal-header"><button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button><h3 class="modal-title">Modal title</h3></div><div class="modal-body"></div><div class="modal-footer"></div></div></div></div>');
       $uiform.modal();
       //var $div = $('<div class="btn-group"><button type="button" class="btn btn-primary" data-dismiss="modal">Aceptar</button><button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button></div>');
       $uiform.find('.modal-title').empty().text(caption);
       $uiform.find('.modal-body').empty().append(form);
       //$(form).append($div);
       if (typeof(callback)==='function'){
           $(form).on('submit',function(){
              return  callback();
           });
       }
       if (events!='') {
           var fn = function(item){
               $uiform.on(item.event,item.callback);

           }

           events.forEach(fn);
       }

       $uiform.find('.btn.btn-primary').click(function(){
           $uiform.find('form').submit();
       });

       $uiform.on('hidden.bs.modal',function(){
           $(this).remove();
       });
       $uiform.modal('show');



   },
   custom:function(caption,text,html){
       var $uiform = $('<div class="modal fade" id="jModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true"><div class="modal-dialog"><div class="modal-content"><div class="modal-header"><button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button><h3 class="modal-title">Modal title</h3></div><div class="modal-body"></div><div class="modal-footer"></div></div></div></div>');
       var $div = $('<div class="btn-group"><button type="button" class="btn btn-primary" data-dismiss="modal">Aceptar</button><button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button></div>');
       $uiform.find('.modal-title').empty().text(caption);
       $uiform.find('.modal-body').empty().append(text);

       $uiform.on('hidden.bs.modal',function(){
           $(this).remove();
       });
       $uiform.modal('show');
   }
};

