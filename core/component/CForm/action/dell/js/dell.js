/**
 * Created by Roman on 7.6.2017.
 */

$(document).ready(function () {
    $('.dells').on('click', function () {
        var form    =   $(this).parents('form'),
            href    =   $(this).prop('href');
        form.prop('action', href);
        form.submit();
        return false;
    })
});