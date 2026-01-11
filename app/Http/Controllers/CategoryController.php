<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CategoryController extends Controller
{
    // Menampilkan form tambah kategori (modal saja)
    public function create()
    {
        return view('categories.create-modal');
    }

    // Menyimpan kategori baru
    public function store(Request $request)
    {
        $validated = $request->validate([
            'category_name' => 'required|string|max:50',
            'type' => 'required|in:income,expense'
        ]);

        Category::create([
            'user_id' => Auth::id(),
            'category_name' => $validated['category_name'],
            'type' => $validated['type']
        ]);

        return redirect()->back()->with('success', 'Kategori berhasil ditambahkan!');
    }
}