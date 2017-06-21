/**
 * Created by gaevoy on 13.06.17.
 */
$(document).ready(function () {
   $('#enter').on('click', function () {
       var form        =   $(this).parents('form'),
           action      =   form.attr('action'),
           data        =   form.serialize(),
           jqXHR       =   $.post(action, data);
       console.log(action);
       console.log(data);
       jqXHR.done(function(data) {
           console.log(data);
           if (data !== 'true') {
               UIkit.notification("Не верный логин или пароль", {
                   status: 'danger',
                   timeout: 1000,
                   pos: 'top-center'
               });
           } else {
               UIkit.notification("Добро пожаловать!", {
                   status: 'success',
                   timeout: 1000,
                   pos: 'top-center'
               });
               form.submit();
               return true;
           }
       });
       return false;
   });
});