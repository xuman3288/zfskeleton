<?php
return array(
    'controllers'     => [
        'invokables' => [
            'Test\Controller\Index' => 'Test\Controller\IndexController',
            'Test\Controller\User'  => 'Test\Controller\UserController'

        ]
    ],
    'router'          => [
        'routes' => [
            'test' => [
                'type'          => 'Literal',
                'options'       => [
                    'route'    => '/test',
                    'defaults' => [
                        '__NAMESPACE__' => 'Test\Controller',
                        'controller'    => 'Index',
                        'action'        => 'index'
                    ]
                ],
                'may_terminate' => true,
                'child_routes'  => [
                    'default' => [
                        'type'    => 'Segment',
                        'options' => array(
                            'route'       => '/[:controller[/:action]]',
                            'constraints' => array(
                                'controller' => '[a-zA-Z][a-zA-Z0-9_-]*',
                                'action'     => '[a-zA-Z][a-zA-Z0-9_-]*',
                            ),
                            'defaults'    => array(),
                        ),
                    ]
                ]
            ],
            'user' => [
                'type'    => 'Literal',
                'options' => [
                    'route'    => '/user',
                    'defaults' => [
                        '__NAMESPACE__' => 'Test\Controller\UserController',
                        'controller'    => 'User',
                        'action'        => 'index'
                    ]
                ],
            ],
            'test-home' => array(
                'type' => 'Zend\Mvc\Router\Http\Literal',
                'options' => array(
                    'route'    => '/',
                    'defaults' => array(
                        'controller' => 'Test\Controller\User',
                        'action'     => 'index',
                    ),
                ),
            ),
        ]
    ],
    'view_manager'    => array(
        'template_map'        => array(
            'layout/layout' => __DIR__ . '/../view/layout/layout.phtml',
        ),
        'template_path_stack' => array(
            'test' => __DIR__ . '/../view',
        ),
        'strategies'          => array('ViewJsonStrategy')
    ),
    'service_manager' => array(
        'factories'          => array(
            'navigation' => '\Zend\Navigation\Service\DefaultNavigationFactory'
        ),
        'aliases'            => array(),
        'abstract_factories' => array(

        )
    ),
    'navigation'      => array(
        'default' => array(
            array(
                'label' => 'User',
                'route' => 'test-home',
                'pages' => array(
                    array(
                        'label' => 'Test',
                        'route' => 'test',
                    ),
                ),
            ),
            array(
                'label' => 'Test',
                'route' => 'test',
            ),
        )
    ),
    'translator'      => array(
        'locale'                    => 'zh_CN',
        'translation_file_patterns' => array(
            array(
                'type'     => 'gettext',
                'base_dir' => __DIR__ . '/../language',
                'pattern'  => '%s.mo'
            )
        ),
        'translation_files'         => array(
            array(
                'type'     => 'phparray',
                'filename' => __DIR__
                    . '/../../../vendor/zendframework/zendframework/resources/languages/zh/Zend_Validate.php',
            ),
            array(
                'type'     => 'phparray',
                'filename' => __DIR__
                    . '/../../../vendor/zendframework/zendframework/resources/languages/zh/Zend_Captcha.php',
            ),
        ),

    ),

);