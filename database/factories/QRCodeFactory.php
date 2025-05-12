<?php

namespace Database\Factories;

use App\Models\ClassRoom;
use App\Models\Subject;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\QRCode>
 */
class QRCodeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        // Generate random valid_until between today and 7 days from now
        $validUntil = Carbon::now()->addDays($this->faker->numberBetween(1, 7));
        
        // Generate random active hours (8 AM - 5 PM)
        $activeFromHour = $this->faker->numberBetween(8, 16);
        $activeUntilHour = $activeFromHour + 1;
        $activeFrom = sprintf('%02d:00', $activeFromHour);
        $activeUntil = sprintf('%02d:00', $activeUntilHour);
        
        return [
            'code' => 'QR-' . $this->faker->unique()->regexify('[A-Z0-9]{8}'),
            'created_by' => function () {
                return User::whereHas('roles', function ($query) {
                    $query->where('name', 'guru')->orWhere('name', 'admin');
                })->inRandomOrder()->first()->id ?? User::factory()->create()->assignRole('guru')->id;
            },
            'valid_until' => $validUntil,
            'is_active' => true,
            'class_id' => function () {
                return ClassRoom::count() > 0 ? 
                    ClassRoom::inRandomOrder()->first()->id : 
                    ClassRoom::factory()->create()->id;
            },
            'subject_id' => function () {
                return Subject::count() > 0 ? 
                    Subject::inRandomOrder()->first()->id : 
                    Subject::factory()->create()->id;
            },
            'active_from' => $activeFrom,
            'active_until' => $activeUntil
        ];
    }
}
