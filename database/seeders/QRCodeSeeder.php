<?php

namespace Database\Seeders;

use App\Models\QRCode;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class QRCodeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        $admin = User::role('admin')->first();

        QRCode::create([
            'code' => 'QR-' . uniqid(),
            'created_by' => $admin->id,
            'valid_until' => Carbon::now()->addDays(7),
        ]);
    }
}
