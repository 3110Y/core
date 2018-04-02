<?php
/**
 * Created by PhpStorm.
 * User: gaevoy
 * Date: 14.02.18
 * Time: 16:36
 */

namespace core\router;


/**
 * Class route
 * @package core\router
 */
class route
{
    /**
     * @var array
     */
    private $route;

    /**
     * @var string
     */
    private $controller;

    /**
     * @var string
     */
    private $URL;

    /**
     * @var string
     */
    private $function;

    /**
     * @var string
     */
    private $site;

    /**
     * @var string
     */
    private $theme;

    /**
     * @var string
     */
    private $method;

    /**
     * @var int
     */
    private $port;

    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $accessMode = 'allow';

    /**
     * @var array
     */
    private $accessGroup = [];

    /**
     * @var array
     */
    private $accessUser = [];


    /**
     * route constructor.
     * @param array $route
     */
    public function __construct(array $route)
    {
        $this->controller           =   $route['controller']    ??  '';
        $this->URL                  =   $route['url']           ??  '/';
        $this->function             =   $route['function']      ??  '';
        $this->site                 =   $route['site']          ??  '*';
        $this->theme                =   $route['theme']         ??  'basic';
        $this->method               =   $route['method']        ??  'GET';
        $this->port                 =   $route['port']          ??  80;
        $this->name                 =   $route['name']          ??  '';
        if (isset($route['access'])) {
            $this->accessMode   =   $route['access']['mode']  ??  'allow';
            $this->accessGroup  =   $route['access']['group'] ??  [];
            $this->accessUser   =   $route['access']['user']  ??  [];
        }
        $this->route                 =   $route          ??  Array();
    }

    /**
     * @return string
     */
    public function getController(): string
    {
        return $this->controller;
    }

    /**
     * @return string
     */
    public function getURL(): string
    {
        return $this->URL;
    }

    /**
     * @return string
     */
    public function getFunction(): string
    {
        return $this->function;
    }

    /**
     * @return string
     */
    public function getSite(): string
    {
        return $this->site;
    }

    /**
     * @return string
     */
    public function getTheme(): string
    {
        return $this->theme;
    }

    /**
     * @return string
     */
    public function getMethod(): string
    {
        return $this->method;
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
    public function getAccessMode(): string
    {
        return $this->accessMode;
    }

    /**
     * @return array
     */
    public function getAccessGroup(): array
    {
        return $this->accessGroup;
    }

    /**
     * @return array
     */
    public function getAccessUser(): array
    {
        return $this->accessUser;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return array
     */
    public function getRoute(): array
    {
        return $this->route;
    }
}