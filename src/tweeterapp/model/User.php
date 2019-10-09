<?php

namespace tweeterapp\model;

class User extends \Illuminate\Database\Eloquent\Model
{
    protected $table = 'user';
    protected $primaryKey = 'id';
    public $timestamps = false;

    public function tweets() {
        return $this->hasMany('tweeterapp\model\Tweet', 'author');
    }

    public function liked() {
        return $this->belongsToMany('tweeterapp\model\Tweet', 'tweeterapp\model\Like', 'user_id', 'tweet_id');
    }

    public function followedBy() {
        return $this->belongsToMany('tweeterapp\model\User', 'tweeterapp\model\Follow', 'followee', 'follower');
    }

    public function follows() {
        return $this->belongsToMany('tweeterapp\model\User', 'tweeterapp\model\Follow', 'follower', 'followee');
    }
}