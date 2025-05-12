<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Supabase Configuration
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials and configuration for Supabase.
    |
    */

    'url' => env('SUPABASE_URL', 'https://dhpogmrulnvmkhdzjpqh.supabase.co'),
    'key' => env('SUPABASE_KEY', 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJzdXBhYmFzZSIsInJlZiI6ImRocG9nbXJ1bG52bWtoZHpqcHFoIiwicm9sZSI6ImFub24iLCJpYXQiOjE3NDcwNDg3MDcsImV4cCI6MjA2MjYyNDcwN30.n3Kywx5Os9kAKg2a4XwcNaJ14zC1OG7sSfdqzfuWTac'),
    'options' => [
        'schema' => 'public',
        'headers' => []
    ],
];
