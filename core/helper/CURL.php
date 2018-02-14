<?php
/**
 * Created by PhpStorm.
 * User: Roman
 * Date: 16.01.2018
 * Time: 19:48
 */

namespace core\helper;

/**
 * Trait CURL
 * @package core\helper
 * @version 1.1
 */
trait CURL
{
    /**
     * Добавляет отправку CURL
     *
     * @param string $url
     * @param mixed|array|string $fields
     * @param string $method
     * @param array $opt
     *
     * @example class myClass { use \core\helper\CURL; }
     *
     * @access public
     * @static
     *
     * @return mixed
     */
    public static function sendCURL(string $url, $fields, string $method = 'MIXED', array $opt = Array())
    {
        $ch = curl_init();
        switch ($method) {
            case 'POST':
                if (!isset($opt[CURLOPT_POST])) {
                    $opt[CURLOPT_POST] = 1;
                }
                break;
            case 'GET':
                $url = $url . "?" . http_build_query($fields);
                break;
            case 'JSON':
                $fields =   json_encode($fields);
                if (!isset($opt[CURLOPT_HTTPHEADER])) {
                    $opt[CURLOPT_HTTPHEADER] = array(
                        'Content-Type: application/json',
                        'Content-Length: ' . strlen($fields)
                    );
                }
                break;
            default:
                break;
        }
        if (!isset($opt[CURLOPT_URL])) {
            $opt[CURLOPT_URL] = $url;
        }
        if (!isset($opt[CURLOPT_RETURNTRANSFER])) {
            $opt[CURLOPT_RETURNTRANSFER] = true;
        }
        if (!isset($opt[CURLOPT_SSL_VERIFYPEER])) {
            $opt[CURLOPT_SSL_VERIFYPEER] = 0;
        }
        if (!isset($opt[CURLOPT_FOLLOWLOCATION])) {
            $opt[CURLOPT_FOLLOWLOCATION] = true;
        }
        foreach ($opt as $key => $value) {
            curl_setopt($ch, $key, $value);
        }

        curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
        return curl_exec($ch);
    }
}