<div class="cform w-hundred">
    <div class="cf-caption {CAPTION_CLASS}">
        <h3>{CAPTION}: Редактирование</h3>
    </div>
    <form action="" method="post">
        <div class="cf-table">
            {FIELDS}
                {COMPONENT}
            {/FIELDS}
        </div>
        <div class="item-action {CLASS_ACTION_ITEM}">
            <div class="uk-button-group">
                {ACTION_ITEM}
                {COMPONENT}
                {/ACTION_ITEM}
            </div>
        </div>
        <div class="group-action {CLASS_ACTION_BOTTOM_ITEM}">
            <div class="uk-button-group">
                {ACTION_BOTTOM_ITEM}
                    {COMPONENT}
                {/ACTION_BOTTOM_ITEM}
            </div>
        </div>
    </form>
</div>
<div class="cform-after {CLASS_ACTION_BOTTOM_ITEM}"></div>