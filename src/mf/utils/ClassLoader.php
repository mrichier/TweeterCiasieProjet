<?php

namespace mf\utils;

class ClassLoader
{
    private $prefix;
    private $verbose;

    public function __construct($prefix = '', $verbose = false) {
        if (!empty($prefix)) {
            $prefix .= DIRECTORY_SEPARATOR;
        }
        $this->prefix = $prefix;
        $this->verbose = $verbose;
        $this->register();
    }

    public function loadClass($className) {
        $classPath = $this->prefix
            .trim(
                str_replace('\\', DIRECTORY_SEPARATOR, $className))
            .'.php';

        if (file_exists($classPath)) {
            require_once($classPath);
        } else if ($this->verbose) {
            echo "$classPath doesn't exist.\n" ;
        }
    }

    private function register() {
        spl_autoload_register("\\mf\\utils\\ClassLoader::loadClass");
    }
}