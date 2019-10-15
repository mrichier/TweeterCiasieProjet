<?php

namespace mf\router;

abstract class AbstractRouter implements IRouter {

    /*   Une instance de HttpRequest */

    protected $http_req = null;

    /*
     * Attribut statique qui stocke les routes possibles de l'application
     *
     * - Une route est reprÃ©sentÃ©e par un tableau :
     *       [ le controlleur, la methode, niveau requis ]
     *
     * - Chaque route est stokÃ¨e dans le tableau sous la clÃ© qui est son URL
     *
     */

    static protected $routes = array ();

    /*
     * Attribut statique qui stocke les alias des routes
     *
     * - Chaque URL est stockÃ© dans une case ou la clÃ© est son alias
     *
     */

    static protected $aliases = array ();

    /*
     * Un constructeur
     *
     *  - initialiser l'attribut httpRequest
     *
     */

    public function __construct(){
        $this->http_req = new \mf\utils\HttpRequest();
    }


}
