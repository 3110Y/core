<!DOCTYPE html>
<html lang="en">
<head>
    {include 'block/head.tpl'}
</head>
<body>
<div id="wrapper-hidden">
    <div id="wrapper">
        <header uk-navbar>
            <a class="uk-navbar-item uk-logo" href="/">Панель администратора</a>
            <div class="uk-navbar-left">
                <ul class="uk-navbar-nav">
                    <li class="only-mobile">
                        <a href="#" id="slide-menu">
                            <span class="uk-icon uk-margin-small-right" uk-icon="icon: menu"></span> Меню
                        </a>
                    </li>
                </ul>
            </div>
            <div class="uk-navbar-right">
                <ul class="uk-navbar-nav">
                    <li>
                        <a href="/admin/logout"><span class="uk-icon uk-margin-small-right" uk-icon="icon: sign-out"></span> Выход</a>
                    </li>
                </ul>
            </div>
        </header>
        <div class="header-bg"></div>
        <div id="column-left-substrate"> </div>
        <div class="column column-left">
            <div id="general-menu">
               {include 'block/menuItem.tpl'}
            </div>
        </div>
        <div class="column column-right no-bg">
            {CONTENT}
        </div>
    </div>

    {include 'block/footer.tpl'}
</div>
</body>
</html>