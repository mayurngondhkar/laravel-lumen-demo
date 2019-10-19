<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call(UsersTableSeeder::class);
        $this->call(TodolistTableSeeder::class);
        $this->call(StepsTableSeeder::class);
        $this->call(TasksTableSeeder::class);
        $this->call(StatesTableSeeder::class);

        factory(App\Todolist::class, 4)
            ->create()
            ->each(function ($todolist) {
                factory(App\Step::class, 4)
                    ->create(['todolist_id' => $todolist['id'], 'user_id' => $todolist['user_id']])
                    ->each(function ($step) {
                        factory(App\Task::class, 4)
                            ->create(['user_id' => $step['user_id'], 'step_id' => $step['id']]);
                    });
            });
    }
}
