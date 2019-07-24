<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;

class Department extends Model
{
    use LogsActivity;
    use SoftDeletes;
    protected static $logAttributes = ['*'];
    protected $fillable = ['name', 'active', 'desc'];

    public function complains()
    {
        return $this->hasMany('App\Complain');
    }
}
