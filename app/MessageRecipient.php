<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MessageRecipient extends Model
{
    protected $fillable = ['name', 'numbers'];

    public function complains()
    {
        return $this->belongsToMany('App\Complain', 'complain_message_recipient');
    }
}
