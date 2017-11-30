/**
 * Created by gaevoy on 13.06.17.
 */

$(document).ready(function () {
   $('.password-toggle').on('click', function () {
       var toggle   = $(this),
           parent   = toggle.parent(),
           password = parent.find('input'),
           type     = password.prop('type');
       if (type === 'password') {
           password.attr('type', 'text');
           toggle.attr('uk-icon', 'icon: unlock');
       } else {
           password.attr('type', 'password');
           toggle.attr('uk-icon', 'icon: lock');
       }

       return false;
   });
});