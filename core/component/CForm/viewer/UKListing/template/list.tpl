<div class="cform w-hundred">
    <div class="overflow-form">
        <form method="post" action="#" class="cf-table">
        <div class="cf-table-caption cf-caption">
            <span>{CAPTION}</span>
        </div>
        <div class="cf-table-row">
            {TH}
                <div class="cf-table-row-th">
                    <a href="{HREF}" {ICON}><span>{TEXT} </span></a>
                </div>
            {/TH}
        </div>
        {TR}
        <div class="cf-table-row">
            {TD_FIELD}
            <div class="cf-table-row-td">
                {COMPONENT}
            </div>
            {/TD_FIELD}
            <div class="cf-table-row-td min {CLASS_ROW}">
                <div class="uk-button-group">
                    {TD_BUTTON}
                        {COMPONENT}
                    {/TD_BUTTON}
                </div>
            </div>
        </div>
        {/TR}
        <div class="group-action ">
            <div class="uk-button-group">
                {BUTTON}
                    {COMPONENT}
                {/BUTTON}
            </div>
        </div>
    </form>
    </div>
    <div id="pagination_table" class="uk-placeholder uk-text-center uk-navbar-container uk-navbar">
        <div class="uk-navbar-left">
            <div class="uk-inline">
                <span class="select dotted"> Показывать по: {ON_PAGE}</span>
                <div uk-dropdown="mode: click;pos: top" class="uk-dropdown">
                    <ul class="uk-nav uk-dropdown-nav">
                        {ON_PAGE_LIST}
                        <li class="{CLASS}">
                            <a href="">{TEXT}</a>
                        </li>
                        {/ON_PAGE_LIST}
                    </ul>
                </div>
            </div>
        </div>
        <div class="uk-navbar-center">
            <ul class="uk-pagination uk-flex-center" uk-margin>
                {PAGINATION}
                    <li class="{CLASS}"><a href="../../../../../../index.php">{TEXT}</a></li>
                {/PAGINATION}
            </ul>
        </div>
        <div class="uk-navbar-right">
            с {ROW_FORM} по {ROW_TO} из {ROW_ALL}
        </div>
    </div>

</div>
<div class="cform-after {CLASS_ROWS}"></div>
