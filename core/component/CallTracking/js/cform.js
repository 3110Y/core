window.jQuery(function($){
    'use strict';

    var waves,
        wave_modal,
        modal_template =
            '<div id="callRecordPlayer" uk-modal>' +
            '<div class="uk-modal-dialog">' +
            '<button class="uk-modal-close-default" type="button" uk-close></button>' +
            '<div class="uk-modal-header"><h2 class="uk-modal-title">Аудиоплеер</h2></div>' +
            '<div class="uk-modal-body">' +
            '<div class="preloader">' +
            '<p>Пожалуйста, подождите.. </p>' +
            '<div class="icon"></div>' +
            '</div>' +
            '<div class="waveform"></div>' +
            '</div>' +
            '<div class="uk-modal-footer uk-text-center">' +
            '<button class="uk-button uk-button-default uk-button-primary toggler-play" type="button">Play/Pause</button>' +
            '<button class="uk-button uk-button-default uk-modal-close" type="button">Закрыть</button>' +
            '</div>' +
            '</div>' +
            '</div>'

    $('body').append(modal_template);
    wave_modal = UIkit.modal('#callRecordPlayer');

    $('.call-tracking-alert-data').on('click',function(e){
        e.preventDefault();
        window.UIkit.modal.alert($(this).next().html());
    });
    $('.call-tracking-play-record').on('click',function(e){
        e.preventDefault();
        var sound = $(this).data('sound');
        wave_modal.$el.find('.toggler-play').hide();
        wave_modal.$el.find('.preloader').show();
        wave_modal.show();

        waves = WaveSurfer.create({
            container: '.waveform',
            waveColor: '#999999',
            progressColor: '#29415F'
        });

        waves.load(sound);
        waves.on('ready', function () {
            wave_modal.$el.find('.toggler-play').show();
            wave_modal.$el.find('.preloader').hide();
            waves.play();
        });
    });
    wave_modal.$el.on({
        'hide.uk.modal': function(){
            if(waves && waves.destroy)
            {
                waves.destroy();
            }
        }
    });
    wave_modal.$el.find('.toggler-play').on('click', function(e) {
        waves.playPause();
    });
});

