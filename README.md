[![Build Status](https://travis-ci.org/marco476/routing-manager.svg?branch=master)](https://travis-ci.org/marco476/routing-manager)
[![Packagist](https://img.shields.io/packagist/v/marco476/routing-manager.svg)](https://packagist.org/packages/marco476/routing-manager)
[![Code Climate](https://codeclimate.com/github/marco476/routing-manager/badges/gpa.svg)](https://codeclimate.com/github/marco476/routing-manager)
[![Issue Count](https://codeclimate.com/github/marco476/routing-manager/badges/issue_count.svg)](https://codeclimate.com/github/marco476/routing-manager)
[![PHP Version](https://img.shields.io/badge/PHP-%3E%3D5.6-blue.svg)](http://php.net/manual/en/migration56.new-features.php)
[![Packagist](https://img.shields.io/packagist/l/marco476/routing-manager.svg)](https://packagist.org/packages/marco476/routing-manager)

# PHP performance-oriented Routing manager
This library is PHP **performance-oriented routing manager** working with routes setted by: 

* PHP Array
* YAML file
* XML file

## Installation

You can install it with Composer:

```
composer require marco476/routing-manager
```

## Usage
Use the *routing-manager* library is extreme simple.
All routes setted **must** include an **expression** *key/tag* for matching with URI.

When you call **matchRoute** method, the library find a match between routes setted and URI present.
It return the **route matched array** if math with URI is Ok or **false** if not.

### Array
See an example with **array**:

```PHP
<?php
//Into web/index.php.
require_once "/vendor/autoload.php";

$Routing = new Routing();

$Routing->setRoutes(array(
    'homepage' => array( //Name route
        'expression'     => '/', //MUST DEFINE!
        'controller'=> 'MyController',
        'action'    => 'MyAction',
        'extra1'    => 'extra1',
        'extra2'    => 'extra2'
    )
));

if ($routeMatch = $Routing->matchRoute()) {
    echo "See my data!";
    var_dump($routeMatch);
} else {
    echo "mmm.. what's wrong?";
}
```

### YML
See an example with **YML**:

```PHP
<?php
//Into web/index.php.
require_once __DIR__ . '/../vendor/autoload.php';

$Routing = new Routing();

$Routing->setRoutesFromYml(__DIR__, 'routes.yml');

if ($routeMatch = $Routing->matchRoute()) {
    echo "See my data!";
    var_dump($routeMatch);
} else {
    echo "mmm.. what's wrong?";
}
```

And see a YML routes configuration file:

```YML
homepage: #Name route
    expression: "/" #MUST DEFINE!
    controller: "MyController"
    action:     "MyAction"
    params:     ["myFirstParameter", "sendMe"]
```

> Note: If you want use a YAML file routes configuration, you must install the yaml php extension. You can install it with *sudo apt-get install php-yaml* or with *PECL*. For detail, [see that](http://bd808.com/pecl-file_formats-yaml/)

### XML
See an example with **XML**:

```PHP
<?php
//Into web/index.php.
require_once __DIR__ . '/../vendor/autoload.php';

$Routing = new Routing();

$Routing->setRoutesFromXml(__DIR__, 'routes.xml');

if ($routeMatch = $Routing->matchRoute()) {
    echo "See my data!";
    var_dump($routeMatch);
} else {
    echo "mmm.. what's wrong?";
}
```

And see a XML routes configuration file:

```XML
<?xml version="1.0" encoding="UTF-8" ?>
<root>
  <node>
    <expression>/</expression>
    <controller>MyController</controller>
    <action>MyAction</action>
    <extra>Hello</extra>
  </node>
  <node>
    <expression>/contacts</expression>
    <controller>MyController2</controller>
    <params>Hello1</params>
    <extra>
      <name>Marco</name>
      <surname>Cante</surname>
    </extra>
  </node>
</root>
```

> Note: If you want use a XML file routes configuration, you must install the libxml php extension. You can [see that](http://php.net/manual/en/book.libxml.php)

## Wildcards
A wildcard is a jolly name inserting into **{}** (like *{idUser}*).
You can set wildcards into **expression** *key/tag* and set **a requirements array** *key/tag* for each one of them (see the example on bottom).

If **requirements** array is not present for a wildcard, it can be everything. For example:

```PHP
'expression'    => '/{myName}'
```

It can be everything:
* */marco*
* */123*
* */marco123*

If **requirements** array is present for a wildcard, you can use a custom regular expression or ExpressionRoute static
constant for *NUMERIC* or *STRING* (**only** in PHP array) strong expression. For example :

```PHP
'expression'    => '/{myName}',
'requirements'  => array(
    'myName'  => ExpressionRoute::STRING
    )
```

It can be:
* */marco*
* */wellName*

But not:
* */123*
* */marco123*

And with custom regular expression:

```PHP
'expression'    => '/{myName}',
'requirements'  => array(
    'myName'  => (marco|luigi)
    )
```

It can be:
* */marco*
* */luigi*

and not else!
See the examples on bottom:

### Wildcards in Array
See an example with **array**:

```PHP
<?php
//Into web/index.php.
require_once __DIR__ . '/../vendor/autoload.php';
use Helper\ExpressionRoute;

$Routing = new Routing();

$Routing->setRoutes(array(
    'homepage' => array(
        'expression'    => '/{wildcard}/{wildcard2}',
        'requirements'  => array(
            'wildcard'  => ExpressionRoute::NUMERIC,
            'wildcard2' => '(hello|bye)'
            ),
        'controller'    => 'MyController',
        'action'        => 'MyAction',
        'extra1'        => 'extra1',
    )
));

if ($routeMatch = $Routing->matchRoute()) {
    echo "See my data!";
    var_dump($routeMatch);
} else {
    echo "mmm.. what's wrong?";
}
```

### Wildcards in YML
See an example with **YML**:

```YML
homepage: 
    expression:   "/{test}"
    requirements:
        test:     "[a-zA-Z]+"
    controller:   "IndexController"
    action:       "showHomeAction"
    params:       ["myFirstParameter", "sendMe"]
```

### Wildcards in XML
See an example with **XML**:

```XML
<?xml version="1.0" encoding="UTF-8" ?>
<root>
  <node>
    <expression>/{test}</expression>
    <requirements>
      <test>(marco|luigi)</test>
    </requirements>
    <controller>MyController</controller>
    <action>MyAction</action>
  </node>
</root>
```

## Unit Test

You can run unit test from document root with:

```
vendor/bin/phpunit
```