<?php

use mf\utils\ClassLoader;
use tweeterapp\model\Tweet;
use tweeterapp\model\User;

require_once "src" . DIRECTORY_SEPARATOR . "mf" . DIRECTORY_SEPARATOR . "utils" . DIRECTORY_SEPARATOR . "ClassLoader.php";
new ClassLoader('src'); // Auto-register
require_once "vendor/autoload.php";

$config = array();
$file = str_replace(PHP_EOL, '', file_get_contents("conf" . DIRECTORY_SEPARATOR . "config.ini"));
$rows = explode(',', $file);
foreach ($rows as $value) {
    $row = explode("=>", $value);
    if (empty($row[1])) $row[1] = '';
    $config[$row[0]] = $row[1];
}

$db = new Illuminate\Database\Capsule\Manager();

$db->addConnection($config);
$db->setAsGlobal();
$db->bootEloquent();


//SELECT * FROM
function getAllUsers()
{
    $allUsers = User::select()->get();
    foreach ($allUsers as $v) {
        echo "Id: $v->id, name: $v->fullname\n";
    }
}

function getAllTweets()
{
    $allTweets = User::select()->get();
    foreach ($allTweets as $v) {
        echo "Id: $v->id, text: $v->text\n";
    }
}


//SELECT with conditions
function getAllTweetsSortedByEdit()
{
    $allTweetsSortedByEdit = Tweet::select()->orderBy('updated_at')->get();
    foreach ($allTweetsSortedByEdit as $v) {
        echo "Edit: $v->updated_at, text: $v->text\n";
    }
}

function getAllTweetsWPositiveScore()
{
    $allTweetsWPositiveScore = Tweet::select()->where('score', '>', 0)->get();
    foreach ($allTweetsWPositiveScore as $v) {
        echo "Id: $v->id, text: $v->text\n";
    }
}


//Creating new entries
function createTweet()
{
    $t = new Tweet();
    $t->text = "The Do Nothing Democrats are Con Artists, only looking to hurt the Republican Party and President. Their total focus is 2020, nothing more, and nothing less. The good news is that WE WILL WIN!!!!";
    $t->author = 1;
    //$t->save();
}

function createUser()
{
    $u = new User();
    $u->fullname = "Barrack Obama";
    $u->username = "obamaofficial";
    $u->level = 100;
//    $u->save();
}


//Association 1->*
function getTweetAuthor()
{
    $tweet = Tweet::select()->first();
    echo $tweet->author()->first();
}

function getAllTweetFromAuthor()
{
    $user = User::select()->where('id', '=', 1)->first();
    foreach ($user->tweets()->get() as $v) {
        echo "Id: $v->id, text: $v->text\n";
    }
}


//Association *->*
function getUsersWhoLiked()
{
    $tweet = Tweet::select()->where('id', '=', 63)->first();
    foreach ($tweet->likedBy()->get() as $v) {
        echo "$v->fullname\n";
    }
}

function getAllLikedTweets()
{
    $user = User::select()->where('id', '=', 10)->first();
    foreach ($user->liked()->get() as $v) {
        echo "$v->text\n";
    }
}

function getAllFollowers()
{
    $followee = User::select()->where('id', '=', 9)->first();
    foreach ($followee->followedBy()->get() as $v) {
        echo "$v->fullname\n";
    }
}

function getAllFollowed()
{
    $follower = User::select()->where('id', '=', 10)->first();
    foreach ($follower->follows()->get() as $v) {
        echo "$v->fullname\n";
    }
}
