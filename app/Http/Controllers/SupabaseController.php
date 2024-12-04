<?php

namespace App\Http\Controllers;

use App\Services\SupabaseService;

class SupabaseController extends Controller
{
    protected $supabaseService;


    public function __construct(SupabaseService $supabaseService)
    {
        $this->supabaseService = $supabaseService;
    }

    public function show()
    {
        $data = $this->supabaseService->getData();
        return view('someview', compact('data'));
    }
}
