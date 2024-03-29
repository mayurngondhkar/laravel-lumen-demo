<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class State extends Model
{
    use SoftDeletes;
    protected $fillable = ['name'];

    public function tasks() {
        return $this->belongsTo(Task::class);
    }
}
