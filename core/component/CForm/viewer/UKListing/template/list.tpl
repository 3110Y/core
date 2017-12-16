<div class="cform w-hundred">
    <div class="overflow-form">
        <div class="cf-table-caption cf-caption">
            <span>{CAPTION}</span>
        </div>
        <form method="post" action="#" id="form-listing">
            <table class="uk-table uk-table-divider uk-table-hover uk-table-striped">
                <thead>
                    <tr>
                        {TH}
                        <th class="uk-text-center">
                            <a href="{HREF}" {ICON}>
                                {TEXT}
                            </a>
                        </th>
                        {/TH}
                    </tr>
                </thead>
                <tbody>
                    {TR}
                    <tr>
                        {TD_FIELD}
                            {COMPONENT}
                        {/TD_FIELD}
                        <td class="uk-table-shrink">
                            <div class="uk-button-group">
                                {TD_BUTTON}
                                    {COMPONENT}
                                {/TD_BUTTON}
                            </div>
                        </td>
                    </tr>
                    {/TR}
                </tbody>
            </table>
        <div class="group-action ">
            <div class="uk-button-group">
                {ROWS}
                    {COMPONENT}
                {/ROWS}
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
                            <a href="{HREF}">{TEXT}</a>
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
            с {ROW_FORM} по {ROW_TO} из {TOTAL_ROWS}
        </div>
    </div>

</div>
<div class="cform-after {CLASS_ROWS}"></div>
