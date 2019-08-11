<?php

Config::set('site_name', 'Ksen');

Config::set('languages', array('en', 'fr'));

Config::set('routes', array(
    'default'=>'',
    'admin'=>'admin_'
));

Config::set('default_route', 'default');

Config::set('default_language', 'en');

Config::set('default_controller', 'pages');

Config::set('default_action', 'index');

//DataBase
Config::set('db.host', 'mysql:3306');
Config::set('db.user', 'root');
Config::set('db.password', 'tiger');
Config::set('db.db_name', 'mvc');

Config::set('salt', 'jd7sj3sdkd964he7e');

Config::set('redis_host', 'redis');
