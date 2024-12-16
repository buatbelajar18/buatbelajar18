<?php

namespace App\Http\Controllers;

use App\Models\Service;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    // Menampilkan semua service
    public function index()
    {
        $services = Service::with('artist', 'category')->get();
        return view('services.index', compact('services'));
    }

    // Menampilkan detail service tertentu
    public function show($id)
    {
        $service = Service::with('artist', 'category')->findOrFail($id);
        return view('services.show', compact('service'));
    }

    // Form untuk menambah service
    public function create()
    {
        return view('services.create');
    }

    // Menyimpan service baru
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'artist_id' => 'required|exists:artists,id',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric',
            'service_type' => 'required|string',
        ]);

        Service::create($validatedData);

        return redirect()->route('services.index');
    }
}
