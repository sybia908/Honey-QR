<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Teacher>
 */
class TeacherFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $positions = ['Guru Matematika', 'Guru Bahasa Indonesia', 'Guru Bahasa Inggris', 'Guru Fisika', 'Guru Kimia', 'Guru Biologi', 'Guru Sejarah', 'Guru Geografi', 'Guru Ekonomi', 'Guru Sosiologi'];
        
        return [
            'nip' => $this->faker->unique()->numerify('##########'),
            'user_id' => function () {
                return User::factory()->create()->assignRole('guru')->id;
            },
            'phone' => $this->faker->phoneNumber(),
            'address' => $this->faker->address(),
            'birth_date' => $this->faker->dateTimeBetween('-60 years', '-25 years')->format('Y-m-d'),
            'gender' => $this->faker->randomElement(['L', 'P']),
            'position' => $this->faker->randomElement($positions),
            'is_active' => true,
        ];
    }
}
