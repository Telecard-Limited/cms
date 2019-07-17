<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SmsRecipient extends Model
{
    protected $fillable = ['name', 'number', 'desc'];

    public function sms_recipientable()
    {
        return $this->morphTo();
    }
}
