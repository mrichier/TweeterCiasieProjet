<?php

namespace mf\utils;

class HttpRequest extends AbstractHttpRequest
{
    public function __construct()
    {
        if (isset($_SERVER["SCRIPT_NAME"])) {
            $this->script_name = $_SERVER["SCRIPT_NAME"];
        }
        if (isset($_SERVER["PATH_INFO"])) {
            $this->path_info = $_SERVER["PATH_INFO"];
        }
        if (isset($_SERVER["SCRIPT_NAME"])) {
            $this->root = dirname($_SERVER['SCRIPT_NAME']);
        }
        if (isset($_SERVER["REQUEST_METHOD"])) {
            $this->method = $_SERVER["REQUEST_METHOD"];
        }
        $this->get = $_GET;
        $this->post = $_POST;
    }
}