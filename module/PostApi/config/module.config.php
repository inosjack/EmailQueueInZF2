<?php
/**
 * Created by PhpStorm.
 * User: dell
 * Date: 7/26/2017
 * Time: 11:53 PM
 */
return array(
    'controllers' => array(
        'invokables' => array(
            'PostApi\Controller\PostApi' => 'PostApi\Controller\PostApiController',
        ),
    ),
    
    //
    'router' => array(
        'routes' => array(
            'api/post' => array(
                'type'    => 'Segment',
                'options' => array(
                    'route'    => '/api/post',
                    'defaults' => array(
                        'controller' => 'PostApi\Controller\PostApi',
                    ),
                ),
            ),
        ),
    ),
    'view_manager' => array( //Add this config
        'strategies' => array(
            'ViewJsonStrategy',
        ),
    ),
);