<?php

use mf\utils\ClassLoader;

require_once "vendor/autoload.php";
require_once "src".DIRECTORY_SEPARATOR."mf".DIRECTORY_SEPARATOR."utils".DIRECTORY_SEPARATOR."ClassLoader.php";
new ClassLoader('src'); // Auto-register

$config = array();
$file = file_get_contents("conf".DIRECTORY_SEPARATOR."config.ini");
$rows = explode(',', $file);
foreach ($rows as $value) {
    $row = explode("=>", $value);
    $config[$row[0]] = $row[1];
}

$db = new Illuminate\Database\Capsule\Manager();

$db->addConnection($config);
$db->setAsGlobal();
$db->bootEloquent();

