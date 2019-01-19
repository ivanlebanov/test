<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
     protected $fillable = [
         'first_name', 'last_name', 'id', 'email'
     ];

     public function orders(){
       return $this->hasMany('App\Order', 'user_id', 'id');
    }
}
