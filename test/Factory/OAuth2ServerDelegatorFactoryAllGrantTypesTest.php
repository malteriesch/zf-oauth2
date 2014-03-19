<?php
/**
 * @copyright Copyright (c) 2014 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://opensource.org/licenses/BSD-3-Clause BSD-3-Clause
 */

namespace ZFTest\OAuth2\Factory;

use Zend\ServiceManager\ServiceManager;
use Zend\Test\PHPUnit\Controller\AbstractHttpControllerTestCase;
use ZF\OAuth2\Factory\OAuth2ServerDelegatorFactoryAllGrantTypes;
use OAuth2\GrantType\AuthorizationCode;
use OAuth2\GrantType\ClientCredentials;
use OAuth2\GrantType\RefreshToken;
use OAuth2\GrantType\UserCredentials;

class OAuth2ServerDelegatorFactoryAllGrantTypesTest extends AbstractHttpControllerTestCase
{

    /**
     * @var OAuth2ServerDelegatorFactoryAllGrantTypes
     */
    protected $factory;

    /**
     * @var ServiceManager
     */
    protected $services;

    

    public function testServiceCreatedWithAllCredentialsAdded()
    {
        $testConfiguration = array('enforce_state' => true, 'allow_implicit' => false, 'access_lifetime' => 50);
        
        $adapter = $this->getMockBuilder('OAuth2\Storage\Pdo')->disableOriginalConstructor()->getMock();

        $this->services->setService('TestAdapter', $adapter);
        $this->services->setService('Config', array(
            'zf-oauth2' => array(
                'storage' => 'TestAdapter'
            )
        ));

        $callback = function() use($adapter, $testConfiguration){
            return new \OAuth2\Server($adapter, $testConfiguration);
        };

        $expectedServer = new \OAuth2\Server($adapter, $testConfiguration);
        $expectedServer->addGrantType(new ClientCredentials($adapter));
        $expectedServer->addGrantType(new AuthorizationCode($adapter));
        $expectedServer->addGrantType(new UserCredentials($adapter));
        $expectedServer->addGrantType(new RefreshToken($adapter));

        $service = $this->factory->createDelegatorWithName( $this->services, null, null, $callback);
        $this->assertEquals($expectedServer, $service);
    }

   

    protected function setUp()
    {
        $this->factory = new OAuth2ServerDelegatorFactoryAllGrantTypes();
        $this->services = $services = new ServiceManager();

    }
}
