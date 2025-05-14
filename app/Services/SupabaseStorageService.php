<?php
namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class SupabaseStorageService
{
    protected $storageUrl;
    protected $apiKey;
    protected $bucket;

    public function __construct()
    {
        $this->storageUrl = rtrim(env('SUPABASE_STORAGE_URL'), '/') . '/object';
        $this->apiKey = env('SUPABASE_SERVICE_ROLE_KEY');
        $this->bucket = 'images'; 
    }

    public function upload($file)
    {
        $fileName = Str::random(20) . '_' . $file->getClientOriginalName();
        $filePath = $file->getRealPath();

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $this->apiKey,
            'Content-Type' => $file->getMimeType(),
        ])->put(
            "{$this->storageUrl}/{$this->bucket}/{$fileName}",
            fopen($filePath, 'r')
        );

        if ($response->successful()) {
            return $fileName;
        }

        throw new \Exception("Upload failed: " . $response->body());
    }
}