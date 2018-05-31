<?php


namespace App;

use Illuminate\Database\Eloquent\Model as Eloquent;



class UserProfile extends Eloquent {
    
    protected $fillable = array('nickname', 'user_id');

    protected $table = 'users_profiles';

    public function user() {
        return $this->belongsTo('User');
    }

}