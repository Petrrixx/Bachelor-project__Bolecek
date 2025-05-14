<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\Photo;
use Illuminate\Support\Facades\Auth;

class PhotoGalleryController extends Controller
{
    protected $cloud;
    protected $key;
    protected $secret;
    protected $preset;

    public function __construct()
    {
        $this->cloud  = env('CLOUDINARY_CLOUD_NAME');
        $this->key    = env('CLOUDINARY_API_KEY');
        $this->secret = env('CLOUDINARY_API_SECRET');
        $this->preset = env('CLOUDINARY_UPLOAD_PRESET');

        if (! $this->cloud || ! $this->key || ! $this->secret) {
            throw new \Exception('Prosím nastavte CLOUDINARY_CLOUD_NAME, CLOUDINARY_API_KEY a CLOUDINARY_API_SECRET v .env');
        }
    }

    public function index()
    {
        $response = Http::withBasicAuth($this->key, $this->secret)
            ->withOptions(['verify' => false])
            ->get("https://api.cloudinary.com/v1_1/{$this->cloud}/resources/image/upload", [
                'max_results' => 200,
            ]);

        if ($response->ok()) {
            $images = collect($response->json()['resources']);
        } else {
            \Log::error('Cloudinary list error', ['resp' => $response->body()]);
            $images = collect();
        }

        return view('photo-gallery', compact('images'));
    }

    public function store(Request $request)
    {
        if (!Auth::check() || !Auth::user()->isAdmin) {
            return response()->json(['message' => 'Nemáte oprávnenie.'], 401);
        }

        $request->validate([
            'images.*' => 'required|image|mimes:jpeg,png,jpg|max:4096',
        ]);

        foreach ($request->file('images') as $file) {
            $response = Http::attach(
                'file', file_get_contents($file->getRealPath()), $file->getClientOriginalName()
            )
            ->withOptions(['verify' => false])
            ->post("https://api.cloudinary.com/v1_1/{$this->cloud}/image/upload", [
                'upload_preset' => $this->preset,
            ]);

            if (! $response->ok()) {
                return back()->withErrors('Chyba pri nahrávaní: '.$response->body());
            }

            $json = $response->json();
            Photo::create([
                'public_id' => $json['public_id'],
                'url'       => $json['secure_url'],
            ]);
        }

        return redirect()->route('photogallery.index')
                         ->with('success', 'Obrázky úspešne nahraté na Cloudinary!');
    }

    public function destroy($publicId)
    {
        if (!Auth::check() || !Auth::user()->isAdmin) {
            abort(403);
        }

        $cloud     = env('CLOUDINARY_CLOUD_NAME');
        $key       = env('CLOUDINARY_API_KEY');
        $secret    = env('CLOUDINARY_API_SECRET');
        $timestamp = time();

        // 1) Vypočítaj signature
        $toSign    = "public_id={$publicId}&timestamp={$timestamp}{$secret}";
        $signature = sha1($toSign);

        // 2) Pošli POST ako form-data
        $response = Http::asForm()
            ->post("https://api.cloudinary.com/v1_1/{$cloud}/image/destroy", [
                'public_id' => $publicId,
                'api_key'   => $key,
                'timestamp' => $timestamp,
                'signature' => $signature,
            ]);

        if (! $response->ok()) {
            \Log::error('Cloudinary destroy error', ['body' => $response->body()]);
            return response()->json([
                'error' => 'Cloudinary delete failed: '.$response->body()
            ], 500);
        }

        // 3) Odstrániť aj z DB
        Photo::where('public_id', $publicId)->delete();

        return response()->json(['success' => true]);
    }
}
