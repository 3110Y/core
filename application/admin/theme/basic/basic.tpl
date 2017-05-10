<!DOCTYPE html>
<html lang="en">
    <head>
        {include 'block/system/head.tpl'}
    </head>
    <body>
        <div class="uk-offcanvas-content">
            <header uk-navbar uk-sticky>
                <div class="uk-navbar-left">
                    <a class="uk-navbar-item uk-logo" href="/">Административная панель</a>
                    <ul class="uk-navbar-nav">
                        <li>
                            <a href="#"  uk-toggle="target: #menu-general">
                                <span class="uk-icon uk-margin-small-right" uk-icon="icon: menu"></span> Меню
                            </a>
                        </li>
                    </ul>
                </div>
                <div class="uk-navbar-right">
                    <ul class="uk-navbar-nav">
                        <li>
                            <a href="/logout"><span class="uk-icon uk-margin-small-right" uk-icon="icon: sign-out"></span> Выход</a>
                        </li>
                    </ul>
                </div>
                <div class="bg"></div>
            </header>
            <div id="wrapper">
                <div class="wrapper-left">
                    {include 'block/menu.tpl'}
                </div>
                <div class="wrapper-right">
                    {CONTENT}
                </div>
            </div>

            {include 'block/footer.tpl'}
        </div>
    </body>
</html>