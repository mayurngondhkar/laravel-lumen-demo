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
        $toDoOrderUserData = [
            [1, 1],
            [1, 2],
            [1, 3],
            [1, 4],
            [1, 5],
            [2, 1],
            [2, 2],
            [2, 3],
            [2, 4],
            [2, 5],
        ];
        foreach ($toDoOrderUserData as $datum) {
            $this->createToDoListItem($datum[0], $datum[1]);
        }
    }

    private function createToDoListItem($user_id, $order) {
        $todolist = new \App\Todolist([
            'name' => 'Task Name ' . Str::random(10),
            'description' => 'Task Description ' . Str::random(5),
            'order' => $order,
            'user_id' => $user_id,
        ]);
        $todolist->save();
    }
}
