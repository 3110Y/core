/**
 * Created by Roman on 7.6.2017.
 */

$(document).ready(function () {
    $('.dells').on('click', function () {
        var dataHref    =   $(this).attr('data-href'),
            parent      =   $('[href="' + dataHref + '"]'),
            form        =   parent.parents('form'),
            href        =   $(this).prop('href');
        form.prop('action', href);
        form.submit();
        return false;
    })
});