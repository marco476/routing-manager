<?php
namespace Helper;

class RoutingHelper extends RoutingHelperAbstract
{
    //Parse a XML file and transform it to PHP specific structure array
    public static function fromXmlToArray($routesXmlFile)
    {
        $routesFromXmlToArray = array();
        $xml = simplexml_load_string(file_get_contents($routesXmlFile));

        if (!$xml) {
            return $routesFromXmlToArray;
        }

        $jsonXmlDecode = json_decode(json_encode($xml), true);

        //Transform a Xml structure
        foreach ($jsonXmlDecode['node'] as $node) {
            if (empty($node['expression'])) {
                continue;
            }

            $nodeFormat = array();

            foreach (array_keys($node) as $key) {
                $nodeFormat[$key] = $node[$key];
            }

            $routesFromXmlToArray[] = $nodeFormat;
        }

        return $routesFromXmlToArray;
    }
}
