<?php

//Global constant for website prefix
!defined('WEBSITE_PATH_PREFIX')
&& define('WEBSITE_PATH_PREFIX',
        '/PhpstormProjects/Mini_Projet_Php_Tweeter/Tweeter/main.php');

//Dependencies (classLoader, db)
require "dependencies.php";

//Routing
require "routes.php";
$router->run();