$(document).ready(function(){
    $('.clickSearch').on('click', function () {
        var search = $(this).data('search');
        
        $('#searchLastText').val(search);
        $('#searchLastForm').submit();
    });

    $('.thumbDescription').hide();

    $('.thumDiv').hover(function(){
        var id = $(this).data('id');
        $('#thumb-prim-' + id).hide();
        $('#thumb-desc-' + id).show();
    }, function(){
        var id = $(this).data('id');
        $('#thumb-desc-' + id).hide();
        $('#thumb-prim-' + id).show();
    });
});