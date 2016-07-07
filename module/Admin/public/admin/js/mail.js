$(document).ready(function(){
    $('a#setImportant').click(function (e) {
        e.preventDefault();

        var $element = $(this);
        $element.tooltip('hide');
        jQuery.ajax({
            url: $(this).data('href'),
            type: 'POST',
            dataType: 'json',
            data: {
                action: 'toggleImportant',
                data: $(this).data('id')
            },
            success: function (data) {
                console.log(data.message, data.important);
                if (!data.error) {
                    switch (data.important) {
                        case true:
                            $element.removeClass('btn-white').addClass('btn-warning');
                            $element.data('original-title', 'Снять отметку важное');
                            $('#flagsContainer').prepend('<span id="' + important['id'] + '" class="label label-' +
                                important['colorClass'] + '">' + important['text'] + '</span>');

                            break;
                        case false:
                            $element.removeClass('btn-warning').addClass('btn-white');
                            $element.data('original-title', 'Отметить как важное');
                            $('#' + important['id']).remove();

                            break;
                    }
                }
            },
            error: function (data) {
                ajaxError(data);
            }
        });
    });
});