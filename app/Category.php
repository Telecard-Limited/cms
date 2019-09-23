<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;

class Category extends Model
{
    use LogsActivity;
    use SoftDeletes;

    protected static $logAttributes = ['*'];
    protected $fillable = ['name', 'status'];

    public function issues()
    {
        return $this->hasMany('App\Issue');
    }
}
