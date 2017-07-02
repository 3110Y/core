<script>

    (function ($) {

        var bar = $("#progressbar-{ID}")[0],
            form        =   $('#{ID}').parents('form'),
            action      =   form.attr('action');

        $('.{ID}-dell').on('click', function () {
            var data_id         = $(this).attr('data-id'),
                url_dell        = action + '/{CS_UNIQUE}?dell=' + data_id,
                parent_photo    =   $(this).parents('.parent-photo');
            $.ajax({
                type: "post",
                dataType: "json",
                url: url_dell,
                async: false,
                success: function(data) {
                    parent_photo.remove();
                }
            });
            return false;
        });
        UIkit.upload('#{ID}', {

            url: action + '/{CS_UNIQUE}',
            multiple: true,
            name:'{NAME}',
            beforeSend: function() { console.log('beforeSend', arguments); },
            beforeAll: function() { console.log('beforeAll', arguments); },
            load: function() { console.log('load', arguments); },
            error: function() { console.log('error', arguments); },
            complete: function() { console.log('complete', arguments); },

            loadStart: function (e) {
                console.log('loadStart', arguments);

                bar.removeAttribute('hidden');
                bar.max =  e.total;
                bar.value =  e.loaded;
            },

            progress: function (e) {
                console.log('progress', arguments);

                bar.max =  e.total;
                bar.value =  e.loaded;

            },

            loadEnd: function (e) {
                console.log('loadEnd', arguments);

                bar.max =  e.total;
                bar.value =  e.loaded;
            },

            completeAll: function () {
                console.log('completeAll', arguments);
                UIkit.notification("Сохранено", {status:'success'})
                setTimeout(function () {
                    bar.setAttribute('hidden', 'hidden');
                }, 1000);
            }
        });

    })(jQuery);

</script>