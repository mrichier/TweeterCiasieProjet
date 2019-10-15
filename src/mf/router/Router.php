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

    /**
     * Run a route requested with an http access to a registered "url" (= path after the website prefix)
     */
    public function run()
    {
        $url = $this->http_req->path_info;
        if ($this->urlIsEmpty($url) && isset(self::$aliases["default"])) {
            //If url was left empty and there is a default route
            $url = self::$aliases["default"];
        } else if (!isset(self::$routes[$url])) {
            //If 404
            echo "404 - cant access $url\n";
            return;
        }

        $className = self::$routes[$url][0];
        $methodName = self::$routes[$url][1];
        $ctrl = new $className;
        $ctrl->{$methodName}();
    }

    /** Get the url of a route (useful for links)
     * @param $route_name
     * @param array $param_list List of GET parameters to add to the url
     * @return string ready-to-use url
     */
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
                foreach ($param_list as $key => $val) {
                    $url .= "$key=$val&";
                }
                //Removing unecessary & char
                $url = rtrim($url, "&");
            }
        } else {
            echo $route_name;
            $url = "404";
        }
        return WEBSITE_PATH_PREFIX . $url;
    }

    /** "Manually" run a route
     * @param $alias
     */
    public static function executeRoute($alias)
    {
        if (isset(self::$aliases[$alias])) {
            $url = self::$aliases[$alias];
        }

        $className = self::$routes[$url][0];
        $methodName = self::$routes[$url][1];
        $ctrl = new $className;
        $ctrl->{$methodName}();
    }

    /** Check if a given url is empty
     * @param $url
     * @return bool true if url is empty, false otherwise
     */
    private function urlIsEmpty($url)
    {
        return empty($url) || trim($url) === '/';
    }

}