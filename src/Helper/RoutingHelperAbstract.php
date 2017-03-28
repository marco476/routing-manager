<?php
namespace Helper;

abstract class RoutingHelperAbstract
{
    const NO_YAML_EXT = "The yaml php extension isn't install. You can install it with sudo apt-get install php-yaml or with PECL. For detail, see that: http://bd808.com/pecl-file_formats-yaml/ .";
    const NO_XML_EXT = "You must enable libxml extension for start this method's test.\nFor detail, see that: http://php.net/manual/en/book.libxml.php .";
    const YML_OR_XML_NO_DIR_OR_FILE = "The path or file passed to setRoutesFromYml or setRoutesFromXml not exist.";
}
