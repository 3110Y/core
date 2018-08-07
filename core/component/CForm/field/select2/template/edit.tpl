<div class="uk-width-{GRID}@m">
    <label for="{ID}" class="uk-form-label {MULTIPLE}">{LABEL} <span>{REQUIRED}</span></label>
    <select id="{ID}" name="{ID_NAME}" class="uk-select {CLASS}" {MULTIPLE} placeholder="{PLACEHOLDER}" style="{STYLE}" {READONLY} required>
        {LIST}
        <option value="{ID}" {SELECTED} {DISABLED} {DATA}data-{KEY}="{VALUE}"{/DATA}>{NAME}</option>
        {/LIST}
    </select>
    {INIT}
</div>
