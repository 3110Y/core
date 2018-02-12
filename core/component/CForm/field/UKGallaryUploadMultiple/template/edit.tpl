<div class="uk-width-{GRID}@m">
    <label for="{ID}" class="uk-form-label">{LABEL}</label>
        <div id="{ID}" class="test-upload uk-placeholder uk-text-center"
             data-action="{URL}/{PARENT_URL}/api/field/UKGallaryUploadMultiple/save/{ROW_ID}/{TABLE}/{ID}/{TABLE_LINK}"
             data-action-edit="{URL}/{PARENT_URL}/api/field/UKGallaryUploadMultiple/sort/{ROW_ID}/{TABLE}/{ID}/{TABLE_LINK}"
             data-action-dell="{URL}/{PARENT_URL}/api/field/UKGallaryUploadMultiple/dell/{ROW_ID}/{TABLE}/{ID}/{TABLE_LINK}"
             style="{READONLY}"
        >
            <span uk-icon="icon: cloud-upload"></span>
            <span class="uk-text-middle">Загрузите файлы, перетащив их сюда или</span>
            <div uk-form-custom>
                <input type="file" multiple  name="{ID}[]"  class="{CLASS}" placeholder="{PLACEHOLDER}" style="{STYLE}">
                <span class="uk-link" style="color: #5897fb; border-bottom: 1px dotted #5897fb; ">нажмите для выбора</span>
            </div>
        </div>
        <progress id="progressbar-{ID}" class="uk-progress" value="0" max="100" hidden></progress>
        <div class="" id="card{ID}">
            {VALUE}
                {include 'card.tpl'}
            {/VALUE}
        </div>
</div>
{INIT}
