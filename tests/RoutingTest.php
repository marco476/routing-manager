<?php
use PHPUnit\Framework\TestCase;
use Routing\Routing;
use Helper\RoutingHelper;

class RoutingTest extends TestCase
{
    const MSG_ERROR_YML_EXTENSION = "You must enable YAML PHP extension for start this method's test.\n You can install it with sudo apt-get install php-yaml or with PECL. For detail, see that: http://bd808.com/pecl-file_formats-yaml/ .";
    const MSG_ERROR_XML_EXTENSION = "You must enable libxml extension for start this method's test.\nFor detail, see that: http://php.net/manual/en/book.libxml.php .";

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
        $Routing->setRoutes(array());

        $this->assertEquals($expect, $Routing->getRoutes());
    }

    //Test setRoutes with one valid route.
    public function testSetOneRouteValid()
    {
        $input = array(
            'homepage' => array(
                'expression' => '/',
                'customController' => 'IndexController',
                'customAction' => 'showHomeAction'
            )
        );

        $expect = array(
            0 => array(
                'expression' => '/',
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
                'expression' => '/',
                'customController' => 'IndexController',
                'customAction' => 'showHomeAction'
            ),
            'contacts' => array(
                //No route key!
                'customController' => 'ContattiController',
                'customAction' => 'showContattiAction'
            ),
            'map' => array(
                'expression' => '/map',
            ),
            'other' => array(
                //No route key!
                'params' => 'specialParam',
                'iDontKnow' => 'bah!'
            ),
        );

        $expect = array(
            0 => array(
                'expression' => '/',
                'customController' => 'IndexController',
                'customAction' => 'showHomeAction'
            ),
            1 => array(
                'expression' => '/map'
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
                'expression' => '/homepage',
                'hello' => 'hi!'
            ),
            'contacts' => array(
                'expression' => '/contacts',
                'customController' => 'ContattiController',
                'customAction' => 'showContattiAction'
            ),
            'map' => array(
                'expression' => '/map',
            )
        );

        $expect = array(
            0 => array(
                'expression' => '/homepage',
                'hello' => 'hi!'
            ),
            1 => array(
                'expression' => '/contacts',
                'customController' => 'ContattiController',
                'customAction' => 'showContattiAction'
            ),
            2 => array(
                'expression' => '/map'
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

            $this->expectExceptionMessage(RoutingHelper::NO_YAML_EXT);
            $Routing->setRoutesFromYml($dirName, $ymlFile);
        } else {
            $expectBool = true;
        }

        $this->assertEquals($expectBool, $extension);
        return $extension;
    }

    /**
     * @depends testYmlExtensionSetRoutesFromYml
     */
    public function testNotFoudDirSetRoutesFromYml($yamlExtensionEnabled)
    {
        if ($yamlExtensionEnabled) {
            $dirName = '/subDir/dirNotExist';
            $ymlFile = 'ymlExample.yml';

            $Routing = new Routing();

            $this->expectExceptionMessage(RoutingHelper::YML_OR_XML_NO_DIR_OR_FILE);
            $Routing->setRoutesFromYml($dirName, $ymlFile);
        } else {
            print self::MSG_ERROR_YML_EXTENSION;
        }
    }

    /**
     * @depends testYmlExtensionSetRoutesFromYml
     */
    public function testNotFoudFileSetRoutesFromYml($yamlExtensionEnabled)
    {
        if ($yamlExtensionEnabled) {
            $dirName = __DIR__;
            $ymlFile = 'ymlExample.yml';

            $Routing = new Routing();

            $this->expectExceptionMessage(RoutingHelper::YML_OR_XML_NO_DIR_OR_FILE);
            $Routing->setRoutesFromYml($dirName, $ymlFile);
        } else {
            print self::MSG_ERROR_YML_EXTENSION;
        }
    }

    /**
     * @depends testYmlExtensionSetRoutesFromYml
     */
    public function testValidSetRoutesFromYml($yamlExtensionEnabled)
    {
        if ($yamlExtensionEnabled) {
            $dirName = __DIR__ . '/yml';
            $ymlFile = 'routesValidTest.yml';

            $expect = array(
                0 => array(
                    'expression' => '/',
                    'controller' => 'IndexController',
                    'action' => 'showHomeAction',
                    'params' => array(
                        0 => 'extraParams'
                    )
                )
            );

            $Routing = new Routing();
            $Routing->setRoutesFromYml($dirName, $ymlFile);

            $this->assertEquals($expect, $Routing->getRoutes());
        } else {
            print self::MSG_ERROR_YML_EXTENSION;
        }
    }

    /**
     * @depends testYmlExtensionSetRoutesFromYml
     */
    public function testEmptySetRoutesFromYml($yamlExtensionEnabled)
    {
        if ($yamlExtensionEnabled) {
            $dirName = __DIR__ . '/yml';
            $ymlFile = 'routesEmptyTest.yml';

            $expect = array();

            $Routing = new Routing();
            $Routing->setRoutesFromYml($dirName, $ymlFile);

            $this->assertEquals($expect, $Routing->getRoutes());
        } else {
            print self::MSG_ERROR_YML_EXTENSION;
        }
    }

    /* ------------------------------------------
            setRoutesFromXml METHOD TESTS!
       ------------------------------------------ */

    public function testXmlExtensionSetRoutesFromXml()
    {
        $extension = extension_loaded('libxml');

        if (!$extension) {
            $expectBool = false;
            $Routing = new Routing();

            $dirName = '/subDir/dirExample';
            $xmlFile = 'xmlExample.xml';

            $this->expectExceptionMessage(RoutingHelper::NO_XML_EXT);
            $Routing->setRoutesFromXml($dirName, $xmlFile);
        } else {
            $expectBool = true;
        }

        $this->assertEquals($expectBool, $extension);
        return $extension;
    }

    /**
     * @depends testXmlExtensionSetRoutesFromXml
     */
    public function testNotFoudDirSetRoutesFromXml($xmlExtensionEnabled)
    {
        if ($xmlExtensionEnabled) {
            $dirName = '/subDir/dirNotExist';
            $xmlFile = 'xmlExample.xml';

            $Routing = new Routing();

            $this->expectExceptionMessage(RoutingHelper::YML_OR_XML_NO_DIR_OR_FILE);
            $Routing->setRoutesFromXml($dirName, $xmlFile);
        } else {
            print self::MSG_ERROR_XML_EXTENSION;
        }
    }

    /**
     * @depends testXmlExtensionSetRoutesFromXml
     */
    public function testNotFoudFileSetRoutesFromXml($xmlExtensionEnabled)
    {
        if ($xmlExtensionEnabled) {
            $dirName = __DIR__;
            $xmlFile = 'xmlExample.xml';

            $Routing = new Routing();

            $this->expectExceptionMessage(RoutingHelper::YML_OR_XML_NO_DIR_OR_FILE);
            $Routing->setRoutesFromXml($dirName, $xmlFile);
        } else {
            print self::MSG_ERROR_XML_EXTENSION;
        }
    }

    /**
     * @depends testXmlExtensionSetRoutesFromXml
     */
    public function testValidSetRoutesFromXml($xmlExtensionEnabled)
    {
        if ($xmlExtensionEnabled) {
            $dirName = __DIR__ . '/xml';
            $xmlFile = 'routesValidTest.xml';

            $expect = array(
                0 => array(
                    'expression' => '/',
                    'controller' => 'MyController',
                    'action' => 'MyAction',
                    'extra' => 'Hello'
                ),
                1 => array(
                    'expression' => '/contacts',
                    'controller' => 'MyController2',
                    'params' => 'Hello1',
                    'extra' => 'Hello2'
                )
            );

            $Routing = new Routing();
            $Routing->setRoutesFromXml($dirName, $xmlFile);

            $this->assertEquals($expect, $Routing->getRoutes());
        } else {
            print self::MSG_ERROR_XML_EXTENSION;
        }
    }

    /**
     * @depends testXmlExtensionSetRoutesFromXml
     */
    public function testEmptySetRoutesFromXml($xmlExtensionEnabled)
    {
        if ($xmlExtensionEnabled) {
            $dirName = __DIR__ . '/xml';
            $xmlFile = 'routesEmptyTest.xml';

            $expect = array();

            $Routing = new Routing();
            $Routing->setRoutesFromXml($dirName, $xmlFile);

            $this->assertEquals($expect, $Routing->getRoutes());
        } else {
            print self::MSG_ERROR_XML_EXTENSION;
        }
    }

    /* ------------------------------------------
            matchRoute METHOD TESTS!
    ------------------------------------------ */

    //Test matchRoute method without defined routes to matching.
    public function testMatchRouteWithoutDefinedRoutes()
    {
        $Routing = new Routing();
        $this->assertFalse($Routing->matchRoute());
    }

    /**
     * @depends testYmlExtensionSetRoutesFromYml
     */
    public function testMatchRouteMatchByYmlRoutes($yamlExtensionEnabled)
    {
        if ($yamlExtensionEnabled) {
            $Routing = new Routing();

            $expect = array(
            'expression' => '/',
            'controller' => 'IndexController',
            'action' => 'showHomeAction',
            'params' => array(
                0 => 'extraParams'
            ));

            $matchedRoute = $Routing->setRoutesFromYml(__DIR__ . '/yml', 'routesValidTest.yml')->matchRoute();
            $this->assertEquals($expect, $matchedRoute);
        } else {
            print self::MSG_ERROR_YML_EXTENSION;
        }
    }

    /**
     * @depends testYmlExtensionSetRoutesFromYml
     */
    public function testMatchRouteNotMatchByYmlRoutes($yamlExtensionEnabled)
    {
        if ($yamlExtensionEnabled) {
            $Routing = new Routing();
            $Routing->setRequestUri('/routeNotConsideredByYml');

            $matchedRoute = $Routing->setRoutesFromYml(__DIR__ . '/yml', 'routesValidTest.yml')->matchRoute();
            $this->assertFalse($matchedRoute);
        } else {
            print self::MSG_ERROR_YML_EXTENSION;
        }
    }

    /**
     * @depends testXmlExtensionSetRoutesFromXml
     */
    public function testMatchRouteMatchByXmlRoutes($xmlExtensionEnabled)
    {
        if ($xmlExtensionEnabled) {
            $Routing = new Routing();

            $expect = array(
            'expression' => '/',
            'controller' => 'MyController',
            'action' => 'MyAction',
            'extra' => 'Hello'
            );

            $matchedRoute = $Routing->setRoutesFromXml(__DIR__ . '/xml', 'routesValidTest.xml')->matchRoute();
            $this->assertEquals($expect, $matchedRoute);
        } else {
            print self::MSG_ERROR_XML_EXTENSION;
        }
    }

    /**
     * @depends testXmlExtensionSetRoutesFromXml
     */
    public function testMatchRouteNotMatchByXmlRoutes($xmlExtensionEnabled)
    {
        if ($xmlExtensionEnabled) {
            $Routing = new Routing();
            $Routing->setRequestUri('/routeNotConsideredByXml');

            $matchedRoute = $Routing->setRoutesFromXml(__DIR__ . '/xml', 'routesValidTest.xml')->matchRoute();
            $this->assertFalse($matchedRoute);
        } else {
            print self::MSG_ERROR_XML_EXTENSION;
        }
    }

    public function testMatchRouteMatchByArrayRoutes()
    {
        $Routing = new Routing();

        $expect = array(
                'expression'     => '/',
                'controller'=> 'MyController',
                'action'    => 'MyAction',
                'extra1'    => 'extra1',
                'extra2'    => 'extra2'
        );

        $routeMatch = $Routing->setRoutes(array(
            'homepage' => array(
                'expression'     => '/',
                'controller'=> 'MyController',
                'action'    => 'MyAction',
                'extra1'    => 'extra1',
                'extra2'    => 'extra2'
            )
        ));

        $matchedRoute = $Routing->matchRoute();
        $this->assertEquals($expect, $matchedRoute);
    }

    public function testMatchRouteNotMatchByArrayRoutes()
    {
        $Routing = new Routing();
        $Routing->setRequestUri('/routeNotConsideredByArray');

        $routeMatch = $Routing->setRoutes(array(
            'homepage' => array(
                'expression'     => '/',
                'controller'=> 'MyController',
                'action'    => 'MyAction',
                'extra1'    => 'extra1',
                'extra2'    => 'extra2'
            )
        ))->matchRoute();

        $this->assertFalse($routeMatch);
    }

    /* ------------------------------------------
            set and get RequestUri METHOD TESTS!
    ------------------------------------------ */
    public function testSetRequestUri()
    {
        $customUri = $expect = '/find/me';

        $Routing = new Routing();
        $Routing->setRequestUri($customUri);

        $this->assertEquals($expect, $Routing->getRequestUri());
    }

    public function testGetRequestAfterNewIstance()
    {
        $expect = '/'; //default!
        $Routing = new Routing();

        $this->assertEquals($expect, $Routing->getRequestUri());
    }
}
