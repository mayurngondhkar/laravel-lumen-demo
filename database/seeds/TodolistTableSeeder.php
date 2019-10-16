<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class TodolistTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $todolist = new \App\Todolist([
            'name' => 'Task Name ' . Str::random(10),
            'description' => 'Task Description ' . Str::random(5),
            'order' => 1
        ]);
        $todolist->save();

        $todolist = new \App\Todolist([
            'name' => 'Task Name ' . Str::random(10),
            'description' => 'Task Description ' . Str::random(5),
            'order' => 2
        ]);
        $todolist->save();

        $todolist = new \App\Todolist([
            'name' => 'Task Name ' . Str::random(10),
            'description' => 'Task Description ' . Str::random(5),
            'order' => 3
        ]);
        $todolist->save();

        $todolist = new \App\Todolist([
            'name' => 'Task Name ' . Str::random(10),
            'description' => 'Task Description ' . Str::random(5),
            'order' => 4
        ]);
        $todolist->save();

        $todolist = new \App\Todolist([
            'name' => 'Task Name ' . Str::random(10),
            'description' => 'Task Description ' . Str::random(5),
            'order' => 5
        ]);
        $todolist->save();
    }
}
