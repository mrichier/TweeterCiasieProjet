<?php

//Dependencies (classLoader, db)
use mf\view\AbstractView;
use tweeterapp\view\TweeterView;

require "dependencies.php";

//Global constant for website prefix
!defined('WEBSITE_PATH_PREFIX')
&& define('WEBSITE_PATH_PREFIX', $req->script_name);

//Adding styles
TweeterView::addStyleSheet("css/style.css");

//Routing
require "routes.php";
$router->run();

