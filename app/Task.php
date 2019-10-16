<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Task extends Model
{
    use SoftDeletes;
    protected $fillable = ['name', 'description', 'state_id', 'step_id', 'order_in_steplist'];

    public function step() {
        return $this->belongsTo('App/Step');
    }

    public function state() {
        return $this->belongsTo('App/State');
    }
}
