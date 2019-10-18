<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class StepsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $step = new \App\Step([
            'name' => 'Step Name ' . Str::random(10),
            'description' => 'Step Description ' . Str::random(5),
            'todolist_id' => '1',
            'order_in_todolist' => 1,
            'user_id' => '1'
        ]);
        $step->save();

        $step = new \App\Step([
            'name' => 'Step Name ' . Str::random(10),
            'description' => 'Step Description ' . Str::random(5),
            'todolist_id' => '1',
            'order_in_todolist' => 2,
            'user_id' => '1'
        ]);

        $step->save();
        $step = new \App\Step([
            'name' => 'Step Name ' . Str::random(10),
            'description' => 'Step Description ' . Str::random(5),
            'todolist_id' => '2',
            'order_in_todolist' => 1,
            'user_id' => '2'
        ]);
        $step->save();

        $step = new \App\Step([
            'name' => 'Step Name ' . Str::random(10),
            'description' => 'Step Description ' . Str::random(5),
            'todolist_id' => '4',
            'order_in_todolist' => 4,
            'user_id' => '2'
        ]);
        $step->save();

        $step = new \App\Step([
            'name' => 'Step Name ' . Str::random(10),
            'description' => 'Step Description ' . Str::random(5),
            'todolist_id' => '5',
            'order_in_todolist' => 5,
            'user_id' => '2'
        ]);
        $step->save();
    }
}
