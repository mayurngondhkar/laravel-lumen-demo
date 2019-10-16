<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class TasksTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $task = new \App\Task([
            'name' => 'Task Name ' . Str::random(10),
            'description' => 'Task Description ' . Str::random(5),
            'state_id' => 1,
            'step_id' => 1,
            'order_in_steplist' => 1
        ]);
        $task->save();

        $task = new \App\Task([
            'name' => 'Task Name ' . Str::random(10),
            'description' => 'Task Description ' . Str::random(5),
            'state_id' => 2,
            'step_id' => 2,
            'order_in_steplist' => 2
        ]);
        $task->save();

        $task = new \App\Task([
            'name' => 'Task Name ' . Str::random(10),
            'description' => 'Task Description ' . Str::random(5),
            'state_id' => 3,
            'step_id' => 3,
            'order_in_steplist' => 3
        ]);
        $task->save();

        $task = new \App\Task([
            'name' => 'Task Name ' . Str::random(10),
            'description' => 'Task Description ' . Str::random(5),
            'state_id' => 4,
            'step_id' => 4,
            'order_in_steplist' => 4
        ]);
        $task->save();

        $task = new \App\Task([
            'name' => 'Task Name ' . Str::random(10),
            'description' => 'Task Description ' . Str::random(5),
            'state_id' => 5,
            'step_id' => 5,
            'order_in_steplist' => 5
        ]);
        $task->save();
    }
}
