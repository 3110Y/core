<script>

    (function ($) {

        var bar = $("#progressbar-{ID}")[0],
            form        =   $('#{ID}').parents('form'),
            action      =   form.attr('action');

        sortable('#card{ID}', {
            items: '.parent-photo',
            forcePlaceholderSize: true ,
            placeholder: '<div class="uk-card uk-card-default parent-photo"  style="margin: 10px;float: left;width: 230px;">' +
            '<div class="uk-card-body uk-text-center">Переместить сюда</div>' +
            '</div>'
        });
        sortable('#card{ID}')[0].addEventListener('sortupdate', function(e) {
            var answer_sort = [],
                url_sort =  action + '/{CS_UNIQUE}?sort';
            $('#card{ID} > .parent-photo').each(function (index) {
                answer_sort[index] = $(this).attr('data-sort-id');
            });
            console.log(answer_sort);
            $.ajax({
                type: "post",
                url: url_sort,
                data: 'sort=' + JSON.stringify(answer_sort),
                success: function(data) {
                    console.log(data);
                    UIkit.notification("Сохранено", {status:'success'});
                }
            });
        });


        $('body').on('click', '.{ID}-dell', function () {
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
                    sortable('#card{ID}');
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
            complete: function(responseText) {
                console.log('complete', arguments);
                console.log(responseText.responseText);
                var text = JSON.parse(responseText.responseText);
                text    =   text['{CS_TABLE}'];
                console.log(text);
                $('#card{ID}').append(text);
                sortable('#card{ID}');
            },

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
                UIkit.notification("Сохранено", {status:'success'});
                setTimeout(function () {
                    bar.setAttribute('hidden', 'hidden');
                }, 1000);
            }
        });

    })(jQuery);

</script>