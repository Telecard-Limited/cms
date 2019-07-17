<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Complain extends Model
{
    public function getIdAttribute($value)
    {
        return str_pad($value, 8, '0', STR_PAD_LEFT);
    }

    public function complainable()
    {
        return $this->morphTo();
    }

    public function ticket_status()
    {
        return $this->belongsTo('App\TicketStatus', 'ticket_status_id', 'id');
    }

    public function issue()
    {
        return $this->belongsTo('App\Issue', 'issue_id', 'id');
    }

    public function created_by()
    {
        return $this->belongsTo('App\User');
    }
}
