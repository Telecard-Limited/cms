<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    protected $fillable = ['name', 'active', 'desc'];

    public function sms_recipients()
    {
        return $this->morphMany('App\SmsRecipient', 'sms_recipientable');
    }

    public function complains()
    {
        return $this->morphMany('App\Complain', 'complainable');
    }
}
