$(function(){
    //--------------
    // ハンバーガーメニュー
    //--------------
    $('.toggle_btn').on('click' , function() {
        if ($('#header').hasClass('open')) {
            $('#header').removeClass('open') ;
        } else {
            $('#header').addClass('open');
        }
    });

    $('#mask').on('click',function() {
        $('#header').removeClass('open');
    });

    $('#navi a').on('click',function(){
        $('#header').removeClass('open');
    });
});