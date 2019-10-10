<?php


namespace mf\router;


class Router extends AbstractRouter
{
    public function __construct()
    {
        parent::__construct();
    }

    public function addRoute($name, $url, $ctrl, $mth)
    {
        self::$routes[$url] = array($ctrl, $mth);
        self::$aliases[$name] = $url;
    }

    public function setDefaultRoute($url)
    {
        self::$aliases["default"] = $url;
    }

    public function run()
    {
        $url = $this->http_req->path_info;
        if (empty($url) && isset(self::$aliases["default"])) {
            $url = self::$aliases["default"];
        } else if (!isset(self::$routes[$url])) {
            echo "cant access $url\n";
            return;
        }

        $className = self::$routes[$url][0];
        $methodName = self::$routes[$url][1];
        $ctrl = new $className;
        $ctrl->{$methodName}();
    }

    public static function urlFor($route_name, $param_list = [])
    {
        //Getting path
        if (isset(self::$aliases[$route_name])) {
            $url = self::$aliases[$route_name];
        } else if (isset(self::$routes[$route_name])) {
            $url = self::$routes[$route_name];
        }

        if (isset($url)) {
            //Adding get parameters
            if (!empty($param_list)) {
                $url .= "?";
                foreach ($param_list as $key=>$val) {
                    $url.= "$key=$val&";
                }
                //Removing unecessary & char
                $url = rtrim($url, "&");
            }
        } else {
            echo $route_name;
            $url = "404";
        }
        return WEBSITE_PATH_PREFIX.$url;
    }

    public static function executeRoute($alias) {
        if (isset(self::$aliases[$alias])) {
            $url = self::$aliases[$alias];
        }

        $className = self::$routes[$url][0];
        $methodName = self::$routes[$url][1];
        $ctrl = new $className;
        $ctrl->{$methodName}();
    }

}