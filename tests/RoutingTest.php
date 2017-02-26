<?php
use PHPUnit\Framework\TestCase;
use Routing\Routing;
use Routing\ErrorHelper;

class RoutingTest extends TestCase
{
    //When I get a new Routing istance, the routes
    //aren't setted.
    public function testRoutesEmptyOnStart()
    {
        $expect = array();

        $Routing = new Routing();
        $resultRoutes = $Routing->getRoutes();

        $this->assertEquals($expect, $resultRoutes);
    }

    /* ------------------------------------------
                setRoutes METHOD TESTS!
       ------------------------------------------ */

    //An empty array for setRoutes not set anything.
    public function testEmptySetRoutes()
    {
        $expect = array();

        $Routing = new Routing();
        $resultRoutes = $Routing->setRoutes(array())->getRoutes();

        $this->assertEquals($expect, $resultRoutes);
    }

    //Test setRoutes with one valid route.
    public function testSetOneRouteValid()
    {
        $input = array(
            'homepage' => array(
                'route' => '/',
                'customController' => 'IndexController',
                'customAction' => 'showHomeAction'
            )
        );

        $expect = array(
            0 => array(
                'route' => '/',
                'customController' => 'IndexController',
                'customAction' => 'showHomeAction'
            )
        );

        $Routing = new Routing();
        $resultRoutes = $Routing->setRoutes($input)->getRoutes();

        $this->assertEquals($expect, $resultRoutes);
    }

    //Test setRoutes with same routes not valid.
    public function testSetRoutesNotAllValid()
    {
        $input = array(
            'homepage' => array(
                'route' => '/',
                'customController' => 'IndexController',
                'customAction' => 'showHomeAction'
            ),
            'contacts' => array(
                //No route key!
                'customController' => 'ContattiController',
                'customAction' => 'showContattiAction'
            ),
            'map' => array(
                'route' => '/map',
            ),
            'other' => array(
                //No route key!
                'params' => 'specialParam',
                'iDontKnow' => 'bah!'
            ),
        );

        $expect = array(
            0 => array(
                'route' => '/',
                'customController' => 'IndexController',
                'customAction' => 'showHomeAction'
            ),
            1 => array(
                'route' => '/map'
            )
        );

        $Routing = new Routing();
        $resultRoutes = $Routing->setRoutes($input)->getRoutes();

        $this->assertEquals($expect, $resultRoutes);
    }

    //Test setRoutes with all routes valid.
    public function testSetRoutesAllValid()
    {
        $input = array(
            'homepage' => array(
                'route' => '/homepage',
                'hello' => 'hi!'
            ),
            'contacts' => array(
                'route' => '/contacts',
                'customController' => 'ContattiController',
                'customAction' => 'showContattiAction'
            ),
            'map' => array(
                'route' => '/map',
            )
        );

        $expect = array(
            0 => array(
                'route' => '/homepage',
                'hello' => 'hi!'
            ),
            1 => array(
                'route' => '/contacts',
                'customController' => 'ContattiController',
                'customAction' => 'showContattiAction'
            ),
            2 => array(
                'route' => '/map'
            )
        );

        $Routing = new Routing();
        $resultRoutes = $Routing->setRoutes($input)->getRoutes();

        $this->assertEquals($expect, $resultRoutes);
    }

    /* ------------------------------------------
            setRoutesFromYml METHOD TESTS!
       ------------------------------------------ */

    public function testYmlExtensionSetRoutesFromYml()
    {
        $extension = extension_loaded('yaml');

        if (!$extension) {
            $expectBool = false;
            $Routing = new Routing();

            $dirName = '/subDir/dirExample';
            $ymlFile = 'ymlExample.yml';

            $this->expectExceptionMessage(ErrorHelper::NO_YAML_EXT);
            $Routing->setRoutesFromYml($dirName, $ymlFile);
        } else {
            $expectBool = true;
        }

        $this->assertEquals($expectBool, $extension);
    }

    public function testNotFoudDirSetRoutesFromYml()
    {
        if (extension_loaded('yaml')) {
            $dirName = '/subDir/dirNotExist';
            $ymlFile = 'ymlExample.yml';

            $Routing = new Routing();

            $this->expectExceptionMessage(ErrorHelper::YML_NO_DIR_OR_FILE);
            $Routing->setRoutesFromYml($dirName, $ymlFile);
        } else {
            print "You must enable YAML PHP extension for start this method's test.\n 
                You can install it with sudo apt-get install php-yaml or with PECL. For detail, see that: http://bd808.com/pecl-file_formats-yaml/ .";
        }
    }

    public function testNotFoudFileSetRoutesFromYml()
    {
        if (extension_loaded('yaml')) {
            $dirName = __DIR__;
            $ymlFile = 'ymlExample.yml';

            $Routing = new Routing();

            $this->expectExceptionMessage(ErrorHelper::YML_NO_DIR_OR_FILE);
            $Routing->setRoutesFromYml($dirName, $ymlFile);
        } else {
            print "You must enable YAML PHP extension for start this method's test.\n 
                You can install it with sudo apt-get install php-yaml or with PECL. For detail, see that: http://bd808.com/pecl-file_formats-yaml/ .";
        }
    }

    public function validSetRoutesFromYml()
    {
        if (extension_loaded('yaml')) {
            $dirName = __DIR__;
            $ymlFile = 'routesTest.yml';

            $expect = array(
                0 => array(
                    'route' => '/',
                    'controller' => 'IndexController',
                    'action' => 'showHomeAction',
                    'params' => 'extraParams'
                )
            );

            $Routing = new Routing();
            $Routing->setRoutesFromYml($dirName, $ymlFile);

            $this->assertEquals($expect, $Routing->getRoutes());
        } else {
            print "You must enable YAML PHP extension for start this method's test.\n 
                You can install it with sudo apt-get install php-yaml or with PECL. For detail, see that: http://bd808.com/pecl-file_formats-yaml/ .";
        }
    }
}
