<?php

namespace tweeterapp\control;

/* Classe TweeterController :
 *
 * RÃ©alise les algorithmes des fonctionnalitÃ©s suivantes:
 *
 *  - afficher la liste des Tweets
 *  - afficher un Tweet
 *  - afficher les tweet d'un utilisateur
 *  - afficher la le formulaire pour poster un Tweet
 *  - afficher la liste des utilisateurs suivis
 *  - Ã©valuer un Tweet
 *  - suivre un utilisateur
 *
 */

use mf\router\Router;
use tweeterapp\model\Tweet;
use tweeterapp\model\User;

class TweeterController extends \mf\control\AbstractController {


    /* Constructeur :
     *
     * Appelle le constructeur parent
     *
     * c.f. la classe \mf\control\AbstractController
     *
     */

    public function __construct(){
        parent::__construct();
    }


    /* MÃ©thode viewHome :
     *
     * RÃ©alise la fonctionnalitÃ© : afficher la liste de Tweet
     *
     */

    public function viewHome(){

        /* Algorithme :
         *
         *  1 RÃ©cupÃ©rer tout les tweet en utilisant le modÃ¨le Tweet
         *  2 Parcourir le rÃ©sultat
         *      afficher le text du tweet, l'auteur et la date de crÃ©ation
         *  3 Retourner un block HTML qui met en forme la liste
         *
         */
        //Display all tweets
        foreach(Tweet::select()->get() as $tweet) {
            echo "$tweet->text | ".$tweet->author()->first()->fullname.", $tweet->created_at\n";
        }
    }


    /* MÃ©thode viewTweet :
     *
     * RÃ©alise la fonctionnalitÃ© afficher un Tweet
     *
     */

    public function viewTweet(){

        /* Algorithme :
         *
         *  1 L'identifiant du Tweet en question est passÃ© en paramÃ¨tre (id)
         *      d'une requÃªte GET
         *  2 RÃ©cupÃ©rer le Tweet depuis le modÃ¨le Tweet
         *  3 Afficher toutes les informations du tweet
         *      (text, auteur, date, score)
         *  4 Retourner un block HTML qui met en forme le Tweet
         *
         *  Erreurs possibles : (*** Ã  implanter ultÃ©rieurement ***)
         *    - pas de paramÃ¨tre dans la requÃªte
         *    - le paramÃ¨tre passÃ© ne correspond pas a un identifiant existant
         *    - le paramÃ¨tre passÃ© n'est pas un entier
         *
         */

        if (isset($this->request->get["id"])) {
            $id = $this->request->get["id"];
            $tweet = Tweet::select()->where('id', '=', $id)->first();
            //html
            echo "<p>$tweet->text | <a href=\""
                    .Router::urlFor("userTweets", array("id"=>$tweet->author)) .'">'
                    .$tweet->author()->first()->fullname.
                '</a>'
                .", $tweet->created_at : $tweet->score likes.</p>";
        }
    }


    /* MÃ©thode viewUserTweets :
     *
     * RÃ©alise la fonctionnalitÃ© afficher les tweet d'un utilisateur
     *
     */

    public function viewUserTweets(){

        /*
         *
         *  1 L'identifiant de l'utilisateur en question est passÃ© en
         *      paramÃ¨tre (id) d'une requÃªte GET
         *  2 RÃ©cupÃ©rer l'utilisateur et ses Tweets depuis le modÃ¨le
         *      Tweet et User
         *  3 Afficher les informations de l'utilisateur
         *      (non, login, nombre de suiveurs)
         *  4 Afficher ses Tweets (text, auteur, date)
         *  5 Retourner un block HTML qui met en forme la liste
         *
         *  Erreurs possibles : (*** Ã  implanter ultÃ©rieurement ***)
         *    - pas de paramÃ¨tre dans la requÃªte
         *    - le paramÃ¨tre passÃ© ne correspond pas a un identifiant existant
         *    - le paramÃ¨tre passÃ© n'est pas un entier
         *
         */

        if (isset($this->request->get["id"])) {
            $id = $this->request->get["id"];
            $user = User::select()->where('id', '=', $id)->first();
            echo "$user->fullname @$user->username | $user->followers followers.<br>\n";
            $tweets = $user->tweets()->get();
            foreach ($tweets as $tweet) {
                echo "<a href='"
                    .Router::urlFor("tweet", array("id"=>$tweet->id)).
                    "'><p>$tweet->text | ".
                    $tweet->author()->first()->fullname.
                    ", $tweet->created_at : $tweet->score likes.</p></a>\n";
            }
        }

    }
}
