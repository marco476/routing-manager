<?php
use PHPUnit\Framework\TestCase;

use Routing\Routing;
use Routing\Exception\RoutingException;
use Routing\Exception\ExceptionMessage;

class RoutingTest extends TestCase
{
	/**
	 * When I get a new Routing istance, the routes aren't setted.
	 *
	 * @return boold
	 */
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

	/**
	 * An empty array for setRoutes not set anything.
	 *
	 * @return bool
	 */
	public function testEmptySetRoutes()
	{
		$expect = array();

		$Routing = new Routing();
		$Routing->setRoutes(array());

		$this->assertEquals($expect, $Routing->getRoutes());
	}

	/**
	 * Test setRoutes with one valid route.
	 *
	 * @return bool
	 */
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
		$Routing->setRoutes($input);

		$this->assertEquals($expect, $Routing->getRoutes());
	}

	/**
	 * Test setRoutes with same routes not valid.
	 *
	 * @return boold
	 */
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
		$Routing->setRoutes($input);

		$this->assertEquals($expect, $Routing->getRoutes());
	}

	/**
	 * Test setRoutes with all routes valid.
	 *
	 * @return bool
	 */
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
		$Routing->setRoutes($input);

		$this->assertEquals($expect, $Routing->getRoutes());
	}

	/* ------------------------------------------
			setRoutesFromYml METHOD TESTS!
	   ------------------------------------------ */

	/**
	 * Check if yml extension is installed.
	 *
	 * @return bool
	 */
	public function testYmlExtensionSetRoutesFromYml()
	{
		$extension = extension_loaded('yaml');

		if (!$extension) {
			$expectBool = false;
			$Routing = new Routing();

			$dirName = '/subDir/dirExample';
			$ymlFile = 'ymlExample.yml';

			$this->expectException(RoutingException::class);
			$this->expectExceptionMessage(ExceptionMessage::NO_YAML_EXT);

			$Routing->setRoutesFromYml($dirName, $ymlFile);
		} else {
			$expectBool = true;
		}

		$this->assertEquals($expectBool, $extension);
		return $extension;
	}

	/**
	 * @depends testYmlExtensionSetRoutesFromYml
	 *
	 * @param bool $yamlExtensionEnabled
	 * @return void
	 */
	public function testNotFoudDirSetRoutesFromYml($yamlExtensionEnabled)
	{
		if ($yamlExtensionEnabled) {
			$dirName = '/subDir/dirNotExist';
			$ymlFile = 'ymlExample.yml';

			$Routing = new Routing();

			$this->expectException(RoutingException::class);
			$this->expectExceptionMessage(ExceptionMessage::YML_OR_XML_NO_DIR_OR_FILE);

			$Routing->setRoutesFromYml($dirName, $ymlFile);
		} else {
			print ExceptionMessage::NO_YAML_EXT . PHP_EOL;
		}
	}

	/**
	 * @depends testYmlExtensionSetRoutesFromYml
	 *
	 * @param bool $yamlExtensionEnabled
	 * @return void
	 */
	public function testNotFoudFileSetRoutesFromYml($yamlExtensionEnabled)
	{
		if ($yamlExtensionEnabled) {
			$dirName = __DIR__;
			$ymlFile = 'ymlExample.yml';

			$Routing = new Routing();

			$this->expectException(RoutingException::class);
			$this->expectExceptionMessage(ExceptionMessage::YML_OR_XML_NO_DIR_OR_FILE);

			$Routing->setRoutesFromYml($dirName, $ymlFile);
		} else {
			print ExceptionMessage::NO_YAML_EXT . PHP_EOL;
		}
	}

	/**
	 * @depends testYmlExtensionSetRoutesFromYml
	 *
	 * @param bool $yamlExtensionEnabled
	 * @return bool|void
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
			print ExceptionMessage::NO_YAML_EXT . PHP_EOL;
		}
	}

	/**
	 * @depends testYmlExtensionSetRoutesFromYml
	 *
	 * @param bool $yamlExtensionEnabled
	 * @return bool|void
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
			print ExceptionMessage::NO_YAML_EXT . PHP_EOL;
		}
	}

	/* ------------------------------------------
			setRoutesFromXml METHOD TESTS!
	   ------------------------------------------ */

	/**
	 * Check if xml extension is installed.
	 *
	 * @return bool
	 */
	public function testXmlExtensionSetRoutesFromXml()
	{
		$extension = extension_loaded('libxml');

		if (!$extension) {
			$expectBool = false;
			$Routing = new Routing();

			$dirName = '/subDir/dirExample';
			$xmlFile = 'xmlExample.xml';

			$this->expectException(RoutingException::class);
			$this->expectExceptionMessage(ExceptionMessage::NO_XML_EXT);

			$Routing->setRoutesFromXml($dirName, $xmlFile);
		} else {
			$expectBool = true;
		}

		$this->assertEquals($expectBool, $extension);
		return $extension;
	}

	/**
	 * @depends testXmlExtensionSetRoutesFromXml
	 *
	 * @param bool $xmlExtensionEnabled
	 * @return void
	 */
	public function testNotFoudDirSetRoutesFromXml($xmlExtensionEnabled)
	{
		if ($xmlExtensionEnabled) {
			$dirName = '/subDir/dirNotExist';
			$xmlFile = 'xmlExample.xml';

			$Routing = new Routing();

			$this->expectException(RoutingException::class);
			$this->expectExceptionMessage(ExceptionMessage::YML_OR_XML_NO_DIR_OR_FILE);

			$Routing->setRoutesFromXml($dirName, $xmlFile);
		} else {
			print ExceptionMessage::NO_XML_EXT . PHP_EOL;
		}
	}

	/**
	 * @depends testXmlExtensionSetRoutesFromXml
	 *
	 * @param bool $xmlExtensionEnabled
	 * @return void
	 */
	public function testNotFoudFileSetRoutesFromXml($xmlExtensionEnabled)
	{
		if ($xmlExtensionEnabled) {
			$dirName = __DIR__;
			$xmlFile = 'xmlExample.xml';

			$Routing = new Routing();

			$this->expectException(RoutingException::class);
			$this->expectExceptionMessage(ExceptionMessage::YML_OR_XML_NO_DIR_OR_FILE);

			$Routing->setRoutesFromXml($dirName, $xmlFile);
		} else {
			print ExceptionMessage::NO_XML_EXT . PHP_EOL;
		}
	}

	/**
	 * @depends testXmlExtensionSetRoutesFromXml
	 *
	 * @param bool $xmlExtensionEnabled
	 * @return bool|void
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
			print ExceptionMessage::NO_XML_EXT . PHP_EOL;
		}
	}

	/**
	 * @depends testXmlExtensionSetRoutesFromXml
	 *
	 * @param bool $xmlExtensionEnabled
	 * @return bool|void
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
			print ExceptionMessage::NO_XML_EXT . PHP_EOL;
		}
	}

	/* ------------------------------------------
			matchRoute METHOD TESTS!
	------------------------------------------ */

	/**
	 * Test matchRoute method without defined routes to matching.
	 *
	 * @return bool
	 */
	public function testMatchRouteWithoutDefinedRoutes()
	{
		$Routing = new Routing();
		$this->assertFalse($Routing->matchRoute());
	}

	/**
	 * @depends testYmlExtensionSetRoutesFromYml
	 *
	 * @param bool $yamlExtensionEnabled
	 * @return bool|void
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

			$Routing->setRoutesFromYml(__DIR__ . '/yml', 'routesValidTest.yml');
			$this->assertEquals($expect, $Routing->matchRoute());
		} else {
			print ExceptionMessage::NO_YAML_EXT . PHP_EOL;
		}
	}

	/**
	 * @depends testYmlExtensionSetRoutesFromYml
	 *
	 * @param bool $yamlExtensionEnabled
	 * @return bool|void
	 */
	public function testMatchRouteNotMatchByYmlRoutes($yamlExtensionEnabled)
	{
		if ($yamlExtensionEnabled) {
			$Routing = new Routing();
			$Routing->setRequestUri('/routeNotConsideredByYml');

			$Routing->setRoutesFromYml(__DIR__ . '/yml', 'routesValidTest.yml');
			$this->assertFalse($Routing->matchRoute());
		} else {
			print ExceptionMessage::NO_YAML_EXT . PHP_EOL;
		}
	}

	/**
	 * @depends testXmlExtensionSetRoutesFromXml
	 *
	 * @param bool $xmlExtensionEnabled
	 * @return bool|void
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

			$Routing->setRoutesFromXml(__DIR__ . '/xml', 'routesValidTest.xml');
			$this->assertEquals($expect, $Routing->matchRoute());
		} else {
			print ExceptionMessage::NO_YAML_EXT . PHP_EOL;
		}
	}

	/**
	 * @depends testXmlExtensionSetRoutesFromXml
	 *
	 * @param bool $xmlExtensionEnabled
	 * @return bool|void
	 */
	public function testMatchRouteNotMatchByXmlRoutes($xmlExtensionEnabled)
	{
		if ($xmlExtensionEnabled) {
			$Routing = new Routing();
			$Routing->setRequestUri('/routeNotConsideredByXml');

			$Routing->setRoutesFromXml(__DIR__ . '/xml', 'routesValidTest.xml');
			$this->assertFalse($Routing->matchRoute());
		} else {
			print ExceptionMessage::NO_XML_EXT . PHP_EOL;
		}
	}

	/**
	 * @return bool
	 */
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

		$Routing->setRoutes(array(
			'homepage' => array(
				'expression'     => '/',
				'controller'=> 'MyController',
				'action'    => 'MyAction',
				'extra1'    => 'extra1',
				'extra2'    => 'extra2'
			)
		));

		$this->assertEquals($expect, $Routing->matchRoute());
	}

	/**
	 * @return bool
	 */
	public function testMatchRouteNotMatchByArrayRoutes()
	{
		$Routing = new Routing();
		$Routing->setRequestUri('/routeNotConsideredByArray');

		$Routing->setRoutes(array(
			'homepage' => array(
				'expression'     => '/',
				'controller'=> 'MyController',
				'action'    => 'MyAction',
				'extra1'    => 'extra1',
				'extra2'    => 'extra2'
			)
		));

		$this->assertFalse($Routing->matchRoute());
	}

	/* ------------------------------------------
		 set and get RequestUri METHOD TESTS!
	------------------------------------------ */
	/**
	 * @return bool
	 */
	public function testSetRequestUri()
	{
		$customUri = $expect = '/find/me';

		$Routing = new Routing();
		$Routing->setRequestUri($customUri);

		$this->assertEquals($expect, $Routing->getRequestUri());
	}

	/**
	 * @return bool
	 */
	public function testGetRequestAfterNewIstance()
	{
		$expect = '/'; //default!
		$Routing = new Routing();

		$this->assertEquals($expect, $Routing->getRequestUri());
	}
}
