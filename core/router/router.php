<?php
/**
 * Created by PhpStorm.
 * User: gaevoy
 * Date: 14.02.18
 * Time: 15:36
 */

namespace core\router;


use core\URI\URL;

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
            return new $controller($route);
        }
        return false;
    }

    /**
     * @param int $port
     * @return bool
     */
    private function checkPort(int $port): bool
    {
        return  !isset($_SERVER['SERVER_PORT']) ||
            $_SERVER['SERVER_PORT'] === '' ||
            (int)$_SERVER['SERVER_PORT'] === (int)$port ||
            $port === '';
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
        $URLPointer =   URL::getURLPointerNow();
        $replace    =   Array(
            '*'  =>  '([\w]+)',
            '/'  =>  '\/',
        );
        $URLRegular   =  '/^' . strtr($URL, $replace) . '$/i';
        preg_match($URLRegular, $URLPointer, $output);
        return isset($output[0]) || $URLPointer === '';
    }

    /**
     * @param string $method
     * @return bool
     */
    private function checkMethod(string $method): bool
    {
        return !isset($_SERVER['REQUEST_METHOD']) ||
            $_SERVER['REQUEST_METHOD'] === '' ||
            $_SERVER['REQUEST_METHOD'] === $method ||
            $method === '';
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
        if (!isset($_SERVER['HTTP_HOST'])) {
            return true;
        }
        $replace    =   Array(
            '*'  =>  '([\w]+)',
            '/'  =>  '\/',
        );
        $siteRegular   =  '/^' . strtr($site, $replace) . '$/i';
        preg_match($siteRegular, $_SERVER['HTTP_HOST'], $output);
        return isset($output[0]) ||  $site === '' || $_SERVER['HTTP_HOST'] === '';
    }
}
