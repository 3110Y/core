var waves,
    wave_modal,
    modal_template =
'<div id="AAsound-modal" uk-modal>' +
    '<div class="uk-modal-dialog">' +
        '<button class="uk-modal-close-default" type="button" uk-close></button>' +
        '<div class="uk-modal-header"><h2 class="uk-modal-title">Аудиоплеер</h2></div>' +
        '<div class="uk-modal-body">' +
            '<div class="AAsound-preloader">' +
                '<p>Пожалуйста, подождите.. </p>' +
                '<div class="AAsound-preloader-svg"></div>' +
            '</div>' +
			'<div class="AAsound-waveform"></div>' +
		'</div>' +
        '<div class="uk-modal-footer uk-text-center">' +
            '<button class="uk-button uk-button-default uk-button-primary AAsound-toggler-play" type="button">Play/Pause</button>' +
            '<button class="uk-button uk-button-default uk-modal-close" type="button">Закрыть</button>' +
        '</div>' +
    '</div>' +
'</div>';

$(function() {
    $('body').append(modal_template);
    wave_modal = UIkit.modal('#AAsound-modal');
    $('#AAsound-modal').on({
        'hide.uk.modal': function(){
            if(waves !== null)
            {
                waves.destroy();
            }
        }
    });

    $('.AAsound-call').click(function(e) {
        e.preventDefault();
        var sound = $(this).attr('data-sound');
        $('.AAsound-toggler-play').hide();
        $('.AAsound-preloader').show();
        wave_modal.show();

        waves = WaveSurfer.create({
            container: '.AAsound-waveform',
            waveColor: '#999999',
            progressColor: '#29415F'
        });

        waves.load(sound);
        waves.on('ready', function () {
            $('.AAsound-toggler-play').show();
            $('.AAsound-preloader').hide();
            waves.play();
        });
    });
});

$(document).on('click', '.AAsound-toggler-play', function(e) {
    waves.playPause();
});
