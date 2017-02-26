<?php
namespace Routing;

class ErrorHelper
{
    const NO_YAML_EXT = "The yaml php extension isn't install. You can install it with sudo apt-get install php-yaml or with PECL. For detail, see that: http://bd808.com/pecl-file_formats-yaml/ .";
    const YML_NO_DIR_OR_FILE = "The path or file passed to setRoutesFromYml not exist.";
}
