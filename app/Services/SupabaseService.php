<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SupabaseService
{
    protected $url;
    protected $key;
    protected $headers;

    public function __construct()
    {
        $this->url = config('supabase.url');
        $this->key = config('supabase.key');
        $this->headers = [
            'apikey' => $this->key,
            'Authorization' => 'Bearer ' . $this->key,
            'Content-Type' => 'application/json',
            'Prefer' => 'return=representation'
        ];
    }

    /**
     * Melakukan query ke tabel Supabase
     *
     * @param string $table Nama tabel
     * @param array $params Parameter query
     * @return array|null
     */
    public function query(string $table, array $params = [])
    {
        $endpoint = $this->url . '/rest/v1/' . $table;
        
        try {
            $response = Http::withHeaders($this->headers)
                ->get($endpoint, $params);
            
            if ($response->successful()) {
                return $response->json();
            }
            
            Log::error('Supabase query error', [
                'table' => $table,
                'params' => $params,
                'status' => $response->status(),
                'response' => $response->body()
            ]);
            
            return null;
        } catch (\Exception $e) {
            Log::error('Supabase query exception', [
                'table' => $table,
                'params' => $params,
                'error' => $e->getMessage()
            ]);
            
            return null;
        }
    }

    /**
     * Menyimpan data ke tabel Supabase
     *
     * @param string $table Nama tabel
     * @param array $data Data yang akan disimpan
     * @return array|null
     */
    public function insert(string $table, array $data)
    {
        $endpoint = $this->url . '/rest/v1/' . $table;
        
        try {
            $response = Http::withHeaders($this->headers)
                ->post($endpoint, $data);
            
            if ($response->successful()) {
                return $response->json();
            }
            
            Log::error('Supabase insert error', [
                'table' => $table,
                'data' => $data,
                'status' => $response->status(),
                'response' => $response->body()
            ]);
            
            return null;
        } catch (\Exception $e) {
            Log::error('Supabase insert exception', [
                'table' => $table,
                'data' => $data,
                'error' => $e->getMessage()
            ]);
            
            return null;
        }
    }

    /**
     * Memperbarui data di tabel Supabase
     *
     * @param string $table Nama tabel
     * @param array $data Data yang akan diperbarui
     * @param array $match Kondisi untuk mencocokkan data
     * @return array|null
     */
    public function update(string $table, array $data, array $match)
    {
        $endpoint = $this->url . '/rest/v1/' . $table;
        
        try {
            $response = Http::withHeaders(array_merge($this->headers, [
                    'Prefer' => 'return=representation'
                ]))
                ->patch($endpoint, $data)
                ->query($match);
            
            if ($response->successful()) {
                return $response->json();
            }
            
            Log::error('Supabase update error', [
                'table' => $table,
                'data' => $data,
                'match' => $match,
                'status' => $response->status(),
                'response' => $response->body()
            ]);
            
            return null;
        } catch (\Exception $e) {
            Log::error('Supabase update exception', [
                'table' => $table,
                'data' => $data,
                'match' => $match,
                'error' => $e->getMessage()
            ]);
            
            return null;
        }
    }

    /**
     * Menghapus data dari tabel Supabase
     *
     * @param string $table Nama tabel
     * @param array $match Kondisi untuk mencocokkan data
     * @return bool
     */
    public function delete(string $table, array $match)
    {
        $endpoint = $this->url . '/rest/v1/' . $table;
        
        try {
            $response = Http::withHeaders($this->headers)
                ->delete($endpoint, $match);
            
            if ($response->successful()) {
                return true;
            }
            
            Log::error('Supabase delete error', [
                'table' => $table,
                'match' => $match,
                'status' => $response->status(),
                'response' => $response->body()
            ]);
            
            return false;
        } catch (\Exception $e) {
            Log::error('Supabase delete exception', [
                'table' => $table,
                'match' => $match,
                'error' => $e->getMessage()
            ]);
            
            return false;
        }
    }

    /**
     * Upload file ke Supabase Storage
     *
     * @param string $bucket Nama bucket
     * @param string $path Path file di bucket
     * @param string $filePath Path file lokal
     * @return string|null URL file yang diupload
     */
    public function uploadFile(string $bucket, string $path, string $filePath)
    {
        $endpoint = $this->url . '/storage/v1/object/' . $bucket . '/' . $path;
        
        try {
            $response = Http::withHeaders($this->headers)
                ->attach('file', file_get_contents($filePath), basename($filePath))
                ->post($endpoint);
            
            if ($response->successful()) {
                return $this->url . '/storage/v1/object/public/' . $bucket . '/' . $path;
            }
            
            Log::error('Supabase file upload error', [
                'bucket' => $bucket,
                'path' => $path,
                'filePath' => $filePath,
                'status' => $response->status(),
                'response' => $response->body()
            ]);
            
            return null;
        } catch (\Exception $e) {
            Log::error('Supabase file upload exception', [
                'bucket' => $bucket,
                'path' => $path,
                'filePath' => $filePath,
                'error' => $e->getMessage()
            ]);
            
            return null;
        }
    }
}
