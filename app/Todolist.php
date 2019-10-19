<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Todolist extends Model
{
    use SoftDeletes;
    protected $fillable = ['name', 'description', 'order', 'user_id'];

    public function steps() {
        return $this->hasMany(Step::class);
    }

    public function user() {
        return $this->belongsTo(User::class);
    }

    public static function boot()
    {
        parent::boot();

        static::deleted(function ($todolist) {
            $todolist->steps()->delete();
        });
    }
}
