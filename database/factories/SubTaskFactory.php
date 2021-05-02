<?php

namespace Database\Factories;

use App\Models\Task;
use App\Models\SubTask;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class SubTaskFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = SubTask::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'task_id' => Task::factory(),
            'assignee_id' => User::factory(),
            'name' => 'Test ' . $this->faker->randomDigit(),
            'description' => $this->faker->text,
            'due_date' => $this->faker->dateTimeThisYear
        ];
    }
}
