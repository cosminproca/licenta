<?php

namespace Database\Factories;

use App\Models\TaskList;
use App\Models\Team;
use Illuminate\Database\Eloquent\Factories\Factory;

class TaskListFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = TaskList::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'team_id' => $this->faker->randomNumber(),
            'name' => $this->faker->randomElement(['Backlog', 'Progress', 'Completed'])
        ];
    }
}
