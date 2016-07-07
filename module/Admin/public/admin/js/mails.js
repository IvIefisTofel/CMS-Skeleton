$(document).ready(function(){
    $('a.flag').click(function (e) {
        e.preventDefault();

        var flag = $(this).data('flag');
        if (flag == 'none') {
            $('.table-mail tr').show();
        } else {
            if ($('.table-mail .' + flag).length > 0) {
                $('.table-mail tr').hide();
                $('.table-mail .' + flag).parents('tr').show();
            } else {
                $('.table-mail tr').show();
            }
        }

        if ($('.table-mail .i-checks:visible:visible:checked').length == $('.table-mail .i-checks:visible').length) {
            $('#toggleAll').iCheck('check');
        } else {
            $('#toggleAll').iCheck('uncheck');
        }
    });

    $('#toggleAll').on('ifClicked', function(){
        if ($('.table-mail .i-checks:visible').length != $('.table-mail .i-checks:visible:checked').length) {
            $('.table-mail .i-checks:visible').iCheck('check');
        } else {
            $('.table-mail .i-checks:visible').iCheck('uncheck');
        }
    });

    $('.table-mail .i-checks').on('ifClicked', function(){
        if ($('.table-mail .i-checks:visible:checked').length == $('.table-mail .i-checks:visible').length - 1 && !$(this).prop('checked')) {
            $('#toggleAll').iCheck('check');
        } else {
            $('#toggleAll').iCheck('uncheck');
        }
    });

    $('.ajaxAction').click(function (e) {
        e.preventDefault();

        var $checked = $('.table-mail .i-checks:checked');
        var arrIds = [];
        $checked.each(function(key, val){
            arrIds.push($(val).data('id'));
        });

        $(this).tooltip('hide');
        if ($checked.length > 0) {
            jQuery.ajax({
                url: $(this).data('href'),
                type: 'POST',
                dataType: 'json',
                data: {
                    action: $(this).data('action'),
                    data: arrIds
                },
                success: function (data) {
                    console.log(data.message);
                    if (!data.error) {
                        switch (data.type) {
                            case 'setRead':
                                $('#countNewMessages b.number').text($('#countNewMessages b.number').text() -
                                    $checked.parents('tr.unread').length);
                                $checked.parents('tr').removeClass('unread').addClass('read');
                                $checked.iCheck('uncheck');

                                break;
                            case 'delete':
                                $('#countNewMessages b.number').text($('#countNewMessages b.number').text() -
                                    $checked.parents('tr.unread').length);
                                $('.mail-box-header h2 .number').text($('.mail-box-header h2 .number').text() -
                                    $checked.length);
                                $checked.parents('tr').remove();

                                break;
                        }
                    }
                },
                error: function (data) {
                    ajaxError(data);
                }
            });
        }
    })
});