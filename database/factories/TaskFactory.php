<?php

namespace Database\Factories;

use App\Models\Task;
use App\Models\User;
use App\Models\Category;
use App\Models\Status;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Task>
 */
class TaskFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = Task::class;

    public function definition(): array
    {
        return [
            'title' => $this->faker->sentence,
            'description' => $this->faker->paragraph,
            'status_id' => Status::inRandomOrder()->first()->id,
            'user_id' => User::factory(), 
            'category_id' => Category::factory(),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
