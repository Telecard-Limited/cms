<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;

class Rating extends Model
{
    use LogsActivity;
    use SoftDeletes;

    protected static $logAttributes = ['*'];

    public function getRatingNumber()
    {
        return str_pad($this->id, 8, "0", STR_PAD_LEFT);
    }

    public function customer()
    {
        return $this->belongsTo('App\Customer');
    }

    public function outlet()
    {
        return $this->belongsTo('App\Outlet');
    }

    public function issues()
    {
        return $this->belongsToMany('App\Issue', 'issue_rating');
    }

    public function ticket_status()
    {
        return $this->belongsTo('App\TicketStatus');
    }

    public function created_by()
    {
        return $this->belongsTo('App\User','user_id', 'id');
    }
}
