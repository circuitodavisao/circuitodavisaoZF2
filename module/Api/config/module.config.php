<?php

namespace Api;

return array(
    'controllers' => array(
        'invokables' => array(
            'Api\Controller\Index' => 'Api\Controller\IndexController',
        ),
        'factories' => array(
            'Api\Controller\Teste' => 'Api\Controller\Factory\TesteControllerFactory',
        ),
    ),
    'router' => array(
        'routes' => array(
            'api' => array(
                'type' => 'Literal',
                'options' => array(
                    'route' => '/api',
                    'defaults' => array(
                        'controller' => 'Api\Controller\Index',
                    ),
                ),
            ),
            'apiteste' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/api/teste[/:id]',
                    'constraints' => array(
                        'id' => '[0-9]+',
                    ),
                    'defaults' => array(
                        'controller' => 'Api\Controller\Teste',
                    ),
                ),
            ),
        ),
    ),
    'view_manager' => array(
        'strategies' => array(
            'ViewJsonStrategy',
        ),
    ),
    'doctrine' => array(
        'driver' => array(
            'application_entities' => array(
                'class' => 'Doctrine\ORM\Mapping\Driver\AnnotationDriver',
                'cache' => 'array',
                'paths' => array(__DIR__ . '/Model/Entity')
            ),
            'orm_default' => array(
                'drivers' => array(
                    'Application\Model\Entity' => 'application_entities'
                )
            )
        ),
    ),
);
