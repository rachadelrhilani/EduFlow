<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\Course;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Course>
 */
class CourseFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => fake()->sentence(),
        'description' => fake()->paragraph(),
        'price' => fake()->randomFloat(2, 10, 100),
        // Cette syntaxe crée automatiquement un User avec le rôle enseignant
            'teacher_id' => User::factory()->create(['role' => 'enseignant'])->id,
            
            // Cette syntaxe crée automatiquement une Category
            'category_id' => Category::factory(),
        ];
    }
}
