<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Outlet extends Model
{
    protected $fillable = ['name', 'active'];
    protected $casts = [
        'active' => 'boolean'
    ];

    public function sms_recipients()
    {
        return $this->morphMany('App\SmsRecipient', 'sms_recipientable');
    }

    public function complains()
    {
        return $this->morphMany('App\Complain', 'complainable');
    }
}
