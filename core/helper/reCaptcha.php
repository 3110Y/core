<?php
/**
 * Created by PhpStorm.
 * User: Евгений
 * Date: 06.03.2018
 * Time: 11:15
 */

namespace core\component\helper;

use \core\component\resources\resources;

/**
 * Trait GOOGLE reCAPTCHA
 *
 * @package core\helper
 */
trait reCaptcha
{

    use CURL;

    /**
     * Возращает реальный ip пользователя
     *
     * @return string
     */
    private static function getIP() : string
    {
        $client  = @$_SERVER['HTTP_CLIENT_IP'];
        $forward = @$_SERVER['HTTP_X_FORWARDED_FOR'];
        $remote  = $_SERVER['REMOTE_ADDR'];

        if(\filter_var($client, FILTER_VALIDATE_IP))
        {
            $ip = $client;
        }
        elseif(\filter_var($forward, FILTER_VALIDATE_IP))
        {
            $ip = $forward;
        }
        else
        {
            $ip = $remote;
        }

        return $ip;
    }

    /**
     * Секретный ключ
     * Как вариант - secret параметр из настроек
     *
     * @var string
     */
//    public static $reCaptchaPrivateKey = '';

    /**
     * [Публичный] Ключ
     * Как вариант - data-sitekey параметр из виджета
     * @var string
     */
//    public static  $reCaptchaPublicKey = '';


    /**
     * URL для POST-запроса проверки
     * @var string
     */
    public static  $reCaptchaVerifyURL = 'https://www.google.com/recaptcha/api/siteverify';

    /**
     * URL скрипта для подключения виджета на стороне клиента
     *
     * @var string
     */
    public static  $reCaptchaScriptURL = 'https://www.google.com/recaptcha/api.js';

    /**
     * Генерация виджета каптчи.
     * Ключ из параметра функции либо класса
     *
     * @param string $publicKey
     * @return string
     */
    public static function getCaptcha(string $publicKey = '') : string
    {
        resources::setJs(self::$reCaptchaScriptURL, false, true);
        return '<div class="g-recaptcha" data-sitekey="' . ($publicKey ?: (self::$reCaptchaPublicKey ?? '')). '"></div>';
    }

    /**
     * Валидация  каптчи.
     * Секретный ключ из параметра функции либо класса
     * Второй параметр - ключ сгенерированный каптчей при проверке, по умолчанию используется $_POST['g-recaptcha-response']
     *
     * @param string $privateKey
     * @param string|null $response
     * @return bool
     */
    public static function validateCaptcha(string $privateKey = '', string $response = null) : bool
    {
        if ($response === null) $response = $_POST['g-recaptcha-response'];
        $secret = $privateKey ?: (self::$reCaptchaPrivateKey ?? '');

        if (!$response || !$secret) {
            return false;
        }
        $recaptchaData = [
            'secret'    => $secret,
            'response'  =>  $response,
            'remoteip'  =>  self::getIP()
        ];
        return self::sendCURL(self::$reCaptchaVerifyURL, $recaptchaData,'POST');
    }

}