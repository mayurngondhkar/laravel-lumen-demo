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
        // User Id, To do list id, Order in to do list
        $stepData = [
            [1, 1, 1],
            [1, 1, 2],
            [1, 1, 3],
            [1, 1, 4],
            [1, 1, 5],
            [1, 2, 1],
            [1, 2, 2],
            [1, 2, 3],
            [1, 2, 4],
            [1, 2, 5],
            [2, 6, 1],
            [2, 6, 2],
            [2, 6, 3],
            [2, 6, 4],
            [2, 6, 5],
            [2, 7, 1],
            [2, 7, 2],
            [2, 7, 3],
            [2, 7, 4],
            [2, 7, 5],
        ];

        foreach ($stepData as $datum) {
            $this->createStepItem($datum[0], $datum[1], $datum[2]);
        }
//        $step = new \App\Step([
//            'name' => 'Step Name ' . Str::random(10),
//            'description' => 'Step Description ' . Str::random(5),
//            'todolist_id' => '1',
//            'order_in_todolist' => 1,
//            'user_id' => '1'
//        ]);
//        $step->save();
//
//        $step = new \App\Step([
//            'name' => 'Step Name ' . Str::random(10),
//            'description' => 'Step Description ' . Str::random(5),
//            'todolist_id' => '1',
//            'order_in_todolist' => 2,
//            'user_id' => '1'
//        ]);
//
//        $step->save();
//        $step = new \App\Step([
//            'name' => 'Step Name ' . Str::random(10),
//            'description' => 'Step Description ' . Str::random(5),
//            'todolist_id' => '2',
//            'order_in_todolist' => 1,
//            'user_id' => '2'
//        ]);
//        $step->save();
//
//        $step = new \App\Step([
//            'name' => 'Step Name ' . Str::random(10),
//            'description' => 'Step Description ' . Str::random(5),
//            'todolist_id' => '4',
//            'order_in_todolist' => 4,
//            'user_id' => '2'
//        ]);
//        $step->save();
//
//        $step = new \App\Step([
//            'name' => 'Step Name ' . Str::random(10),
//            'description' => 'Step Description ' . Str::random(5),
//            'todolist_id' => '5',
//            'order_in_todolist' => 5,
//            'user_id' => '2'
//        ]);
//        $step->save();
    }

    private function createStepItem($user_id, $toDoListId, $orderInTodoList) {
        $step = new \App\Step([
            'name' => 'Step Name ' . Str::random(10),
            'description' => 'Step Description ' . Str::random(5),
            'todolist_id' => $toDoListId,
            'order_in_todolist' => $orderInTodoList,
            'user_id' => $user_id
        ]);
        $step->save();
    }
}
