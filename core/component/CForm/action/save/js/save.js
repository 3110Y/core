/**
 * Created by Roman on 8.6.2017.
 */

$(document).ready(function () {
    $('.save').on('click', function () {
        var form        =   $(this).parents('form'),
            action      =   form.attr('action'),
            data        =   form.serialize(),
            jqXHR       =   $.post(action, data);
        console.log(action);
        console.log(data);
        jqXHR.fail(function(data) {
            console.error(data);
            alert(data);
        });
        jqXHR.done(function(data) {
            var array = JSON.parse(data);
            if (array === true) {
                UIkit.notification("Сохранено", {status:'success'})
            } else {
                if (array.danger !== undefined && array.danger.length > 0) {
                    for (var i = 0, iMax = array.danger.length; i < iMax; i++) {
                        UIkit.notification(array.danger[i], {status: 'danger'})
                    }
                    UIkit.notification("Изменения не сохранены", {
                        status: 'warning',
                        timeout: 50000,
                        pos: 'bottom-right'
                    });
                } else {
                    UIkit.notification("Изменения сохранены", {
                        status: 'success',
                        timeout: 50000,
                        pos: 'bottom-right'
                    })
                }
                if (array.warning !== undefined && array.warning.length > 0) {
                    for (var i = 0, iMax = array.warning.length; i < iMax; i++) {
                        UIkit.notification(array.warning[i], {status: 'warning'})
                    }
                }
            }
        });
        return false;
    })
});