<?php

namespace App\Http\Controllers;

use App\Services\SupabaseService;

class SupabaseController extends Controller
{
    protected $supabaseService;

    // Inject the SupabaseService
    public function __construct(SupabaseService $supabaseService)
    {
        $this->supabaseService = $supabaseService;
    }

    // Example method to show data
    public function show()
    {
        $data = $this->supabaseService->getData();
        return view('someview', compact('data'));
    }
}
