<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MessageResponse extends Model
{
    protected $fillable = ['receiver', 'response', 'code', 'status', 'message'];

    public function complain()
    {
        return $this->belongsTo('App\Complain');
    }
}
