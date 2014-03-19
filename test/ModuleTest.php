<?php
/**
 * @license   http://opensource.org/licenses/BSD-3-Clause BSD-3-Clause
 * @copyright Copyright (c) 2014 Zend Technologies USA Inc. (http://www.zend.com)
 */

namespace ZFTest\Hal;

use ZF\OAuth2\Module;
use PHPUnit_Framework_TestCase as TestCase;
use Zend\ServiceManager\ServiceManager;

class ModuleTest extends TestCase
{
    public function setUp()
    {
        $this->module = new Module;
    }

    public function setupServiceManager()
    {
        $services = new ServiceManager(new \Zend\Mvc\Service\ServiceManagerConfig());
        $services->setAllowOverride(true);
        $services->setService('ApplicationConfig', include 'TestAsset/application.config.php' );
        $services->get('ModuleManager')->loadModules();

        return $services;
    }

    public function testRetrievingOAuthServerCallsDelegate()
    {
        $services = $this->setupServiceManager();

        $mockStorageAdapter = $this->getMockBuilder('ZF\OAuth2\Adapter\PdoAdapter')->disableOriginalConstructor()->getMock();
        $mockOAuth2Server = $this->getMockBuilder('OAuth2\Server')->disableOriginalConstructor()->getMock();

        $mockDelegateFactory = $this->getMockBuilder('Zend\ServiceManager\DelegatorFactoryInterface')->disableOriginalConstructor()->getMock();
        $mockDelegateFactory->expects($this->once())->method('createDelegatorWithName')->will($this->returnValue($mockOAuth2Server));
        
        $services->setService('ZF\OAuth2\Adapter\PdoAdapter', $mockStorageAdapter);
        $services->setService('ZF\OAuth2\Factory\OAuth2ServerDelegatorFactoryAllGrantTypes', $mockDelegateFactory);
        $this->assertSame($mockOAuth2Server, $services->get('ZF\OAuth2\Service\OAuth2Server'));

    }

   
}
