<!DOCTYPE html>
<html lang="en">
    <head>
        {include 'block/system/head.tpl'}
    </head>
    <body>
        <form action="{URL}" method="post" id="enter-form" class="uk-card uk-card-default uk-position-center">
            <div class="uk-card-header uk-text-center">
                <h4 class="uk-card-title uk-margin-remove-bottom">Панель администратора</h4>
            </div>
            <div class="uk-card-body">
                <div class="uk-margin">
                    <div class="uk-inline">
                        <span class="uk-form-icon" uk-icon="icon: user"></span>
                        <input class="uk-input" type="text" name="login" placeholder="Логин">
                    </div>
                </div>

                <div class="uk-margin">
                    <div class="uk-inline">
                        <span class="uk-form-icon uk-form-icon-flip" uk-icon="icon: lock"></span>
                        <input class="uk-input" type="password" name="password" placeholder="Пароль">
                    </div>
                </div>
            </div>
            <div class="uk-card-footer uk-text-center">
                <button type="submit" class="uk-button uk-button-primary" id="enter">Авторизация</button>
            </div>
        </form>
        {include 'block/footer.tpl'}
    </body>
</html>