/**
 * Created by Roman on 11.5.2017.
 */
$(document).ready(function () {
    var slideMenu            = $('#slide-menu'),
        wrapper             = $('#wrapper'),
        wrapperHidden       = $('#wrapper-hidden'),
        columnLeftSubstrate = $('#column-left-substrate'),
        generalMenu         = $('#general-menu'),
        generalMenuLi       = $('#general-menu li'),
        generalMenuLia      = $('#general-menu li a');

    slideMenu.click(function () {
        wrapper.addClass('slide-left');
        return false;
    });
    wrapperHidden.on('swiperight', function(e) {
        wrapper.addClass('slide-left');
        return false;
    });
    columnLeftSubstrate.click(function () {
        wrapper.removeClass('slide-left');
        return false;
    });
    wrapperHidden.on('swipeleft', function(e) {
        wrapper.removeClass('slide-left');
        return false;
    });

    generalMenuLia.click(function (e) {
        e.preventDefault();
        $(this).parent('li').toggleClass('open');
    });
});