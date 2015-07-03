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
}
