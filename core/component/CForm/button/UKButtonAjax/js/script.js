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
        if (typeof(CKEDITOR) !== 'undefined') {
            for (var i in CKEDITOR.instances) {
                CKEDITOR.instances[i].updateElement();
            }
        }
        $.ajax({
            method: "POST",
            url: action,
            data: form.serialize(),
            beforeSend: function (xhr) {
                if (typeof(CKEDITOR) !== 'undefined') {
                    for (var i in CKEDITOR.instances) {
                        CKEDITOR.instances[i].updateElement();
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
            if(data.result === true) {
                UIkit.notification(success, {
                    status: 'success',
                    timeout: 50000,
                    pos: 'top-center'
                });
                for (var name in data.data) {
                 //   $('#' + name).val(data.data[name]);
                    $('#' + name).removeClass('error');
                }

            } else {
                UIkit.notification(error, {
                    status: 'warning',
                    timeout: 50000,
                    pos: 'top-center'
                });
                console.log(data.errorData);
                if (data.errorData !== undefined) {
                    for (var errorDataName in data.errorData) {
                        if (errorDataName !== true) {
                            UIkit.notification(data.errorData[errorDataName], {
                                status: 'danger',
                                timeout: 50000,
                                pos: 'top-center'
                            });
                        }
                        $('#' + errorDataName).addClass('error');
                    }
                }
            }
        });
        return false;
    });

});