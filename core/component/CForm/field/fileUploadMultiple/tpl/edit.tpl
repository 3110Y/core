<div class="cform-field {FIELD_CLASS}" style="{FIELD_STYLE}">
    <label for="{ID}" class="uk-form-label {LABEL_CLASS}" title="{LABEL_TITLE}" {TOOLTIP} style="vertical-align: top;{LABEL_STYLE}">{LABEL}</label>
    <ul class="uk-form-controls {CONTROLS_CLASS}" style="{CONTROLS_STYLE}">
        {PREV_ICON}
        {POST_ICON}
    <div id="{ID}" class="test-upload uk-placeholder uk-text-center">
        <span uk-icon="icon: cloud-upload"></span>
        <span class="uk-text-middle">Прикрепите файлы, перетащиав их сюда или</span>
        <div uk-form-custom>
            <input type="file" multiple  name="{NAME}"  class="{CLASS}" placeholder="{PLACEHOLDER}" style="{STYLE}" {REQUIRED}>
            <span class="uk-link">выберете одно</span>
        </div>
    </div>
    <progress id="progressbar-{ID}" class="uk-progress" value="0" max="100" hidden></progress>
        <div class="uk-flex uk-flex-between uk-flex-wrap uk-flex-wrap-around">
            {VALUE}
                <div class="uk-card uk-card-default parent-photo" style="margin-top: 10px">
                    <div class="uk-card-body">
                        <img src="{IMG_IMG}">
                    </div>
                    <div class="uk-card-footer uk-text-center">
                        <a href="#" data-id="{IMG_ID}" class="{ID}-dell uk-button uk-button-danger">Удалить</a>
                    </div>
                </div>
            {/VALUE}
        </div>
</div>
{INIT}
