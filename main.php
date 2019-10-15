<?php

//Dependencies (classLoader, db)
require "dependencies.php";

//Global constant for website prefix
!defined('WEBSITE_PATH_PREFIX')
&& define('WEBSITE_PATH_PREFIX', $req->script_name);

//Routing
require "routes.php";
$router->run();