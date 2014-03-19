<?php
/**
 * @license   http://opensource.org/licenses/BSD-3-Clause BSD-3-Clause
 * @copyright Copyright (c) 2014 Zend Technologies USA Inc. (http://www.zend.com)
 */

namespace ZF\OAuth2\Factory;

use Zend\ServiceManager\DelegatorFactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use OAuth2\GrantType\AuthorizationCode;
use OAuth2\GrantType\ClientCredentials;
use OAuth2\GrantType\RefreshToken;
use OAuth2\GrantType\UserCredentials;

class OAuth2ServerDelegatorFactoryAllGrantTypes implements DelegatorFactoryInterface
{
    public function createDelegatorWithName( ServiceLocatorInterface $serviceLocator, $name, $requestedName, $callback) {
        $config   = $serviceLocator->get('Config');
        $storage = $serviceLocator->get($config['zf-oauth2']['storage']);
        $server = $callback($serviceLocator);

        // Add the "Client Credentials" grant type (it is the simplest of the grant types)
        $server->addGrantType(new ClientCredentials($storage));

        // Add the "Authorization Code" grant type (this is where the oauth magic happens)
        $server->addGrantType(new AuthorizationCode($storage));

        // Add the "User Credentials" grant type
        $server->addGrantType(new UserCredentials($storage));

        // Add the "Refresh Token" grant type
        $server->addGrantType(new RefreshToken($storage));
        
        return $server;
    }
}