<div class="cform-field {FIELD_CLASS}" style="{FIELD_STYLE}">

    <label for="{ID}" class="uk-form-label {LABEL_CLASS}" title="{LABEL_TITLE}" {TOOLTIP} style="{LABEL_STYLE}">{LABEL}</label>

    <div class="uk-form-controls {CONTROLS_CLASS}" style="{CONTROLS_STYLE}">
        {PREV_ICON}
        {POST_ICON}
        <select id="{ID}" name="{NAME}" class="uk-select select-field{ID}-id{DATA_ID} {CLASS}" {MULTIPLE} placeholder="{PLACEHOLDER}" data-new-placeholder="{TOP_PLACEHOLDER}" style="{STYLE}" value="{VALUE}" {REQUIRED}>
            {LIST}
                <option value="{LIST_ID}" {LIST_SELECTED}>{LIST_NAME}</option>
            {/LIST}
        </select>
        {INIT}
    </div>

</div>