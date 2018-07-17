<?php
/**
 * Created by PhpStorm.
 * User: Евгений
 * Date: 06.03.2018
 * Time: 11:10
 */

namespace core\component\helper;


trait realIP
{
    /**
     * Возращает реальный ip пользователя
     *
     * @return string
     */
    private static function getIP() : string
    {
        $client  = @$_SERVER['HTTP_CLIENT_IP'] ?? '';
        $forward = @$_SERVER['HTTP_X_FORWARDED_FOR'] ?? '';
        $remote  = $_SERVER['REMOTE_ADDR'] ?? '';

        if(\filter_var($client, FILTER_VALIDATE_IP))
        {
            $ip = $client;
        }
        elseif(\filter_var($forward, FILTER_VALIDATE_IP))
        {
            $ip = $forward;
        }
        elseif(\filter_var($remote, FILTER_VALIDATE_IP))
        {
            $ip = $remote;
        }
        else {
            $ip = '127.0.0.1';
        }

        return $ip;
    }


}