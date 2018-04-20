<?php
/**
 * Created by PhpStorm.
 * User: gaevoy
 * Date: 20.03.18
 * Time: 16:32
 */

namespace Core\URI;

/**
 * Class URI
 * @package core\URI
 */
class URI
{
    /**
     * @var string
     */
    private $schema;

    /**
     * @var string
     */
    private $host;

    /**
     * @var int
     */
    private $port;

    /**
     * @var string
     */
    private $user;

    /**
     * @var string
     */
    private $pass;

    /**
     * @var string
     */
    private $path;

    /**
     * @var string
     */
    private $query;

    /**
     * @var string
     */
    private $fragment;

    /**
     * @var mixed|bool|null AJAX запрос
     */
    private static $isAjaxRequest;

    /**
     * @var array
     */
    private static $instance = [];

    /**
     * @param string $url
     * @return URI
     * @throws \InvalidArgumentException
     */
    public static function getInstance(string $url = ''): URI
    {
        if ($url === '') {
            $url = $_SERVER['REQUEST_URI'];
        }
        $key    =   md5($url);
        if (!isset(self::$instance[$key])) {
            self::$instance[$key] = new self($url);
        }
        return self::$instance[$key];
    }

    /**
     * URI constructor.
     * @param $url
     * @throws \InvalidArgumentException
     */
    private function __construct($url)
    {
        $URI = parse_url($url);
        if ($URI === false) {
            throw new \InvalidArgumentException('Malformed URL: ' . $url);
        }
        $this->schema   = $URI['scheme']    ??  '';
        $this->host     = $URI['host']      ??  '';
        $this->port     = $URI['port']      ??  0;
        $this->user     = $URI['user']      ??  '';
        $this->pass     = $URI['pass']      ??  '';
        $this->path     = $URI['path']      ??  '';
        $this->query    = $URI['query']     ??  '';
        $this->fragment = $URI['fragment']  ??  '';
    }


    /**
     * @return string
     */
    public function getSchema(): string
    {
        return $this->schema;
    }

    /**
     * @return string
     */
    public function getHost(): string
    {
        return $this->host;
    }

    /**
     * @return int
     */
    public function getPort(): int
    {
        return $this->port;
    }

    /**
     * @return string
     */
    public function getUser(): string
    {
        return $this->user;
    }

    /**
     * @return string
     */
    public function getPass(): string
    {
        return $this->pass;
    }

    /**
     * @return string
     */
    public function getPath(): string
    {
        return $this->path;
    }

    /**
     * @return string
     */
    public function getQuery(): string
    {
        return $this->query;
    }

    /**
     * @return string
     */
    public function getFragment(): string
    {
        return $this->fragment;
    }

    /**
     * Проверяет запрос на аяксовость
     * @return bool
     */
    public static function isAjaxRequest(): bool
    {
        if (self::$isAjaxRequest === null) {
            self::$isAjaxRequest = (
                isset($_SERVER['HTTP_REFERER'], $_SERVER['HTTP_X_REQUESTED_WITH']) &&
                $_SERVER['HTTP_REFERER'] !== '' &&
                strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest'
            );
        }
        return self::$isAjaxRequest;
    }

    /**
     * переадресация
     * @param string $url URL
     * @param boolean $isExternal внешний адресс
     */
    public static function redirect($url, $isExternal = false) : void
    {
        if ($isExternal === false && isset($_SERVER['HTTP_HOST'])) {
            $protocol   = $_SERVER['HTTP_X_FORWARDED_PROTO'] ?? 'http';
            $url        =   $protocol . '://' .$_SERVER['HTTP_HOST'] . $url;
        }
        header("Location: {$url}");
        exit;
    }

}
