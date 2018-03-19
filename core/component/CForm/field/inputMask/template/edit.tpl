<div class="cform-field uk-width-{GRID}@m">
        <input type="text" pattern=".*" id="{ID}" name="{FIELD}" class="uk-input {CLASS}" placeholder="{PLACEHOLDER}" style="{STYLE}" value="{VALUE}" {READONLY} required>
        <label for="{ID}" class="uk-form-label">{LABEL} <span>{REQUIRED}</span></label>
        <script>
            $(document).ready(function () {
                alert('dsgsdg');
                $("#{ID}").inputmask("{MASK}", {
                    colorMask: true
                });
            });

        </script>
</div>
