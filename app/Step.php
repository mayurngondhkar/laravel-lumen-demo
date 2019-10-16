<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Step extends Model
{
    use SoftDeletes;
    protected $fillable = ['name', 'description', 'todolist_id', 'order_in_todolist'];

    public function todolist() {
        return $this->belongsTo('App/Todolist');
    }

    public function tasks() {
        return $this->hasMany('App/Task');
    }
}
