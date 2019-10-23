<?php

namespace tweeterapp\view;

use mf\router\Router;
use mf\view\TweeterViewPageEnum;

class TweeterView extends \mf\view\AbstractView
{

    /* Constructeur
    *
    * Appelle le constructeur de la classe parent
    */
    public function __construct($data)
    {
        parent::__construct($data);
    }

    /* MÃ©thode renderHeader
     *
     *  Retourne le fragment HTML de l'entÃªte (unique pour toutes les vues)
     */
    private function renderHeader()
    {
        $homeUrl = Router::urlFor("home");
        $loginUrl = '#'; //TODO
        $addTweetUrl = '#'; //TODO
        return <<<HEADER
        <h1>MiniTweeTR</h1>
        <nav>
            <a href="${homeUrl}">&#8962;</a>
            <a href="${loginUrl}">&#9094;</a>
            <a href="${addTweetUrl}">+</a>
        </nav>
HEADER;
    }

    /* MÃ©thode renderFooter
     *
     * Retourne le fragment HTML du bas de la page (unique pour toutes les vues)
     */
    private function renderFooter()
    {
        return 'La super app créée en Licence Pro CIASIE &copy;2018';
    }

    /* MÃ©thode renderHome
     *
     * Vue de la fonctionalitÃ© afficher tous les Tweets.
     *
     */
    private function renderHome()
    {

        /*
         * Retourne le fragment HTML qui affiche tous les Tweets.
         *
         * L'attribut $this->data contient un tableau d'objets tweet.
         *
         */
        $tweets = "";
        foreach ($this->data as $tweet) {
            $tweets .= $this->renderTweetAsLine($tweet);
        }
        return <<<EOT
    ${tweets}
EOT;
    }

    /* MÃ©thode renderUeserTweets
     *
     * Vue de la fonctionalitÃ© afficher tout les Tweets d'un utilisateur donnÃ©.
     *
     */

    private function renderUserTweets()
    {

        /*
         * Retourne le fragment HTML pour afficher
         * tous les Tweets d'un utilisateur donnÃ©.
         *
         * L'attribut $this->data contient un objet User.
         *
         */
        $user = $this->data;
        //render the tweets
        $tweets = "";
        foreach ($user->tweets()->get() as $tweet) {
            $tweets .= $this->renderTweetAsLine($tweet);
        }
        return <<<EOT
    ${tweets}
EOT;
    }

    /* MÃ©thode renderViewTweet
     *
     * RrÃ©alise la vue de la fonctionnalitÃ© affichage d'un tweet
     *
     */

    private function renderViewTweet()
    {

        /*
         * Retourne le fragment HTML qui rÃ©alise l'affichage d'un tweet
         * en particuliÃ©
         *
         * L'attribut $this->data contient un objet Tweet
         *
         */
        $tweet = $this->renderTweetAsFullBlock($this->data);
        return <<<EOT
    ${tweet}
EOT;
    }


    /* MÃ©thode renderPostTweet
     *
     * Realise la vue de rÃ©gider un Tweet
     *
     */
    protected function renderPostTweet()
    {

        /* MÃ©thode renderPostTweet
         *
         * Retourne la framgment HTML qui dessine un formulaire pour la rÃ©daction
         * d'un tweet, l'action du formulaire est la route "send"
         *
         */

    }


    /* MÃ©thode renderBody
     *
     * Retourne la framgment HTML de la balise <body> elle est appelÃ©e
     * par la mÃ©thode hÃ©ritÃ©e render.
     *
     */

    protected function renderBody($selector = null)
    {

        /*
         * voire la classe AbstractView
         *
         */
        switch ($selector) {
            case TweeterViewPageEnum::HOME :
                $main = $this->homeTemplate();
                return $this->mainTemplate($main);
            case TweeterViewPageEnum::USER_TWEETS :
                $main = $this->userTweetsTemplate();
                return $this->mainTemplate($main);
            case TweeterViewPageEnum::TWEET :
                $main = $this->tweetTemplate();
                return $this->mainTemplate($main);
            case TweeterViewPageEnum::POST_TWEET:
                return $this->postTemplate();
            default:
                return "<div class='404'>404 NOT FOUND</div>";
                break;
        }


    }


    protected function renderTweetAsLine($tweet)
    {
        $authorRoute = Router::urlFor("userTweets", array("id" => $tweet->author));
        $tweetRoute = Router::urlFor("tweet", array("id" => $tweet->id));
        return "<article><span class='tweet-text'><a href='$tweetRoute'>$tweet->text</a></span>
                    <span class='author'><a href='$authorRoute'>" . $tweet->author()->first()->fullname . "</a></span>
                     <span class='creation-date'>$tweet->created_at</span></article>";
    }

    protected function renderTweetAsFullBlock($tweet)
    {
        $authorRoute = Router::urlFor("userTweets", array("id" => $tweet->author));
        $tweetRoute = Router::urlFor("tweet", array("id" => $tweet->id));
        return "<article><span class='tweet-text'><a href='$tweetRoute'>$tweet->text</a></span>
                    <span class='author'><a href='$authorRoute'>" . $tweet->author()->first()->fullname . "</a></span>
                    <span class='tweet-score'>$tweet->score likes</span>
                    <span class='creation-date'>$tweet->created_at</span>
                    <span class='edit-date'>$tweet->updated_at</span></article>";
    }

    /**
     * Html to display a user in a line
     */
    protected function renderUserAsLine($user)
    {
        $userRoute = Router::urlFor("userTweets", array("id" => $user->id));
        return "<article>
                    <a href='$userRoute'><span class='user-fullname'>$user->fullname</span>
                     <span class='user-name'>@$user->username</span></a> <span class='user-follower-count'>$user->followers followers.</span>
                </article>\n";
    }

    public function renderTweetForm()
    {
        $app_root = (new \mf\utils\HttpRequest())->root;
        return <<<FORM
    <form class="forms" action="${app_root}/main.php/send/" method="post">
        <textarea name="tweet-text" class="forms-text"
            placeholder="Partagez vos pensées"></textarea>
            <input type="submit" name="submit" class="forms-button">
    </form>
FORM;

    }

    protected function renderMenu()
    {

    }

    protected function formatDate($timestamp)
    {
        //TODO
    }


    /**
     *
     * TEMPLATES
     *
     **/


    protected function mainTemplate($main, $header = '', $footer = '')
    {
        $header = $this->renderHeader();
        $footer = $this->renderFooter();
        return <<<BODY
    <header>${header}</header>
    <main>
        <aside></aside>
        ${main}
    </main>
    <footer>${footer}</footer>
BODY;
    }

    protected function homeTemplate()
    {
        $tweetList = $this->renderHome();
        return <<<HOME
        <section class="tweet-list">${tweetList}</section>
HOME;
    }

    protected function userTweetsTemplate()
    {
        $user = $this->data;
        $userLine = $this->renderUserAsLine($user);
        $tweetList = $this->renderUserTweets();
        return <<<USER
        <section class="user-block">${userLine}</section>
        <section class="tweet-list">${tweetList}</section>
USER;
    }

    protected function tweetTemplate()
    {
        $tweet = $this->renderTweetAsFullBlock($this->data);
        return <<<TWEET
        <section class="tweet-list">${tweet}</section>
TWEET;
    }

    protected function postTemplate()
    {
        $header = $this->renderHeader();
        $footer = $this->renderFooter();
        $form = $this->renderTweetForm();
        return <<<BODY
    <header>${header}</header>
    <main>
        ${form}
    </main>
    <footer>${footer}</footer>
BODY;
    }
}
