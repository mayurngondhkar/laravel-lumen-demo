<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Task extends Model
{
    use SoftDeletes;
    protected $fillable = ['name', 'description', 'state_id', 'step_id', 'order_in_steplist', 'user_id'];

    public function step() {
        return $this->belongsTo(Step::class);
    }

    public function state() {
        return $this->belongsTo(State::class);
    }

    public function user() {
        return $this->belongsTo(User::class);
    }
}
