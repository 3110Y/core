/**
 * Created by Roman on 8.6.2017.
 */

$(document).ready(function () {
    $('.save').on('click', function () {
        var form        =   $(this).parents('form'),
            action      =   form.prop('action'),
            data        =   form.serialize(),
            jqXHR       =   $.post(action, data);
        jqXHR.fail(function(data) {
            console.error(data);
            alert(data);
        });
        jqXHR.done(function(data) {
            var array = JSON.parse(data);
            if (array === true) {
                UIkit.notification("Сохранено", {status:'success'})
            } else {
                for (var i=0,iMax = array.length; i < iMax; i++) {
                    UIkit.notification(array[i], {status:'danger'})
                }
                UIkit.notification("Изменения не сохранены", {status:'warning',timeout:50000,pos:'bottom-right'})
            }
        });
        return false;
    })
});