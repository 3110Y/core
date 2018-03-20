<script>
    var ckeditor_field_{ID};
    $(document).ready(function () {
        ckeditor_field_{ID} = CKEDITOR.replace( '{ID}', {
            filebrowserBrowseUrl:       '/core/component/library/vendor/CKEditor/kcfinder/browse.php?type=files',
            filebrowserImageBrowseUrl:  '/core/component/library/vendor/CKEditor/kcfinder/browse.php?type=images',
            filebrowserFlashBrowseUrl:  '/core/component/library/vendor/CKEditor/kcfinder/browse.php?type=flash',
            filebrowserUploadUrl:       '/core/component/library/vendor/CKEditor/kcfinder/upload.php?type=files',
            filebrowserImageUploadUrl:  '/core/component/library/vendor/CKEditor/kcfinder/upload.php?type=images',
            filebrowserFlashUploadUrl:  '/core/component/library/vendor/CKEditor/kcfinder/upload.php?type=flash',
            on: {
                instanceReady: function( evt )
                {
                    $(window).resize();
                }
            }
        });
    });
</script>