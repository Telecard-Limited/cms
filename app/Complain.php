<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;

class Complain extends Model
{
    use LogsActivity;
    use SoftDeletes;
    protected static $logAttributes = ['*'];

    public function getComplainNumber()
    {
        return str_pad($this->id, 8, "0", STR_PAD_LEFT);
    }

    public function ticket_status()
    {
        return $this->belongsTo('App\TicketStatus', 'ticket_status_id', 'id');
    }

    public function outlet()
    {
        return $this->belongsTo('App\Outlet');
    }

    public function issues()
    {
        return $this->belongsToMany('App\Issue', 'complain_issue');
    }

    public function customer()
    {
        return $this->belongsTo('App\Customer', 'customer_id', 'id');
    }

    public function created_by()
    {
        return $this->belongsTo('App\User', 'user_id', 'id');
    }

    public function message_responses()
    {
        return $this->hasMany('App\MessageResponse');
    }

    public function message_recipients()
    {
        return $this->belongsToMany('App\MessageRecipient', 'complain_message_recipient');
    }
}
