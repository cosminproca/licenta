<?php

namespace Database\Factories;

use App\Enums\TaskPriorityType;
use App\Models\Task;
use App\Models\TaskList;
use App\Models\Team;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class TaskFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Task::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'team_id' => Team::factory(),
            'task_list_id' => TaskList::factory(),
            'assignee_id' => 1,
            'name' => 'Test ' . $this->faker->randomDigit(),
            'description' => $this->faker->text,
            'priority' => $this->faker->randomElement(TaskPriorityType::asArray()),
            'time' => $this->faker->time(),
            'time_estimation' => $this->faker->time(),
            'due_date' => $this->faker->dateTimeThisYear
        ];
    }
}
