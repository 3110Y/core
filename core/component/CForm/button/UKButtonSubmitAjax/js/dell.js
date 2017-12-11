/**
 * Created by Roman on 7.6.2017.
 */

$(document).ready(function () {
    $('.dells').on('click', function () {
        alert('1');
        var dataHref    =   $(this).attr('data-href'),
            parent      =   $('[href="' + dataHref + '"]'),
            form        =   parent.parents('form'),
            href        =   $(this).prop('href');
        alert('2');
        form.prop('action', href);
        form.submit();
        return false;
    })
});