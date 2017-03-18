[![Build Status](https://travis-ci.org/marco476/routing-manager.svg?branch=master)](https://travis-ci.org/marco476/routing-manager)
[![Packagist](https://img.shields.io/packagist/v/marco476/routing-manager.svg)](https://packagist.org/packages/marco476/routing-manager)
[![Packagist](https://img.shields.io/packagist/l/marco476/routing-manager.svg)](https://packagist.org/packages/marco476/routing-manager)
[![Code Climate](https://codeclimate.com/github/marco476/routing-manager/badges/gpa.svg)](https://codeclimate.com/github/marco476/routing-manager)
[![Issue Count](https://codeclimate.com/github/marco476/routing-manager/badges/issue_count.svg)](https://codeclimate.com/github/marco476/routing-manager)
[![PHP Version](https://img.shields.io/badge/PHP-%3E%3D5.6-blue.svg)](http://php.net/manual/en/migration56.new-features.php)

# PHP performance-oriented Routing manager
This library is PHP **performance-oriented routing manager** working with routes setted in many ways: 

* PHP Array
* YAML file
* XML file

## Installation

You can install it with Composer:

```
composer require marco476/routing-manager
```

## Usage
Use a *routing-manager* library is extreme simple.
See an example with **array**:

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

See an example with **YML**:

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

> Note: If you want use a YAML file routes configuration, you must install the yaml php extension. You can install it with *sudo apt-get install php-yaml* or with *PECL*. For detail, [see that](http://bd808.com/pecl-file_formats-yaml/)

See an example with **XML**:

```PHP
<?php
//Into web/index.php.
require_once __DIR__ . '/../vendor/autoload.php';
use Routing\Routing;

$Routing = new Routing();

$routeMatch = $Routing->setRoutesFromXml(__DIR__, 'routes.xml')->matchRoute();

if ($routeMatch) {
    echo "Now I call {$routeMatch['controller']} controller!";
} else {
    echo "mmm.. what's wrong?";
}
```

And see a XML routes configuration file:

```XML
<?xml version="1.0" encoding="UTF-8" ?>
<root>
	<node>
		<route>/</route>
		<controller>MyController</controller>
		<action>MyAction</action>
		<extra>Hello</extra>
	</node>
	<node>
		<route>/contacts</route>
		<controller>MyController2</controller>
		<params>Hello1</params>
		<extra>Hello2</extra>
	</node>
</root>
```

> Note: If you want use a XML file routes configuration, you must install the libxml php extension. You can [see that](http://php.net/manual/en/book.libxml.php)

All routes setted **must** include a **route** *key/tag* for matching the URI.

When you call **matchRoute** method, the library find a match with routes setted and URI present.
It return the **route matched** array if math with URI is Ok or **false** if not.
