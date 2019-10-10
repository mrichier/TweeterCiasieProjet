<?php

use mf\router\Router;
use mf\utils\ClassLoader;
use tweeterapp\model\Tweet;
use tweeterapp\model\User;

!defined('WEBSITE_PATH_PREFIX') && define('WEBSITE_PATH_PREFIX', '/PhpstormProjects/Mini_Projet_Php_Tweeter/Tweeter/main.php');

require_once "src" . DIRECTORY_SEPARATOR . "mf" . DIRECTORY_SEPARATOR . "utils" . DIRECTORY_SEPARATOR . "ClassLoader.php";
new ClassLoader('src'); // Auto-register
require_once "vendor/autoload.php";

$config = array();
$file = str_replace(PHP_EOL, '', file_get_contents("conf" . DIRECTORY_SEPARATOR . "config.ini"));
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

/* test controller
$ctrl = new tweeterapp\control\TweeterController();
echo $ctrl->viewHome();*/

//test router
$router = new \mf\router\Router();

$router->addRoute('maison',
    '/home/',
    '\tweeterapp\control\TweeterController',
    'viewHome');

$router->setDefaultRoute('/home/');

/* Après exécution de cette instruction, l'attribut statique $routes et
   $aliases de la classe Router auront les valeurs suivantes: */

//Test router::run
$router = new \mf\router\Router();

$router->addRoute('home', '/home/', '\tweeterapp\control\TweeterController', 'viewHome');
$router->addRoute('tweet', '/tweet/', '\tweeterapp\control\TweeterController', 'viewTweet');
$router->addRoute('userTweets', '/user/', '\tweeterapp\control\TweeterController', 'viewUserTweets');

$router->setDefaultRoute('/home/');

$router->run();