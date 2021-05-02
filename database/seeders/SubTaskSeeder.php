<?php

namespace Database\Seeders;

use App\Models\SubTask;
use Illuminate\Database\Seeder;

class SubTaskSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        SubTask::factory(10)->create();
    }
}
