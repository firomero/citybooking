$.fn.editablefield = function(){

    $(this).parent().append('<input type="text" style="display: none"/>')
    $(this).click(function(){
        $(this).hide();
        $(this).siblings('input[type="text"]').show().val($(this).text()).focus();
    });

    var input = $(this).siblings('input[type="text"]');
    var focused = $(this);

    $(input).focusout(function(){
        $(this).hide();  $(this).siblings(focused).show().text($(this).val());
    });
};

/*selectable*/
(function($){
    $.fn.editablelist = function(options){
        // This is the easiest way to have default options.
        //data=[{id:1,value:'foo'}];
        var settings = $.extend({
            data:[],
            tableClass:'table table-hover',
            buttonClass:'btn btn-mini icon-plus'
        }, options );

        //hiding the element
        var $this= $(this);
        $this.hide();
        $this.empty();
        var $sClass = $this.attr('class');;
        var data = settings.data;
        var $parent = $this.parent();

        //creating the select for choices
        var $selectable = $('<select class="ui-selectable-component"></select>');
        $selectable.addClass($sClass);
        data.forEach(function(item){
            var $option = $('<option class="ui-selectable-child"></option>');
            $option.val(item.id);
            $option.html(item.value);
            $selectable.append($option);
        });

        //button for adding elements to the hidden select
        var $button = $('<button type="button"></button>');
        $button.addClass(settings.buttonClass);
        $button.click(function(){
            var $tableElement =$('<tr><td><span></span><i class="doDelete icon-minus"></i></td></tr>') ;
            $tableElement.attr('data-id',$selectable.val());
            $tableElement.find('span').append($selectable.find('option:selected').text());
            var $opt = $('<option selected="selected"></option>');
            $opt.val($selectable.val());
            $opt.html($selectable.find('option:selected').text());
            $this.append($opt);
            $table.append($tableElement);
            $tableElement.find('i').click(function(){
                var $parent = $(this).closest('tr');
                var id = $parent.data('id');
                $this.find('option[value="'+id+'"]').remove();
                $this.find('option[value="'+id+'"]').detach();
                $parent.remove();

            });

        });

        var $table = $('<table><tbody></tbody></table>');
        $table.removeAttr('class');
        $table.addClass(settings.tableClass);
        //Adding everything to the form
        $parent.append($selectable).append($button).append($table);
        return this;
    }
})(jQuery);