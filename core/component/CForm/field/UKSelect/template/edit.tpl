<div class="cform-field uk-width-{GRID}@m">
    <select id="{ID}" name="{ID_NAME}" class="uk-select {CLASS}" {MULTIPLE} placeholder="{PLACEHOLDER}" style="{STYLE}" {READONLY} required>
        {LIST}
        <option value="{ID}" {SELECTED} {DISABLED} {DATA}data-{KEY}="{VALUE}"{/DATA}>{NAME}</option>
        {/LIST}
    </select>
    <label for="{ID}" class="uk-form-label {MULTIPLE}">{LABEL} <span>{REQUIRED}</span></label>
</div>
