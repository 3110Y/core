<script>

    (function ($) {

        var action      =   $('#{ID}').attr('data-action-edit');

        sortable('#card{ID}', {
            items: '.parent-photo',
            forcePlaceholderSize: true ,
            placeholder: '<div class="uk-card uk-card-default parent-photo"  style="margin: 10px;float: left;width: 230px;">' +
            '<div class="uk-card-body uk-text-center">Переместить сюда</div>' +
            '</div>'
        });
        sortable('#card{ID}')[0].addEventListener('sortupdate', function(e) {
            var answer_sort = [],
                url_sort =  action;
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

    })(jQuery);

</script>