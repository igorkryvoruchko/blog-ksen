<?php

define('DS', DIRECTORY_SEPARATOR);
define('ROOT', dirname(dirname(__FILE__)));
define('VIEWS_PATH', ROOT.DS.'views');

require_once (ROOT.DS.'lib'.DS.'init.php');

$router = new Router($_SERVER['REQUEST_URI']);

$redis = new Redis();
$redis->connect('redis', 6379);
$redis->set('name', 'Ksena');
$name = $redis->get('name');
//
session_start();

App::run($_SERVER['REQUEST_URI']);


