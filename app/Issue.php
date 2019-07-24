<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;

class Issue extends Model
{
    use LogsActivity;
    use SoftDeletes;

    protected static $logAttributes = ['*'];
    protected $fillable = ['name', 'desc', 'active'];

    public function complains()
    {
        return $this->belongsToMany('App\Complain', 'complain_issue');
    }

    public function ratings()
    {
        return $this->belongsToMany('App\Rating', 'issue_rating');
    }
}
