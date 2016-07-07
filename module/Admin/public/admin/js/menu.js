

function bindEditMenu(){
    $('.i-checks').iCheck({
        checkboxClass: 'icheckbox_square-green',
        radioClass: 'iradio_square-green'
    });
    
    $('.i-checks').on('ifChanged', function(e){
        var dataObj = {
            id: $(this).parents('tr').data('id'),
            active: ($(this).iCheck('data')[0].checked) ? 1 : 0
        };

        jQuery.ajax({
            url: $(this).parents('tbody').data('action'),
            type: 'POST',
            dataType: 'json',
            data: {
                action: 'setActive',
                data: dataObj
            },
            success: function (data) {
                var type = 'error';
                if (data.error) {
                    console.log(data.message);
                } else {
                    type = 'success';
                }

                toastr[type](data.message, null);

            },
            error: function (data) {
                ajaxError(data);
            }
        });
    });

    $('.litDeleteMenu').click(function(){
        var $el = $(this);

        jQuery.ajax({
            url: $(this).parents('tbody').data('action'),
            type: 'POST',
            dataType: 'json',
            data: {
                action: 'deleteMenuItem',
                data: {
                    id: $(this).parents('tr').data('id')
                }
            },
            success: function (data) {
                var type = 'error';
                if (data.error) {
                    console.log(data.message);
                } else {
                    type = 'success';
                    $el.parents('tr').remove()
                }

                toastr[type](data.message, null);

            },
            error: function (data) {
                ajaxError(data);
            }
        });
    });

    $('.litEditMenu').dblclick(function () {
        $(this).children().toggle(0, function(){
            $(this).find('input').focus();
        });
    });

    $('.litEditMenu input').dblclick(function (e) {
        e.stopPropagation();
    });

    $('.litEditMenu input').keyup(function(e) {
        if (e.which == 13) {
            var $el = $(this);

            var dataObj = {
                id: $(this).parents('tr').data('id'),
                name: $(this).attr('name'),
                value: $(this).val()
            };

            jQuery.ajax({
                url: $(this).parents('tbody').data('action'),
                type: 'POST',
                dataType: 'json',
                data: {
                    action: 'updateParam',
                    data: dataObj
                },
                success: function (data) {
                    var type = 'error';
                    if (data.error) {
                        console.log(data.message);
                    } else {
                        type = 'success';
                    }

                    $el.parent().prev().text($el.val()).parent().children().toggle();
                    toastr[type](data.message, null);
                },
                error: function (data) {
                    var $textEl = $el.parent().prev();
                    $el.val($textEl.text());
                    $textEl.parent().children().toggle();

                    ajaxError(data);
                }
            });
        } else if (e.which == 27) {
            var $textEl = $(this).parent().prev();
            $(this).val($textEl.text());
            $textEl.parent().children().toggle();
        }
    });

    $('input[type=checkbox].i-checks').change(function(e){
        console.log($(this).prop());
    });
}

$(document).ready(function(){
    bindEditMenu();

    $("tbody.ui-sortable").sortable().on('sortstop', function(event, ui){
        var arrWeight = [];

        var index = 1;
        $(this).children().each(function(){
            var id = $(this).data('id');
            if (id != "") {
                arrWeight[id] = index;
                index++;
            }
        });

        jQuery.ajax({
            url: $(this).data('action'),
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
    }).disableSelection();

    $('#litAddMenuForm').submit(function (e) {
        e.preventDefault();

        var ser = $(this).serializeArray(),
            menuItem = {label: "", url: "", active: 0};
        $.each(ser, function(key, val){
            menuItem[val.name] = val.value;
        });

        jQuery.ajax({
            url: $(this).attr('action'),
            type: 'POST',
            dataType: 'json',
            data: {
                action: 'addMenuItem',
                data: menuItem
            },
            success: function (data) {
                var type = 'error';
                if (data.error) {
                    console.log(data.message);
                } else {
                    $('#modal-close').click();

                    var $elClone = $('tbody tr#blank').clone();
                    $elClone.attr('data-id', data.id).removeAttr('id').removeAttr('style');

                    $elClone.find('span.litMenuLabel').text(menuItem.label);
                    $elClone.find('input[name="label"]').val(menuItem.label);

                    $elClone.find('span.litMenuUrl').text(menuItem.url);
                    $elClone.find('input[name="url"]').val(menuItem.url);

                    $elClone.find('.i-checks-next').prop('checked', menuItem.active);
                    $elClone.appendTo($('tbody'));
                    $elClone.find('.i-checks-next').removeClass('i-checks-next').addClass('i-checks');

                    bindEditMenu();
                }
                var header = (data.msgHeader != undefined) ? data.msgHeader : null;

                toastr['success'](data.message, header);

            },
            error: function (data) {
                ajaxError(data);
            }
        });
    });
});