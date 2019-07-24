<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;

class Outlet extends Model
{
    use LogsActivity;

    use SoftDeletes;
    protected static $logAttributes = ['*'];
    protected $fillable = ['name', 'active', 'city', 'desc'];
    protected $casts = [
        'active' => 'boolean'
    ];

    public function complains()
    {
        return $this->hasMany('App\Complain');
    }
}
