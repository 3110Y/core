/**
 * Created by Roman on 8.6.2017.
 */

$(document).ready(function () {
    $('.save').on('click', function () {
        var form        =   $(this).parents('form'),
            action      =   form.attr('action');
        if ( typeof(CKEDITOR) !== 'undefined' ) {
            for (instance in CKEDITOR.instances) {
                CKEDITOR.instances[instance].updateElement();
            }
        }
        $(form).ajaxSubmit({
            url: action + '?json=true',
            iframe: true,
            dataType: 'json',
            success:    function(data) {
                var array = JSON.parse(data);
                console.log(data);
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
            },
            error: function(data) {
                console.log(data);
                UIkit.notification("Критическая ошибка", {
                    status: 'danger',
                    timeout: 50000,
                    pos: 'top-center'
                });
                UIkit.notification("Изменения не сохранены", {
                    status: 'warning',
                    timeout: 50000,
                    pos: 'bottom-right'
                });
            }
        });
        return false;
    })
});