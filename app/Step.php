<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Step extends Model
{
    use SoftDeletes;
    protected $fillable = ['name', 'description', 'todolist_id', 'order_in_todolist', 'user_id'];

    public function todolist() {
        return $this->belongsTo(Todolist::class);
    }

    public function tasks() {
        return $this->hasMany(Task::class);
    }

    public function user() {
        return $this->belongsTo(User::class);
    }
}
