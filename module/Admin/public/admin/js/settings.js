$(document).ready(function(){
    $('.switch-link').click(function (e) {
        e.preventDefault();

        var $icon = $(this).find('i');
        if ($icon.hasClass('fa-exchange')) {
            $icon.removeClass('fa-exchange').addClass('fa-pencil');
            $(this).attr('data-original-title', 'Режим редактирования');
        } else if ($icon.hasClass('fa-pencil')) {
            $icon.removeClass('fa-pencil').addClass('fa-exchange');
            $(this).attr('data-original-title', 'Режим сортировки');
        }

        $(this).tooltip('show');

        var $sortable = $(this).parents('div.ibox').find('.form-group');
        var $toHide = $sortable.children(':visible'),
            $toShow = $sortable.children(':hidden');
        $toHide.fadeOut('slow', function(){
            $toShow.fadeIn('slow');
        });
    });

    $('.sortable').sortable({
        handle: 'div.panel'
    }).on('sortstop', function(event, ui){
        var arrWeight = $(this).sortable('toArray');

        jQuery.ajax({
            url: $(this).attr('action'),
            type: 'POST',
            dataType: 'json',
            data: {
                action: 'changeWeight',
                data: arrWeight
            },
            success: function (data) {
                console.log(data.message);
            },
            error: function (data) {
                ajaxError(data);
            }
        });
    });
});