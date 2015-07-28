<?php

return array(

    //App properties
    'name' => 'TitanFramework',
    'environment' => $_SERVER['REMOTE_ADDR'] == '192.168.1.6' ? 'test' : 'production',
    'domain' => '192.168.1.6',

    //Database
    'db_location' => 'localhost',
    'db_name' => 'TitanFrameworkDev',
    'db_user' => 'root',
    'db_pass' => 'r160689s',

    //Email
    'email_system' => 'support@titanframework.com',
    'smtp_host' => 'smtp.gmail.com',
    'smtp_user' => 'rydosmith2@gmail.com',
    'smtp_password' => 'R160689s'
);