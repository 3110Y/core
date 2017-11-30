/**
 * Created by gaevoy on 28.05.17.
 */

$(document).ready(function () {
    $('input.checkbox-rows').change(function () {
        var isChecked = false;
        if ($(this).is(':checked')) {
            isChecked = true;
        }
        if (isChecked) {
            $('input.checkbox-row').attr('checked', 'checked');
        } else {
            $('input.checkbox-row').removeAttr('checked');
        }
   });
});