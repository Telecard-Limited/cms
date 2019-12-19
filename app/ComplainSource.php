<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ComplainSource extends Model
{
    protected $fillable = ['name', 'description'];

    public function complains()
    {
        return $this->hasMany(Complain::class);
    }
}
