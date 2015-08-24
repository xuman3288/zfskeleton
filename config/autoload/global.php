<?php
/**
 * Global Configuration Override
 *
 * You can use this file for overriding configuration values from modules, etc.
 * You would place values in here that are agnostic to the environment and not
 * sensitive to security.
 *
 * @NOTE: In practice, this file will typically be INCLUDED in your source
 * control, so do not include passwords or other sensitive information in this
 * file.
 */

use OAuth2\GrantType\AuthorizationCode;

return array(
    /*'db' => array(
        'driver'         => 'pdo',
        'dsn'            => 'mysql:host=localhost;dbname=zfskeleton',
        'driver_options' => array(
            PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES \'UTF8\'',
            PDO::ATTR_TIMEOUT            => 10
        ),
        'username'       => 'root',
        'password'       => '111111'
    ),*/
    'db'              => array(
        'adapters' => array(
            'db' => array(
                'driver'         => 'pdo',
                'dsn'            => 'mysql:host=localhost;dbname=zfskeleton',
                'driver_options' => array(
                    PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES \'UTF8\'',
                    PDO::ATTR_TIMEOUT            => 10
                ),
                'username'       => 'root',
                'password'       => '111111'
            )
        )
    ),
    'caches'          => array(
        'Cache\FileTest' => array(
            'adapter' => 'filesystem',
            'options' => array(
                'cache_dir' => 'data/cache/cache_cache_filetest'
            ),
            'plugins' => array('serializer')
        )
    ),
    'service_manager' => array(
        'factories'          => array(
            //'Zend\Db\Adapter\Adapter' => 'Zend\Db\Adapter\AdapterServiceFactory',
            'MyOAuth2Provider' => 'Codeacious\OAuth2Provider\ProviderFactory',
            'storage' => function(){
                    return new OAuth2\Storage\Redis(new \Predis\Client());
                },
            'scope'  => function($sm) {
                    return new \OAuth2\Scope($sm->get('storage'));
                }
        ),
        'aliases'            => array(
            //'db' => 'Zend\Db\Adapter\Adapter'
        ),
        'abstract_factories' => array(
            'Zend\Cache\Service\StorageCacheAbstractServiceFactory',
            'Gzfextra\Db\TableGateway\TableGatewayAbstractServiceFactory',
            'Zend\Db\Adapter\AdapterAbstractServiceFactory'
        )
    ),
    'oauth2provider'=>[
        'storage' => [
            /*[
                'class' => 'OAuth2\Storage\Pdo',
                'options' => [
                    'dsn' => 'mysql:host=localhost;dbname=testdb',
                    'username' => 'user',
                    'password' => 'secret',
                ],
            ],*/
            /*[
                'class' => 'OAuth2\Storage\Redis',
                'options' => new \Predis\Client(),
            ],*/
            'storage'
        ],
        'options' => [
            //'allow_implicit' => true,
            'auth_code_lifetime' => 60,
            'access_lifetime' => 3600,
            'refresh_token_lifetime' => 1209600,
        ],
        /*'response_types' =>
        [
            //'code' =>
        ],*/
        'grant_types' => [
            [
                'class' => AuthorizationCode::class,
                'storage' => 'storage'
            ]
        ],
        //'scope_util' => 'scope'
    ],
    'ini_set' => array(
        'session.save_handler' => 'memcache',
        'session.save_path'    => 'tcp://127.0.0.1:11211'
    )
);

