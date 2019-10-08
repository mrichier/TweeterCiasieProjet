<?php

namespace mf\utils;

class ClassLoader
{
    private $prefix;

    public function __construct($prefix = '') {
        if (!empty($prefix)) {
            $prefix .= DIRECTORY_SEPARATOR;
        }
        $this->prefix = $prefix;
        $this->register();
    }

    public function loadClass($className) {
        $classPath = $this->prefix
            .trim(
                str_replace('\\', DIRECTORY_SEPARATOR, $className))
            .'.php';

        if (file_exists($classPath)) {
            require_once($classPath);
        } else {
            echo "$classPath doesn't exist.\n" ;
        }
    }

    private function register() {
        spl_autoload_register("\\mf\\utils\\ClassLoader::loadClass");
    }
}