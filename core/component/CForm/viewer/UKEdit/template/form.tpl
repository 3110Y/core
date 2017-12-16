<div class="cform w-hundred">
    <div class="cf-caption {CAPTION_CLASS}">
        <span>{CAPTION}</span>
    </div>
    <form action="#" method="post" class="edit" enctype="multipart/form-data">
        <div class="cf-table uk-grid-small uk-flex-center uk-margin" uk-grid>
            {FIELDS}
                {COMPONENT}
            {/FIELDS}
        </div>
        <div class="item-action ">
            <div class="uk-button-group">
                {ROWS}
                {COMPONENT}
                {/ROWS}
            </div>
        </div>
    </form>
</div>