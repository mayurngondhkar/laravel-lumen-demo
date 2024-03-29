<?php

use Illuminate\Database\Seeder;

class StatesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $state = new \App\State([
            'name' => 'Active',
        ]);
        $state->save();

        $state = new \App\State([
            'name' => 'Completed',
        ]);
        $state->save();
    }
}
