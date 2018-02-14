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
    private static $route = [];

    /**
     * @var int
     */
    private static $port = 80;

    /**
     * @var array
     */
    private static $URI = [];

    /**
     * @var string
     */
    private static $method = '';

    /**
     * @var string
     */
    private static $site = '';


    /**
     * @param array $structure
     */
    public static function addStructure(array $structure): void
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
            self::add(new route($route));
        }
    }

    /**
     * @param route $route
     */
    public static function add(route $route): void
    {
        self::$route[] = $route;
    }

    /**
     * @return mixed|bool|object
     */
    public static function execute()
    {
        /** @var \core\router\route $route */
        foreach (self::$route as $route) {
            if (!self::checkPort($route->getPort())) {
                echo '<pre>';
                var_dump('checkPort');
                var_dump($route->getController());
                echo '</pre>';
                continue;
            }
            if (!self::checkSite($route->getSite())) {
                echo '<pre>';
                var_dump('checkSite');
                var_dump($route->getController());
                echo '</pre>';
                continue;
            }
            if (!self::checkMethod($route->getMethod())) {
                echo '<pre>';
                var_dump('checkMethod');
                var_dump($route->getController());
                echo '</pre>';
                continue;
            }
            if (!self::checkURI($route->getURI())) {
                echo '<pre>';
                var_dump('checkURI');
                var_dump($route->getController());
                echo '</pre>';
                continue;
            }
            $controller = $route->getController();
            $function = $route->getFunction();
            if ($function !== '') {
                return (new $controller())->$function();
            }
            return $controller;
        }
        return false;
    }

    /**
     * @return int
     */
    public static function getPort(): int
    {
        return self::$port;
    }

    /**
     * @param int $port
     */
    public static function setPort(int $port): void
    {
        self::$port = $port;
    }

    /**
     * @param int $port
     * @return bool
     */
    private static function checkPort(int $port): bool
    {
        return self::$port === $port || self::$port === '' || $port === '';
    }

    /**
     * @return array
     */
    public static function getURI(): array
    {
        return self::$URI;
    }

    /**
     * @param array $URI
     */
    public static function setURI(array $URI): void
    {
        self::$URI = $URI;
    }

    /**
     * @param string $URI
     * @return bool
     */
    private static function checkURI(string $URI): bool
    {
        if ($URI === '' ) {
            return true;
        }
        ['/'];
        ['/', 'admin'];
        ['admin'];
        foreach (self::$URI as $URL) {
            var_dump($URI, $URL);
            if (self::$URI === $URL) {
               return true;
            }
        }
    }

    /**
     * @return string
     */
    public static function getMethod(): string
    {
        return self::$method;
    }

    /**
     * @param string $method
     */
    public static function setMethod(string $method): void
    {
        self::$method = $method;
    }

    /**
     * @param string $method
     * @return bool
     */
    private static function checkMethod(string $method): bool
    {
        return self::$method === $method || self::$method === '' || $method === '';
    }

    /**
     * @return array
     */
    public static function getSite(): array
    {
        return self::$site;
    }

    /**
     * @param string $site
     */
    public static function setSite(string $site): void
    {
        self::$site = $site;
    }

    /**
     * @param string $site
     * @return bool
     */
    private static function checkSite(string $site): bool
    {
        $replace    =   Array(
            '*'  =>  '([\w]+)',
            '/'  =>  '\/',
        );
        $siteRegular   =  '/^' . strtr($site, $replace) . '$/i';
        preg_match($siteRegular, self::$site, $output);
        return isset($output[0]) ||  $site === '' || self::$site === '';
    }
}