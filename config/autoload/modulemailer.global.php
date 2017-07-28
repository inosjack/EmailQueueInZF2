<?php
/**
 * ModuleMailer Configuration
 *
 * If you have a ./config/autoload/ directory set up for your project, you can
 * drop this config file in it and change the values as you wish.
 */
$settings = array(

    'table'=>'mailer',

    'default_from'=>'default@domain.com',

    'smtp' => array(
        'host'              => 'smtp.mailtrap.io',
        'connection_class'  => 'plain',
        'connection_config' => array(
            'ssl'       => 'tls',
            'username' => '8fb82c0165a162',
            'password' => '69af58bb34ab02'
        ),
        'port' => 465,
    ),
);

/**
 * You do not need to edit below this line
 */
return array(
    'ModuleMailer' => $settings,
);