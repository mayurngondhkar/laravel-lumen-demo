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
        // User Id, Step id, Order in step list, State Id
        $taskData = [
            [1, 1, 1, 1],
            [1, 1, 2, 1],
            [1, 1, 3, 1],
            [1, 1, 4, 1],
            [1, 1, 5, 1],
            [1, 2, 1, 1],
            [1, 2, 2, 1],
            [1, 2, 3, 1],
            [1, 2, 4, 1],
            [1, 2, 5, 1],
            [1, 1, 1, 1],
            [1, 1, 2, 1],
            [1, 1, 3, 1],
            [1, 1, 4, 1],
            [1, 1, 5, 1],
            [1, 2, 1, 1],
            [1, 2, 2, 1],
            [1, 2, 3, 1],
            [1, 2, 4, 1],
            [2, 6, 1, 1],
            [2, 6, 2, 1],
            [2, 6, 3, 1],
            [2, 6, 4, 1],
            [2, 6, 5, 1],
            [2, 7, 1, 1],
            [2, 7, 2, 1],
            [2, 7, 3, 1],
            [2, 7, 4, 1],
            [2, 7, 5, 1],
            [2, 6, 1, 1],
            [2, 6, 2, 1],
            [2, 6, 3, 1],
            [2, 6, 4, 1],
            [2, 6, 5, 1],
            [2, 7, 1, 1],
            [2, 7, 2, 1],
            [2, 7, 3, 1],
            [2, 7, 4, 1],
            [2, 7, 5, 1],
        ];

        foreach ($taskData as $datum) {
            $this->createTaskItem($datum[0], $datum[1], $datum[2], $datum[3]);
        }

    }

    private function createTaskItem($userId, $stepId, $orderInStep, $stateId) {
        $task = new \App\Task([
            'name' => 'Task Name ' . Str::random(10),
            'description' => 'Task Description ' . Str::random(5),
            'state_id' => $stateId,
            'step_id' => $stepId,
            'order_in_steplist' => $orderInStep,
            'user_id' => $userId
        ]);
        $task->save();
    }
}
