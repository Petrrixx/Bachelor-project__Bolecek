<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PhotoGalleryController extends Controller
{
    public function upload(Request $request)
    {
        $request->validate([
            'images.*' => 'required|file|mimes:jpeg,jpg,png|max:2048'
        ]);

        $uploadedFiles = [];
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $file) {
                // Vygeneruj jedinečný názov súboru, napr. kombináciou time(), uniqid() a pôvodného názvu
                $filename = time() . '_' . uniqid() . '_' . $file->getClientOriginalName();
                // Alebo môžeš použiť: $filename = time() . '_' . Str::random(10) . '_' . $file->getClientOriginalName();
                $file->move(public_path('images/fotogaleria'), $filename);
                $uploadedFiles[] = $filename;
            }
        }

        return response()->json([
            'message' => 'Obrázky boli úspešne nahraté.',
            'files'   => $uploadedFiles
        ]);
    }
}

