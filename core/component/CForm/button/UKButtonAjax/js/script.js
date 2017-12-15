/**
 * Created by Roman on 7.6.2017.
 */
$(document).ready(function () {
    var button      =   $('.save'),
        action      =   button.attr('href'),
        success     =   button.attr('data-success'),
        error       =   button.attr('data-error'),
        form        =   button.parents('form');

    button.on('click', function () {
        $.ajax({
            method: "POST",
            url: action,
            data: form.serialize(),
            afterSend: function (xhr) {
                if (typeof(CKEDITOR) !== 'undefined') {
                    for (instance in CKEDITOR.instances) {
                        CKEDITOR.instances[instance].updateElement();
                    }
                }
            },
            cache: false,
            error: function (qXHR, textStatus, errorThrown) {
                console.log(qXHR, textStatus, errorThrown);
                UIkit.notification("Критическая ошибка", {
                    status: 'danger',
                    timeout: 50000,
                    pos: 'top-center'
                });
                UIkit.notification(error, {
                    status: 'warning',
                    timeout: 50000,
                    pos: 'bottom-right'
                });
            }

        }).done(function (msg) {
            var data = JSON.parse(msg);
            console.log(data.data);
            if(data.result === true) {
                UIkit.notification(success, {
                    status: 'success',
                    timeout: 50000,
                    pos: 'top-center'
                });
                for (var name in data.data) {
                    $('#' + name).val(data.data.name);
                }
            } else {
                UIkit.notification(error, {
                    status: 'warning',
                    timeout: 50000,
                    pos: 'top-center'
                })
            }
        });
        return false;
    });

});