<script>

    (function ($) {

        var action      =   $('#{ID}').attr('data-action-dell');


        $('body').on('click', '.{ID}-dell', function () {
            var data_id         = $(this).attr('data-id'),
                url_dell        = action + '/' + data_id,
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