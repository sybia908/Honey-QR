<?php

namespace Database\Seeders;

use App\Models\Classes;
use Illuminate\Database\Seeder;

class ClassSeeder extends Seeder
{
    public function run()
    {
        $classes = [
            ['name' => 'X IPA 1'],
            ['name' => 'X IPA 2'],
            ['name' => 'X IPS 1'],
            ['name' => 'X IPS 2'],
            ['name' => 'XI IPA 1'],
            ['name' => 'XI IPA 2'],
            ['name' => 'XI IPS 1'],
            ['name' => 'XI IPS 2'],
            ['name' => 'XII IPA 1'],
            ['name' => 'XII IPA 2'],
            ['name' => 'XII IPS 1'],
            ['name' => 'XII IPS 2'],
        ];

        foreach ($classes as $class) {
            Classes::create($class);
        }
    }
}
