<?php

namespace App\Http\Controllers;

use App\Services\SupabaseService;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    protected $supabaseService;

    public function __construct(SupabaseService $supabaseService)
    {
        $this->supabaseService = $supabaseService;
    }

    public function index()
    {
        $categories = $this->supabaseService->getCategories();
        return response()->json($categories);
    }

    public function store(Request $request)
    {
        $request->validate([
            'categoryname' => 'required|string|max:255',
        ]);

        $data = [
            'categoryname' => $request->input('categoryname')
        ];

        return $this->supabaseService->insertCategory($data);
    }
}
