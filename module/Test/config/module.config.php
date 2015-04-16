<?php
return array(
    'controllers' => [
        'invokables' => [
            'Test\Controller\Index' => 'Test\Controller\IndexController',
            'Test\Controller\User'  => 'Test\Controller\UserController'

        ]
    ],
    'router' => [
        'routes' => [
            'test' => [
                'type' => 'Literal',
                'options' => [
                    'route' => '/test',
                    'defaults' => [
                        '__NAMESPACE__' => 'Test\Controller',
                        'controller'    => 'Index',
                        'action'        => 'index'
                    ]
                ],
                'may_terminate' => true,
                'child_routes' => [
                    'default' => [
                        'type'    => 'Segment',
                        'options' => array(
                            'route'    => '/[:controller[/:action]]',
                            'constraints' => array(
                                'controller' => '[a-zA-Z][a-zA-Z0-9_-]*',
                                'action'     => '[a-zA-Z][a-zA-Z0-9_-]*',
                            ),
                            'defaults' => array(
                            ),
                        ),
                    ]
                ]
            ]
        ]
    ],
    'view_manager' => array(
        'template_map' => array(
            'layout/layout'           => __DIR__ . '/../view/layout/layout.phtml',
        ),
        'template_path_stack' => array(
            'test' => __DIR__ . '/../view',
        ),
        'strategies' => array('ViewJsonStrategy')
    ),
    'service_manager' => array(
        'factories' => array(
        ),
        'aliases'   => array(
        ),
        'abstract_factories'    => array(
        )
    ),
    'translator' => array(
        'locale' => 'zh_CN',
        'translation_file_patterns' => array(
            array(
                'type' => 'gettext',
                'base_dir' => __DIR__ . '/../language',
                'pattern' => '%s.mo'
            )
        ),
        'translation_files' => array(
            array(
                'type'     => 'phparray',
                'filename'  => __DIR__ . '/../../../vendor/zendframework/zendframework/resources/languages/zh/Zend_Validate.php',
            ),
            array(
                'type'     => 'phparray',
                'filename'  => __DIR__ . '/../../../vendor/zendframework/zendframework/resources/languages/zh/Zend_Captcha.php',
            ),
        ),
        // 由于一种语言,有多种表现形式,日语可以是ja,ja-jp，简单中文zh,zh-CN,这里归纳一下
        // abbr，与框架Library同级resource下语言目录名称，看起来像缩写
        // include，语言包含的形式
        // 此处我的网站使用三种语言:zh_CN, en_US, zh_TW，有更多语言往后面加

    ),

);