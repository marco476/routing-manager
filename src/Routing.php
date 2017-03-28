<?php
use Helper\RoutingHelper;
use Helper\ExpressionRoute;

class Routing
{
    //Name of request URI.
    protected $requestURI;

    //List of all routes that can match with URI.
    protected $routes = array();

    public function __construct()
    {
        $this->requestURI = !empty($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : '/';
    }

    //You can set a custom URI to match with $routes
    public function setRequestUri($uri)
    {
        return $this->requestURI = $uri;
    }

    public function getRequestUri()
    {
        return $this->requestURI;
    }

    //Set routes by a YML routes file.
    public function setRoutesFromYml($routePath, $routeFile)
    {
        if (!extension_loaded('yaml')) {
            throw new \Exception(RoutingHelper::NO_YAML_EXT);
        }

        $routesYmlFile = $routePath . '/' . $routeFile;

        if (!is_dir($routePath) || !file_exists($routesYmlFile)) {
            throw new \Exception(RoutingHelper::YML_OR_XML_NO_DIR_OR_FILE);
        }

        //Filesize is necessary, because without it, if yml is empty
        //yaml_parse_file return a fatal error and not false!
        return filesize($routesYmlFile) ? $this->setRoutes(yaml_parse_file($routesYmlFile)) : $this;
    }

    //Set routes by a XML routes file.
    public function setRoutesFromXml($routePath, $routeFile)
    {
        if (!extension_loaded('libxml')) {
            throw new \Exception(RoutingHelper::NO_XML_EXT);
        }

        $routesXmlFile = $routePath . '/' . $routeFile;

        if (!is_dir($routePath) || !file_exists($routesXmlFile)) {
            throw new \Exception(RoutingHelper::YML_OR_XML_NO_DIR_OR_FILE);
        }

        $xmlArray = RoutingHelper::fromXmlToArray($routesXmlFile);
        
        return !empty($xmlArray) ? $this->setRoutes($xmlArray) : $this;
    }

    //Set routes internal array by an array of routes $routes.
    public function setRoutes(array $routes)
    {
        foreach ($routes as $route) {
            $expression = !empty($route['expression']) ? $route['expression'] : false;
            $requirements = !empty($route['requirements']) ? $route['requirements'] : false;

            if ($expression === false) {
                continue;
            }

            //Formatted expression.
            $route['expression'] = ExpressionRoute::getIstance()->formatExpression($expression, $requirements);
            $this->routes[] = $route;
        }

        return $this;
    }

    //Return all routes setted.
    public function getRoutes()
    {
        return $this->routes;
    }

    //Match the URI by routes setted.
    public function matchRoute()
    {
        foreach ($this->routes as $route) {
            if (preg_match('~^\\' . $route['expression'] . '$~', $this->requestURI)) {
                return $route;
            }
        }

        return false;
    }
}
