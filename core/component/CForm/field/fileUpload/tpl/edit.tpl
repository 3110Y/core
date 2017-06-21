<div class="cform-field {FIELD_CLASS}" style="{FIELD_STYLE}">

	<label for="{ID}" class="uk-form-label {LABEL_CLASS}" title="{LABEL_TITLE}" {TOOLTIP} style="vertical-align: top; {LABEL_STYLE}">{LABEL}</label>

	<div class="uk-form-controls {CONTROLS_CLASS}" style="{CONTROLS_STYLE}">
		{PREV_ICON}
		{POST_ICON}
		<div uk-form-custom class="fileUpload-dflex">
            {VALUE}
            <div uk-form-custom="target: true">
                <input type="file" id="{ID}" name="{NAME}"  class="{CLASS}" placeholder="{PLACEHOLDER}" style="{STYLE}" {REQUIRED}>
                <input class="uk-input uk-form-width-medium" type="text" placeholder="Выберете файл" disabled>
                <button class="uk-button uk-button-default uk-form-width-medium" >Загрузить</button>
            </div>

		</div>
	    {INIT}
    </div>

</div>
