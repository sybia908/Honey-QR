<?php

namespace Database\Factories;

use App\Models\QRCode;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Attendance>
 */
class AttendanceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        // Generate random date for the past 30 days
        $date = Carbon::now()->subDays($this->faker->numberBetween(0, 30))->format('Y-m-d');
        
        // Generate random time_in between 7:00 and 8:00
        $hour = $this->faker->numberBetween(7, 8);
        $minute = $this->faker->numberBetween(0, 59);
        $timeIn = Carbon::createFromFormat('Y-m-d H:i', $date . ' ' . sprintf('%02d:%02d', $hour, $minute));
        
        // Status based on time_in (after 7:15 is late)
        $status = $hour > 7 || ($hour == 7 && $minute > 15) ? 'late' : 'on_time';
        
        // Maybe include time_out (80% probability)
        $hasTimeOut = $this->faker->boolean(80);
        $timeOut = null;
        
        if ($hasTimeOut) {
            // Time out between 1 to 9 hours after time_in
            $timeOut = (clone $timeIn)->addHours($this->faker->numberBetween(1, 9));
        }
        
        // Generate random coordinates (around Jakarta area)
        $latBase = -6.2;
        $lngBase = 106.8;
        $latOffset = $this->faker->randomFloat(6, -0.1, 0.1);
        $lngOffset = $this->faker->randomFloat(6, -0.1, 0.1);
        
        return [
            'user_id' => function () {
                return User::whereHas('roles', function ($query) {
                    $query->where('name', 'siswa');
                })->inRandomOrder()->first()->id ?? User::factory()->create()->assignRole('siswa')->id;
            },
            'qrcode_id' => function () {
                return QRCode::count() > 0 ? 
                    QRCode::inRandomOrder()->first()->id : 
                    QRCode::factory()->create()->id;
            },
            'date' => $date,
            'time_in' => $timeIn->format('H:i:s'),
            'time_out' => $hasTimeOut ? $timeOut->format('H:i:s') : null,
            'status' => $status,
            'latitude_in' => $latBase + $latOffset,
            'longitude_in' => $lngBase + $lngOffset,
            'latitude_out' => $hasTimeOut ? $latBase + $this->faker->randomFloat(6, -0.002, 0.002) + $latOffset : null,
            'longitude_out' => $hasTimeOut ? $lngBase + $this->faker->randomFloat(6, -0.002, 0.002) + $lngOffset : null,
        ];
    }
}
