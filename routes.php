<?php
$router = new \mf\router\Router();

$router->addRoute('home',
    '/home/',
    '\tweeterapp\control\TweeterController',
    'viewHome');
$router->addRoute('tweet',
    '/tweet/',
    '\tweeterapp\control\TweeterController',
    'viewTweet');
$router->addRoute('userTweets',
    '/user/',
    '\tweeterapp\control\TweeterController',
    'viewUserTweets');

$router->setDefaultRoute('/home/');