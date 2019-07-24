<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;

class TicketStatus extends Model
{
    use LogsActivity;
    use SoftDeletes;
    protected $fillable = ['name', 'active', 'desc'];

    protected static $logAttributes = ['*'];

    public function complains()
    {
        return $this->hasMany('App\Complain');
    }
}
