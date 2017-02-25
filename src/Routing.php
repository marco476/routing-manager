<?php
namespace Routing;

class Routing
{
    const ROUTES_NO_SETTED = "Routes aren't setted.";

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
        if (!empty($this->routes)) {
            return false;
        }

        if (!extension_loaded('yaml')) {
            trigger_error("The yaml php extension not install. You can install it with sudo apt-get install php-yaml or with PECL. For detail, see that: http://bd808.com/pecl-file_formats-yaml/ .", E_USER_ERROR);
        }

        if (!is_dir($routePath)) {
            $this->createPathRoute($routePath);
        }

        $routesYmlFile = $routePath . '/' . $routeFile;

        if (!file_exists($routesYmlFile)) {
            trigger_error("The {$routesYmlFile} file not exist.", E_USER_ERROR);
        }

        if (empty($parseYml = yaml_parse_file($routesYmlFile))) {
            trigger_error(self::ROUTES_NO_SETTED, E_USER_ERROR);
        }

        return $this->setRoutes($parseYml);
    }

    //Set routes by a array's routes.
    public function setRoutes(array $routes)
    {
        if (!empty($this->routes)) {
            return false;
        }

        if (empty($routes)) {
            trigger_error(self::ROUTES_NO_SETTED, E_USER_ERROR);
        }

        foreach ($routes as $route) {
            if (!empty($route['route'])) {
                $this->routes[] = $route;
            }
        }

        if (empty($this->routes)) {
            trigger_error("No routes are valid.", E_USER_ERROR);
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
