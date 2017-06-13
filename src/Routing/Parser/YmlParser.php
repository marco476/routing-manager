<?php
namespace Routing\Parser;

class YmlParser implements parserInterface
{
	/**
	 * Parse a Yml file and transform it to PHP array
	 *
	 * @param string $routesXmlFile
	 * @return array
	 */
	public static function parse($routesYmlFile)
	{
		return yaml_parse_file($routesYmlFile);
	}
}