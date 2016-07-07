$(document).ready(function () {
    var arrParams = getUrlParams();

    if (arrParams.formresult == 'success') {
        window.history.pushState(null, null, location.protocol + '//' + location.host + location.pathname);
        $('form.pure-form').before('<div class="message" style="display: none">Сообщение отправленно</div>');
        $('form.pure-form').slideUp('slow', function(){
            $(this).remove();
            $('.message').slideDown('slow', function(){
                setTimeout(function(){
                    $('.message').slideUp('slow', function(){
                        $(this).remove();
                    });
                }, 10000);
            });
        });
    } else if (arrParams.formresult == 'error' && arrParams.message != undefined) {
        window.history.pushState(null, null, location.protocol + '//' + location.host + location.pathname);
        $('form.pure-form').before('<div class="message error" style="display: none">' + arrParams.message + '</div>');
        $('.message').slideDown('slow');
    }
});