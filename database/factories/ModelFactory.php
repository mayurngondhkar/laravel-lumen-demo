<?php

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| Here you may define all of your model factories. Model factories give
| you a convenient way to create models for testing and seeding your
| database. Just tell the factory how a default model should look.
|
*/

//$factory->define(App\User::class, function (Faker\Generator $faker) {
//    return [
//        'name' => $faker->name,
//        'email' => $faker->email,
//    ];
//});

$factory->define(App\Todolist::class, function (Faker\Generator $faker) {
    static $order = 1;
    echo 1;
    return [
        'name' => 'To Do List Name' . $faker->text(8),
        'description' => 'To Do List Description' . $faker->text(20),
        'order' => $order++,
        'user_id' => $faker->numberBetween(1, 2),
    ];
});

$factory->define(App\Step::class, function (Faker\Generator $faker) {
    static $order = 1;
    return [
        'name' => 'Step Name ' . $faker->text(8),
        'description' => 'Step Description ' . $faker->text(20),
//        'todolist_id' => ,
        'order_in_todolist' => $order++,
//        'user_id' =>
    ];
});

$factory->define(App\Task::class, function (Faker\Generator $faker) {
    static $order = 1;
    return [
        'name' => 'Task Name ' . $faker->text(8),
        'description' => 'Task Description ' . $faker->text(20),
        'state_id' => $faker->numberBetween(0, 1),
//        'step_id' => ,
        'order_in_steplist' => $order++,
//        'user_id' =>
    ];
});
