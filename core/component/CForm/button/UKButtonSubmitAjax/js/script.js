/**
 * Created by Roman on 7.6.2017.
 */

$(document).ready(function () {
    $('.dells').on('click', function (e) {
        var formID      =   $(this).attr('data-id'),
            form        =   $(formID),
            href        =   $(this).attr('data-href');
        console.log(href);
        form.prop('action', href);
        form.submit();
        return false;
    })
});