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
            if (data.hasOwnProperty('data')) {
                for (var name in data.data) {
                    $('#' + name).removeClass('error');
                }
            }
            if(data.result === true) {
                UIkit.notification(success, {
                    status: 'success',
                    timeout: 20000,
                    pos: 'top-center'
                });
            } else {
                UIkit.notification(error, {
                    status: 'warning',
                    timeout: 20000,
                    pos: 'top-center'
                });

                if (data.hasOwnProperty('errorData')) {
                    var text = '',
                        flag = false;

                    for (var errorDataName in data.errorData) {
                        var flagIn = false;

                        if (data.errorData.hasOwnProperty(errorDataName)) {
                            if (data.errorData[errorDataName] !== true) {
                                text = data.errorData[errorDataName];
                            } else {
                                switch (errorDataName) {
                                    default:
                                        flagIn = true;
                                        text = 'Необходимо заполнить все обязательные поля';
                                        break;
                                }
                            }
                        }

                        if (flagIn === false || (flagIn === true && flag === false)) {
                            if (flagIn === true) flag = true;

                            UIkit.notification(text, {
                                status: 'danger',
                                timeout: 20000,
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