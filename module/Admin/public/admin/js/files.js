GetNoun = function(number, one, two, five) {
    number = Math.abs(number);
    number %= 100;
    if (number >= 5 && number <= 20) {
        return five;
    }
    number %= 10;
    if (number == 1) {
        return one;
    }
    if (number >= 2 && number <= 4) {
        return two;
    }
    return five;
};

$(document).ready(function(){
    $('#litEditFile').on('shown.bs.modal', function (event) {
        var modal = $(this);
        var button = $(event.relatedTarget);
        var alias = button.data('alias');

        modal.find('#litFileTitle').text('Файл "' + alias + '"');
        modal.find('#litFileAlias').val(alias);
        modal.find('#litFileNewAlias').val(alias);

        $('#litFileNewAlias').focus();
    });

    $('#litEditFileForm').submit(function (e) {
        e.preventDefault();
        var alias = $(this).find('#litFileAlias').val();

        $('#litEditFile').modal('hide');
        jQuery.ajax({
            url: $(this).attr('action'),
            type: 'POST',
            dataType: 'json',
            data: {
                action: 'editFile',
                data: $(this).serializeArray()
            },
            success: function (data) {
                if (data.error) {
                    console.log(data.message);
                } else {
                    $('a[data-alias="' + alias + '"]').attr('data-alias', data.newAlias)
                        .data('alias', data.newAlias)
                        .parents('.file-box').find('.litFileLink').attr('href', '/' + data.newAlias)
                        .find('.file-name').text(data.newAlias);
                    $('a[data-filealias="' + alias + '"]').attr('data-filealias', data.newAlias).data('filealias', data.newAlias);

                    swal("Выполнено", data.message, "success");
                }
            },
            error: function (data) {
                ajaxError(data);
            }
        });
        $(this).find('input').val('');
    });

    $('.litFileDeleteButton').click(function (e) {
        e.preventDefault();

        var alias = $(this).data('filealias');
        swal({
            title: "Вы уверенны?",
            text: "После удаления файл будет невозможно восстановить!",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            cancelButtonText: 'Нет, не удалять.',
            confirmButtonText: "Да, удалить!",
            closeOnConfirm: false
        }, function () {
            jQuery.ajax({
                url: $('#litEditFileForm').attr('action'),
                type: 'POST',
                dataType: 'json',
                data: {
                    action: 'deleteFile',
                    data: alias
                },
                success: function (data) {
                    if (data.error) {
                        console.log(data.message);
                    } else {
                        $('a[data-filealias="' + alias + '"]').parents('.file-box').fadeOut('slow', function(){
                            $(this).remove();
                        });
                        swal("Удалено!", data.message, "success");
                    }
                },
                error: function (data) {
                    ajaxError(data);
                }
            });
        });
    });

    $('.litFileLink').click(function(e){
        if (e.which == 1) {
            e.preventDefault();

            $(this).parents('.file-box').toggleClass('selected');
        }
    });

    $('.litFileLink').dblclick(function(e){
        e.preventDefault();

        var win = window.open($(this).attr('href'), '_blank');
        if(win){
            win.focus();
        }
    });

    $('#sel-all').click(function (e) {
        e.preventDefault();

        $('.file-box').addClass('selected');
    });

    $('#sel-inverse').click(function (e) {
        e.preventDefault();

        $selected = $('.file-box.selected');
        $('.file-box').addClass('selected');
        $selected.removeClass('selected');
    });

    $('#sel-delete').click(function (e) {
        e.preventDefault();

        var arrAlias = [];
        var $selected = $('.file-box.selected');
        $selected.each(function(key, val){
            arrAlias.push($(val).find('.litFileDeleteButton').data('filealias'));
        });

        if (arrAlias.length > 0) {
            swal({
                title: "Вы уверенны, что хотите удалить " + arrAlias.length + " " + GetNoun(arrAlias.length, 'файл', 'файла', 'файлов') + "?",
                text: "После удаления файл будет невозможно восстановить!",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                cancelButtonText: 'Нет, не удалять.',
                confirmButtonText: "Да, удалить!",
                closeOnConfirm: false
            }, function () {
                jQuery.ajax({
                    url: $('#litEditFileForm').attr('action'),
                    type: 'POST',
                    dataType: 'json',
                    data: {
                        action: 'deleteManyFiles',
                        data: arrAlias
                    },
                    success: function (data) {
                        if (data.error) {
                            console.log(data.message);
                        } else {
                            $selected.fadeOut('slow', function(){
                                $(this).remove();
                            });
                            swal("Удалено!", data.message, "success");
                        }
                    },
                    error: function (data) {
                        ajaxError(data);
                    }
                });
            });
        }
    });
});