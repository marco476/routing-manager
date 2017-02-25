# PHP performance-oriented Routing manager
This library is **PHP performance-oriented routing manager** working with routes setted as:

* Array
* YAML file
* XML file (work in progress..)

## Installation

You can install it with Composer:

```
composer require marco476/routing-manager
```

## Usage
Use a *routing-manager* library is extreme simple.
See an example with array:

```PHP
<?php
//Into web/index.php.
require_once __DIR__ . '/../vendor/autoload.php';
use Routing\Routing;

$Routing = new Routing();

$routeMatch = $Routing->setRoutes(array(
    'homepage' => array( //Name route
        'route'     => '/', //MUST DEFINE!
        'controller'=> 'MyController',
        'action'    => 'MyAction',
        'extra1'    => 'extra1',
        'extra2'    => 'extra2'
    )
))->matchRoute();

if ($routeMatch) {
    echo "what will you do?";
} else {
    echo "mmm.. what's wrong?";
}
```

See an example with YML:

```PHP
<?php
//Into web/index.php.
require_once __DIR__ . '/../vendor/autoload.php';
use Routing\Routing;

$Routing = new Routing();

$routeMatch = $Routing->setRoutesFromYml(__DIR__, 'routes.yml')->matchRoute();

if ($routeMatch) {
    echo "what will you do?";
} else {
    echo "mmm.. what's wrong?";
}
```

And see a YML routes configuration file:

```YML
homepage: #Name route
    route:      / #MUST DEFINE!
    controller: MyController
    action:     MyAction 
    extra1:     extra1
    extra2:     extra2
```

All routes setted **must** include a **route** array *key* for matching the URI.

When you call **matchRoute** method, the library find a match with routes setted and URI.
It return the **route matched** array if math with URI is Ok or **false** if not.

> Note: If you want use a YAML file routes configuration, you must install the yaml php extension. You can install it with *sudo apt-get install php-yaml* or with *PECL*. For detail, [see that](http://bd808.com/pecl-file_formats-yaml/)
