<?php
return array(
    'controllers'  => array(
        'invokables' => array(
            'Album\Controller\Album' => 'Album\Controller\AlbumController',
            'Album\Controller\Test'  => 'Album\Controller\TestController'
        ),
    ),

    // The following section is new and should be added to your file
    'router'       => array(
        'routes' => array(
            'album' => array(
                'type'    => 'Literal',
                'options' => array(
                    'route'    => '/album',
                    'defaults' => array(
                        '__NAMESPACE__' => 'Album\Controller',
                        'controller'    => 'Album',
                        'action'        => 'index',
                    ),
                ),
                'may_terminate' => true,
                'child_routes' => array(
                    'default' => array(
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
                    ),
                ),
            ),
        ),
    ),

    'view_manager' => array(
        'template_path_stack' => array(
            'album' => __DIR__ . '/../view',
        ),
    ),
);