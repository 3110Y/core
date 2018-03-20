<div class="cform-field uk-width-{GRID}@m">
        <input type="text" pattern=".*" id="{ID}" name="{FIELD}" class="uk-input {CLASS} aa-datetime" data-mode="{OPTION_MODE}" placeholder="{PLACEHOLDER}" style="{STYLE}" value="{VALUE}" {READONLY} required>
        <label for="{ID}" class="uk-form-label">{LABEL} <span>{REQUIRED}</span></label>
</div>
<script>
$(function() {
    $('#{ID}').datetimepicker({
        {PICKER_OPTION}
    });
});
</script>