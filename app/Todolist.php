<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Todolist extends Model
{
    use SoftDeletes;
    protected $fillable = ['name', 'description', 'order'];

    public function steps() {
        return $this->hasMany('App/Step');
    }
}