<?php
namespace Routing;

class RoutingHelper
{
    const NO_YAML_EXT = "The yaml php extension isn't install. You can install it with sudo apt-get install php-yaml or with PECL. For detail, see that: http://bd808.com/pecl-file_formats-yaml/ .";
    const NO_XML_EXT = "You must enable libxml extension for start this method's test.\nFor detail, see that: http://php.net/manual/en/book.libxml.php .";
    const YML_OR_XML_NO_DIR_OR_FILE = "The path or file passed to setRoutesFromYml or setRoutesFromXml not exist.";

    //Parse a XML file and transform it to PHP specific structure array
    public static function fromXmlToArray($routesXmlFile)
    {
        $routesFromXmlToArray = array();
        $xml = simplexml_load_string(file_get_contents($routesXmlFile), "SimpleXMLElement", LIBXML_NOCDATA);

        if (!$xml) {
            return $routesFromXmlToArray;
        }

        $jsonXmlDecode = json_decode(json_encode($xml), true);

        //Transform a Xml structure into this library structure like
        foreach ($jsonXmlDecode['node'] as $node) {
            if (empty($node['route'])) {
                continue;
            }

            $nodeName = !empty($node['name']) ? $node['name'] : 'unknow';
            $nodeFormat = array();

            foreach (array_keys($node) as $key) {
                $nodeFormat[$key] = $node[$key];
            }

            $routesFromXmlToArray[$nodeName] = $nodeFormat;
        }

        return $routesFromXmlToArray;
    }
}
