<script>
    $(document).ready(function () {

        var bar = $("#progressbar-{ID}")[0],
            action      =   $('#{ID}').attr('data-action');

        UIkit.upload('#{ID}', {
            url: action,
            multiple: true,
            name:'{ID}',
            beforeSend: function() { console.log('beforeSend', arguments); },
            beforeAll: function() { console.log('beforeAll', arguments); },
            load: function() { console.log('load', arguments); },
            error: function() { console.log('error', arguments); },
            complete: function(responseText) {
                console.log(responseText.responseText);
                var answer    = JSON.parse(responseText.responseText);
                var text            =  answer.content;
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

    });
</script>