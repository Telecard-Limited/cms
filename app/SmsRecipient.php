<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;

class SmsRecipient extends Model
{
    use LogsActivity;
    use SoftDeletes;

    protected static $logAttributes = ['*'];
    protected $fillable = ['name', 'number', 'desc'];

    public function sms_recipientable()
    {
        return $this->morphTo();
    }
}
