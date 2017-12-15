<script>
    var ckeditor_field_{ID} = CKEDITOR.replace( '{ID}', {
        toolbar : '{MODE}',
        filebrowserBrowseUrl: '/core/component/CForm/field/ckeditor/vendor/kcfinder/browse.php?type=files',
        filebrowserImageBrowseUrl: '/core/component/CForm/field/ckeditor/vendor/kcfinder/browse.php?type=images',
        filebrowserFlashBrowseUrl: '/core/component/CForm/field/ckeditor/vendor/kcfinder/browse.php?type=flash',
        filebrowserUploadUrl: '/core/component/CForm/field/ckeditor/vendor/kcfinder/upload.php?type=files',
        filebrowserImageUploadUrl: '/core/component/CForm/field/ckeditor/vendor/kcfinder/upload.php?type=images',
        filebrowserFlashUploadUrl: '/core/component/CForm/field/ckeditor/vendor/kcfinder/upload.php?type=flash',
        on: {
            instanceReady: function( evt )
            {
                $(window).resize();
            }
        }
    });
</script>