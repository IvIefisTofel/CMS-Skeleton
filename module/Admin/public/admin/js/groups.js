function bindEditGroups(){
    $('a.litEditGroup').click(function(e){
        e.preventDefault();

        var $litEditGroup = $(this).parents('tr').find('td.litEditGroup');
        $litEditGroup.children('span').hide();
        $litEditGroup.children('form').show();
    });

    $('.litCloseEditGroup').click(function (e) {
        e.preventDefault();

        var $litEditGroup = $(this).parents('tr').find('td.litEditGroup');
        $litEditGroup.children('form').hide();
        $litEditGroup.children('span').show();
        $litEditGroup.find('input').val($litEditGroup.children('span').text().trim());
    });

    $('.litEditGroup form').submit(function (e) {
        e.preventDefault();

        var $litEditGroup = $(this).parents('tr').find('td.litEditGroup');
        jQuery.ajax({
            url: $(this).attr('action'),
            type: 'POST',
            dataType: 'json',
            data: {
                action: 'editGroup',
                data: $(this).serializeArray()
            },
            success: function (data) {
                var type = 'error';
                if (data.error) {
                    console.log(data.message);

                    $litEditGroup.children('form').hide();
                    $litEditGroup.children('span').show();
                    $litEditGroup.find('input').val($litEditGroup.children('span').text().trim());
                } else {
                    type = 'success';

                    $litEditGroup.children('span').text($litEditGroup.find('input').val());
                    $litEditGroup.children('form').hide();
                    $litEditGroup.children('span').show();
                }
                var header = (data.msgHeader != undefined) ? data.msgHeader : null;

                toastr[type](data.message, header);
            },
            error: function (data) {
                ajaxError(data);
            }
        });
    });
}

$(document).ready(function(){
    $("tbody.ui-sortable").sortable().on('sortstop', function(event, ui){
        var arrWeight = [];

        var index = 1;
        $(this).children().each(function(){
            var id = $(this).children().first().text().trim();
            arrWeight[id] = index;

            index++;
        });

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
    }).disableSelection();

    $('.ibox .ibox-title').prepend('<button type="button" class="btn btn-primary btn-xs" data-toggle="modal" data-target="#litAddGroup"><span class="glyphicon glyphicon-plus"></span> Добавить группу</button> <small>Перетащите для изменения порядка.</small>');

    bindEditGroups();

    $('form#litAddGroupForm').submit(function (e) {
        e.preventDefault();

        $(this).find('button[data-dismiss="modal"]').click();
        jQuery.ajax({
            url: $(this).attr('action'),
            type: 'POST',
            dataType: 'json',
            data: {
                action: 'addGroup',
                data: $(this).serializeArray()
            },
            success: function (data) {
                var type = 'error';
                if (data.error) {
                    console.log(data.message);
                } else {
                    type = 'success';

                    var html = '<tr class="ui-sortable-handle"><th scope="row">{id}</th><td class="litEditGroup"><span class="litGroupName">{name}</span><form method="post" action="/admin/settings/editgroup/{id}" style="display: none;"><div class="input-group input-group-sm"><input type="text" class="form-control" name="name" value="{name}"><span class="input-group-btn"><button class="btn btn-default litCloseEditGroup" type="button" style="border-radius: 0;">Отмена</button></span><span class="input-group-btn"><button class="btn btn-primary" type="submit">Сохранить</button></span></div></form></td><td class="litEditGroupCell"><a href="/admin/settings/editgroup/{id}" class="btn btn-primary btn-xs litEditGroup" title="Редактировать {name}"><span class="glyphicon glyphicon-pencil"></span></a></td></tr>';
                    html = html.replace(/{id}/g, data.id);
                    html = html.replace(/{name}/g, data.name);
                    $('#litSettingsGroup tbody').append(html);
                    bindEditGroups();
                }
                var header = (data.msgHeader != undefined) ? data.msgHeader : null;

                toastr[type](data.message, header);

            },
            error: function (data) {
                ajaxError(data);
            }
        });
        $(this).find('input').val('');
    });
});