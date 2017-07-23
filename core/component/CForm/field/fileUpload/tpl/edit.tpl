<div class="cform-field {FIELD_CLASS}" style="{FIELD_STYLE}">

    <label for="{ID}" class="uk-form-label {LABEL_CLASS}" title="{LABEL_TITLE}" {TOOLTIP} style="vertical-align: top; {LABEL_STYLE}">{LABEL}</label>

    <div class="uk-form-controls {CONTROLS_CLASS}" style="{CONTROLS_STYLE}">
        {PREV_ICON}
        {POST_ICON}
        <div  class="fileUpload-dflex" id="{NAME}-cont-{ID}-all">
            {VALUE}
            <div id="{ID}" class="test-upload uk-placeholder uk-text-center"  style="margin-top: 0">
                <span uk-icon="icon: cloud-upload"></span>
                <div uk-form-custom>
                    <input type="file" multiple  name="{NAME}"  class="{CLASS}" placeholder="{PLACEHOLDER}" style="{STYLE}" {REQUIRED}>
                    <span class="uk-link" style="color: #5897fb; border-bottom: 1px dotted #5897fb; ">Загрузка</span>
                </div>
            </div>

            <progress id="progressbar-{ID}" class="uk-progress" value="0" max="100" hidden></progress>
        </div>
        {INIT}
    </div>

</div>
