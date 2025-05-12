<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Subject>
 */
class SubjectFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $subjects = [
            'Matematika' => 'MTK',
            'Bahasa Indonesia' => 'BIN',
            'Bahasa Inggris' => 'BIG',
            'Fisika' => 'FIS',
            'Kimia' => 'KIM',
            'Biologi' => 'BIO',
            'Sejarah' => 'SEJ',
            'Geografi' => 'GEO',
            'Ekonomi' => 'EKO',
            'Sosiologi' => 'SOS',
            'Pendidikan Kewarganegaraan' => 'PKN',
            'Pendidikan Agama' => 'PAI',
            'Seni Budaya' => 'SBD',
            'Pendidikan Jasmani' => 'PJK',
            'Informatika' => 'INF'
        ];
        
        $subjectName = $this->faker->unique()->randomElement(array_keys($subjects));
        $subjectCode = $subjects[$subjectName];
        
        return [
            'name' => $subjectName,
            'code' => $subjectCode,
            'description' => $this->faker->sentence(),
            'credits' => $this->faker->numberBetween(1, 4)
        ];
    }
}
