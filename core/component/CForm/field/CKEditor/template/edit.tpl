<div class="cform-field uk-width-{GRID}">
    <textarea id="{ID}" name="{NAME}" class="uk-textarea {CLASS}" placeholder="{PLACEHOLDER}" style="{STYLE}" required>{VALUE}</textarea>
    <label for="{ID}" class="uk-form-label">{LABEL} <span>{REQUIRED}</span></label>
</div>
<script>
    var ckeditor_field_{NAME} = CKEDITOR.replace( '{NAME}', {
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