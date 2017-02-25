<?php
use PHPUnit\Framework\TestCase;
use Routing\Routing;

class RoutingTest extends TestCase
{
    //When I get a new Routing istance, the routes
    //is not setted.
    public function testRoutesEmptyOnStart()
    {
        $expect = array();

        $Routing = new Routing();
        $resultRoutes = $Routing->getRoutes();

        $this->assertEquals($expect, $resultRoutes);
    }

    //An empty array for setRoutes not set nothing.
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
}
