<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'total_price', 'fulfillment_status', 'user_id'
    ];
    public function user(){
      return $this->belongsTo('App\Customer', 'user_id', 'id');
    }
}
