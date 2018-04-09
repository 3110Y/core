<ul>
    {FOR}
        <li class="{CLASS}">
            <a href="{URL}"><span uk-icon="icon: {ICON}"></span> {NAME}</a>
            {SUB_LINK}
                <a href="#"><</a>
            {SUB_LINK}
            {SUB}
                {include 'menuItem.tpl'}
            {/SUB}
        </li>
    {/FOR}
</ul>