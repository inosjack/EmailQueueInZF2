<?php
/**
 * ModuleMailer
 */

return array(
    'ModuleMailer' => array(
        'table'=>'mailer',
        'default_from'=>'default@domain.com',
    ),
    
    'controllers' => array(
        'invokables' => array(
            'ModuleMailer\Controller\Console' => 'ModuleMailer\Controller\ConsoleController',
        ),
    ),
    
    
    'console' => array(
        'router' => array(
            'routes' => array(
                'my-first-route' => array(
                    'options' => array(
                        'route'    => 'mailer process <queue>',
                        'defaults' => array(
                            'controller' => 'ModuleMailer\Controller\Console',
                            'action'     => 'process'
                        )
                    )
                )
                
            )
        )
    ),
    
);
