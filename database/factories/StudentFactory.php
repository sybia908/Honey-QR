<?php

namespace Database\Factories;

use App\Models\ClassRoom;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Student>
 */
class StudentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'nis' => $this->faker->unique()->numerify('#########'),
            'user_id' => function () {
                return User::factory()->create()->assignRole('siswa')->id;
            },
            'class_id' => function () {
                // Pastikan ada data kelas terlebih dahulu
                if (ClassRoom::count() > 0) {
                    return ClassRoom::inRandomOrder()->first()->id;
                }
                // Jika belum ada kelas, buat kelas baru
                return ClassRoom::factory()->create()->id;
            },
            'phone' => $this->faker->phoneNumber(),
            'address' => $this->faker->address(),
            'birth_date' => $this->faker->dateTimeBetween('-20 years', '-14 years')->format('Y-m-d'),
            'gender' => $this->faker->randomElement(['L', 'P']),
            'is_active' => true,
        ];
    }
}
