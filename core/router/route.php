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
     * @var string
     */
    private $controller;

    /**
     * @var string
     */
    private $uri;

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
     * route constructor.
     * @param array $route
     */
    public function __construct(array $route)
    {
        $this->controller           =   $route['controller']    ??  '';
        $this->uri                  =   $route['uri']           ??  '/';
        $this->function             =   $route['function']      ??  '/';
        $this->site                 =   $route['site']          ??  '*.*';
        $this->theme                =   $route['theme']         ??  'basic';
        $this->method               =   $route['method']        ??  'GET';
        $this->port                 =   $route['port']          ??  80;
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
    public function getURI(): string
    {
        return $this->uri;
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
}