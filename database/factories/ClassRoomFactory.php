<?php

namespace Database\Factories;

use App\Models\Teacher;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ClassRoom>
 */
class ClassRoomFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $classes = ['X', 'XI', 'XII'];
        $majors = ['IPA', 'IPS', 'Bahasa'];
        $numbers = ['1', '2', '3', '4'];
        
        $class = $this->faker->randomElement($classes);
        $major = $this->faker->randomElement($majors);
        $number = $this->faker->randomElement($numbers);
        
        $name = $class . ' ' . $major . ' ' . $number;
        $code = strtoupper(substr($class, 0, 1) . substr($major, 0, 1) . $number);
        
        return [
            'name' => $name,
            'code' => $code,
            'description' => $this->faker->sentence(),
            'homeroom_teacher_id' => function () {
                // Pastikan ada data guru terlebih dahulu
                if (Teacher::count() > 0) {
                    return Teacher::inRandomOrder()->first()->id;
                }
                return null;
            },
            'is_active' => true,
        ];
    }
}
