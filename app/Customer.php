<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;

class Customer extends Model
{
    use LogsActivity;
    use SoftDeletes;
    protected static $logAttributes = ['*'];
    protected $fillable = ['name', 'number', 'active'];

    public function ratings()
    {
        return $this->hasMany('App\Rating');
    }

    public function complains()
    {
        return $this->hasMany('App\Complain');
    }
}
