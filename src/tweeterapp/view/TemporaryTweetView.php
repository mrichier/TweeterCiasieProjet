<?php

namespace tweeterapp\view;
use mf\router\Router;

/**
 * Class TemporaryTweetView
 * Temporary class to be deleted, which exists to allow better
 * readability of tweeterController before starting to work on views.
 */
class TemporaryTweetView
{
    /**
     * Html to display a tweet in a line
     */
    public static function lineTweetHtml($tweet, $full = false) {
        //If full === false then don't display number of likes
        $authorRoute = Router::urlFor("userTweets", array("id"=>$tweet->author));
        $tweetRoute = Router::urlFor("tweet", array("id"=>$tweet->id));
        return "<p><a href='$tweetRoute'>$tweet->text</a> |
                    <a href='$authorRoute'>".$tweet->author()->first()->fullname."</a>
                    , $tweet->created_at"
                    . ($full !== false ? " : $tweet->score likes." : ".")
                ."</p>";
    }

    /**
     * Html to display a user in a line
     */
    public static function lineUserHtml($user) {
        $userRoute = Router::urlFor("userTweets", array("id"=>$user->id));
        return "<p>
                    <a href='$userRoute'>$user->fullname</a>
                     @$user->username | $user->followers followers.
                </p>\n";
    }
}