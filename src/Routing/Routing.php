<?php
namespace Routing;

use Routing\Exception\RoutingException;
use Routing\Exception\ExceptionMessage;

use Routing\Parser\XmlParser;
use Routing\Parser\YmlParser;
use Helper\Expression\Formatter;

class Routing
{
	/**
	 * Name of request   URI.
	 *
	 * @var string
	 */
	protected $requestURI;

	/**
	 * List of all routes that can match with URI.
	 *
	 * @var array
	 */
	protected $routes = array();

	/**
	 * Constructor
	 */
	public function __construct()
	{
		$this->requestURI = !empty($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : '/';
	}

	/**
	 * Set a custom URI to match with $routes
	 *
	 * @param string $uri
	 * @return string
	 */
	public function setRequestUri($uri)
	{
		return $this->requestURI = $uri;
	}

	/**
	 * Get Request URI
	 *
	 * @return string
	 */
	public function getRequestUri()
	{
		return $this->requestURI;
	}

	/**
	 * Set routes by a YML routes file.
	 *
	 * @param string $routePath
	 * @param string $routeFile
	 * @return bool
	 */
	public function setRoutesFromYml($routePath, $routeFile)
	{
		if (!extension_loaded('yaml')) {
			throw new RoutingException(ExceptionMessage::NO_YAML_EXT);
		}

		$routesYmlFile = $routePath . '/' . $routeFile;

		if (!file_exists($routesYmlFile)) {
			throw new RoutingException(ExceptionMessage::YML_OR_XML_NO_DIR_OR_FILE);
		}

		//Filesize is necessary, because without it, if yml is empty
		//yaml_parse_file return a fatal error and not false!
		return 
			filesize($routesYmlFile) && 
			!empty($ymlArray = YmlParser::parse($routesYmlFile)) &&
			$this->setRoutes($ymlArray);
	}

	/**
	 * Set routes by a XML routes file.
	 *
	 * @param string $routePath
	 * @param string $routeFile
	 * @return bool
	 */
	public function setRoutesFromXml($routePath, $routeFile)
	{
		if (!extension_loaded('libxml')) {
			throw new RoutingException(ExceptionMessage::NO_XML_EXT);
		}

		$routesXmlFile = $routePath . '/' . $routeFile;

		if (!file_exists($routesXmlFile)) {
			throw new RoutingException(ExceptionMessage::YML_OR_XML_NO_DIR_OR_FILE);
		}

		return 
			!empty($xmlArray = XmlParser::parse($routesXmlFile)) &&
			$this->setRoutes($xmlArray);
	}

	/**
	 * Set routes by array $routes.
	 *
	 * @param array $routes
	 * @return bool
	 */
	public function setRoutes(array $routes)
	{
		if(empty($routes)){
			return false;
		}

		$Formatter = new Formatter();

		foreach ($routes as $route) {
			$expression = !empty($route['expression']) ? $route['expression'] : false;
			$requirements = !empty($route['requirements']) ? $route['requirements'] : false;

			if ($expression === false) {
				continue;
			}

			//Formatted expression.
			$route['expression'] = $Formatter->formatExpression($expression, $requirements);
			$this->routes[] = $route;
		}

		return true;
	}

	/**
	 * Return all routes setted.
	 *
	 * @return array
	 */
	public function getRoutes()
	{
		return $this->routes;
	}

	/**
	 * Match the URI by routes setted.
	 *
	 * @return array|bool
	 */
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
