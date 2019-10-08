<?php

use mf\utils\ClassLoader;
use tweeterapp\model\User;

require_once "src".DIRECTORY_SEPARATOR."mf".DIRECTORY_SEPARATOR."utils".DIRECTORY_SEPARATOR."ClassLoader.php";
new ClassLoader('src'); // Auto-register
require_once "vendor/autoload.php";

$config = array();
$file = str_replace(PHP_EOL, '', file_get_contents("conf".DIRECTORY_SEPARATOR."config.ini"));
$rows = explode(',', $file);
foreach ($rows as $value) {
    $row = explode("=>", $value);
    if (empty($row[1])) $row[1] = '';
    $config[$row[0]] = $row[1];
}

$db = new Illuminate\Database\Capsule\Manager();

$db->addConnection($config);
$db->setAsGlobal();
$db->bootEloquent();