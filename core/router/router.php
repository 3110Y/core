<?php
/**
 * Created by PhpStorm.
 * User: gaevoy
 * Date: 14.02.18
 * Time: 15:36
 */

namespace core\router;

/**
 * Class router
 * @package core\router
 */
class router
{
    /**
     * @var array
     */
    private $route = [];

    /**
     * @var int
     */
    private $port = 80;

    /**
     * @var string
     */
    private $method = '';

    /**
     * @var string
     */
    private $site = '';


    /**
     * @param array $structure
     * @return router
     */
    public function addStructure(array $structure): router
    {
        $structureNew = [];
        foreach ($structure as $route) {
            if (isset($route['site']) && \is_array($route['site'])) {
                foreach ($route['site'] as $site) {
                    $routeClone             =    $route;
                    $routeClone['site']     = $site;
                    $structureNew[]         = $routeClone;
                }
            } else {
                $structureNew[] = $route;
            }
        }
        $structure = [];
        foreach ($structureNew as $route) {
            if (isset($route['port']) && \is_array($route['port'])) {
                foreach ($route['port'] as $site) {
                    $routeClone             =    $route;
                    $routeClone['port']     = $site;
                    $structure[]            = $routeClone;
                }
            } else {
                $structure[] = $route;
            }
        }
        $structureNew = [];
        foreach ($structure as $route) {
            if (isset($route['method']) && \is_array($route['method'])) {
                foreach ($route['method'] as $site) {
                    $routeClone                 =    $route;
                    $routeClone['method']     =  $site;
                    $structureNew[]             = $routeClone;
                }
            } else {
                $structureNew[] = $route;
            }
        }
        foreach ($structureNew as $route) {
            $this->add(new route($route));
        }
        return $this;
    }

    /**
     * @param route $route
     */
    public function add(route $route): void
    {
        $this->route[] = $route;
    }

    /**
     * @return mixed|bool|object
     */
    public  function execute()
    {
        /** @var \core\router\route $route */
        foreach ($this->route as $route) {
            if (!$this->checkPort($route->getPort())) {
                continue;
            }
            if (!$this->checkSite($route->getSite())) {
                continue;
            }
            if (!$this->checkMethod($route->getMethod())) {
                continue;
            }
            if (!$this->checkURL($route->getURL())) {
                continue;
            }
            $controller = $route->getController();
            $function = $route->getFunction();
            if ($function !== '') {
                return (new $controller($route))->$function();
            }
            return $controller;
        }
        return false;
    }

    /**
     * @return int
     */
    public function getPort(): int
    {
        return $this->port;
    }

    /**
     * @param int $port
     * @return router
     */
    public function setPort(int $port): router
    {
        $this->port = $port;
        return $this;
    }

    /**
     * @param int $port
     * @return bool
     */
    private function checkPort(int $port): bool
    {
        return $this->port === $port || $this->port === '' || $port === '';
    }



    /**
     * @param string $URL
     * @return bool
     */
    private function checkURL(string $URL): bool
    {
        if ($URL === '' ) {
            return true;
        }
        return URL::getURLPointerNow() === $URL || $URL === '';

    }

    /**
     * @return string
     */
    public function getMethod(): string
    {
        return $this->method;
    }

    /**
     * @param string $method
     * @return router
     */
    public function setMethod(string $method): router
    {
        $this->method = $method;
        return $this;
    }

    /**
     * @param string $method
     * @return bool
     */
    private function checkMethod(string $method): bool
    {
        return $this->method === $method || $this->method === '' || $method === '';
    }

    /**
     * @return array
     */
    public function getSite(): array
    {
        return $this->site;
    }

    /**
     * @param string $site
     * @return router
     */
    public function setSite(string $site): router
    {
        $this->site = $site;
        return $this;
    }

    /**
     * @param string $site
     * @return bool
     */
    private  function checkSite(string $site): bool
    {
        $replace    =   Array(
            '*'  =>  '([\w]+)',
            '/'  =>  '\/',
        );
        $siteRegular   =  '/^' . strtr($site, $replace) . '$/i';
        preg_match($siteRegular, $this->site, $output);
        return isset($output[0]) ||  $site === '' || $this->site === '';
    }
}
