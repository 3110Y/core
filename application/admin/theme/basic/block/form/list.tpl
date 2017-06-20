<div class="cform w-hundred">
    <form method="post" action="{URL}" class="cf-table">
        <div class="cf-table-caption cf-caption {CAPTION_CLASS}">
            <h3>{CAPTION}</h3>
            <!--<a href="" uk-icon="icon: more-vertical; ratio: 1.65" uk-toggle="target: #cform-sort"></a>-->
        </div>
        <div class="cf-table-row">
            {HEADER_ROW}
                <div id="{ID}" class="cf-table-row-th {CLASS}" style="{STYLE}">
                    <span>{COMPONENT}</span>
                </div>
            {/HEADER_ROW}
        </div>
        {ROWS}
        <div class="cf-table-row">
            {FIELDS}
            <div id="{ID}" class="cf-table-row-td {CLASS}" style="{STYLE}">
                {COMPONENT}
            </div>
            {/FIELDS}
            <div class="cf-table-row-td min {CLASS_ROW}">
                <div class="uk-button-group">
                    {ACTION_ROW}
                        {COMPONENT}
                    {/ACTION_ROW}
                </div>
            </div>
        </div>
        {/ROWS}
        <div class="group-action {CLASS_ROWS}">
            <div class="uk-button-group">
                {ACTION_ROWS}
                {COMPONENT}
                {/ACTION_ROWS}
            </div>
        </div>
    </form>
    <div id="pagination_table" class="uk-placeholder uk-text-center uk-navbar-container uk-navbar">
        <div class="uk-navbar-left">
            <div class="uk-inline">
                <span class="select"> Показывать по: {ON_PAGE}</span>
                <div uk-dropdown="mode: click;pos: top" class="uk-dropdown">
                    <ul class="uk-nav uk-dropdown-nav">
                        {ON_PAGE_LIST}
                        <li class="{CLASS}">
                            <a href="{URL}">{TEXT}</a>
                        </li>
                        {/ON_PAGE_LIST}
                    </ul>
                </div>
            </div>
        </div>
        <div class="uk-navbar-center">
            <ul class="uk-pagination uk-flex-center" uk-margin>
                {PAGINATION}
                    <li class="{CLASS}"><a href="{HREF}">{TEXT}</a></li>
                {/PAGINATION}
            </ul>
        </div>
        <div class="uk-navbar-right">
            Показано: с {ROW_FORM} по {ROW_TO} из {ROW_ALL}
        </div>
    </div>
    <div id="cform-sort" uk-offcanvas="flip: true; overlay: true">
        <div class="uk-offcanvas-bar">

            <button class="uk-offcanvas-close" type="button" uk-close></button>

            <h3>Title</h3>

            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.</p>

        </div>
    </div>
</div>
<div class="cform-after {CLASS_ROWS}"></div>
