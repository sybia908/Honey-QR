<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static array|null query(string $table, array $params = [])
 * @method static array|null insert(string $table, array $data)
 * @method static array|null update(string $table, array $data, array $match)
 * @method static bool delete(string $table, array $match)
 * @method static string|null uploadFile(string $bucket, string $path, string $filePath)
 * 
 * @see \App\Services\SupabaseService
 */
class Supabase extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'supabase';
    }
}
