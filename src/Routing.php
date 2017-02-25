<?php
namespace Routing;

class Routing
{
    const NO_YAML_EXT = "The yaml php extension isn't install. You can install it with sudo apt-get install php-yaml or with PECL. For detail, see that: http://bd808.com/pecl-file_formats-yaml/ .";

    //Name of request URI.
    protected $requestURI;

    //List of all routes that can match with URI.
    protected $routes = array();

    public function __construct()
    {
        $this->requestURI = !empty($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : '/';
    }

    //Set routes by a YML route file.
    public function setRoutesFromYml($routePath, $routeFile)
    {
        if (!extension_loaded('yaml')) {
            trigger_error(self::NO_YAML_EXT, E_USER_ERROR);
        }

        $routesYmlFile = $routePath . '/' . $routeFile;

        return !is_dir($routePath) || !file_exists($routesYmlFile) ? false : $this->setRoutes(yaml_parse_file($routesYmlFile));
    }

    //Set routes by a array's routes.
    public function setRoutes(array $routes)
    {
        foreach ($routes as $route) {
            if (!empty($route['route'])) {
                $this->routes[] = $route;
            }
        }

        return $this;
    }

    //Return all routes setted.
    public function getRoutes()
    {
        return $this->routes;
    }

    //Match the URI from the routes setted.
    public function matchRoute()
    {
        foreach ($this->routes as $route) {
            if (preg_match('~^\\' . $route['route'] . '$~', $this->requestURI)) {
                return $route;
            }
        }

        return false;
    }
}
