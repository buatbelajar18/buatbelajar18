<?php

namespace App\Http\Controllers;

use App\Models\Commission;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // Tambahkan ini

class CommissionController extends Controller
{
    // Menampilkan semua commission
    public function index()
    {
        // Memuat data commission beserta relasi user
        $commissions = Commission::with('user')->get();
        // Memuat data commission berdasarkan user yang sedang login
        $commissions = Commission::where('user_id', Auth::id())->get();
        return view('commissions.index', compact('commissions'));
    }

    // Menampilkan form untuk menambah commission
    public function create()
    {
        return view('commissions.create');
    }

    // Menyimpan commission baru ke database
    public function store(Request $request)
    {
        // Validasi input
        $validatedData = $request->validate([
            'status' => 'required|string|in:pending,accepted,completed',
            'total_price' => 'required|numeric',
            'description' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        // Mengelola upload gambar
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('commissions', 'public');
            $validatedData['image'] = $imagePath;
        }

        // Tambahkan user_id dari user yang login
        $validatedData['user_id'] = Auth::user()->id;

        // Simpan commission ke database
        Commission::create($validatedData);

        return redirect()->route('commissions.index')->with('success', 'Commission berhasil ditambahkan!');
    }

    // Menampilkan detail commission
    public function show($id)
    {
        $commission = Commission::with('user')->findOrFail($id);
        return view('commissions.show', compact('commission'));
    }

    // Menampilkan form untuk mengedit commission
    // Menampilkan form untuk mengedit commission
    public function edit($id)
    {
        $commission = Commission::findOrFail($id);

        // Pastikan hanya user yang memiliki commission yang bisa mengedit
        if ($commission->user_id !== Auth::id()) {
            return redirect()->route('commissions.index')->with('error', 'Unauthorized action.');
        }

        return view('commissions.edit', compact('commission'));
    }


    // Mengupdate commission di database
    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'status' => 'required|string|in:pending,accepted,completed',
            'total_price' => 'required|numeric',
            'description' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $commission = Commission::findOrFail($id);

        // Mengelola upload gambar baru jika ada
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('commissions', 'public');
            $validatedData['image'] = $imagePath;
        }

        // Update data commission di database
        $commission->update($validatedData);

        return redirect()->route('commissions.index')->with('success', 'Commission berhasil diupdate!');
    }

    // Menghapus commission dari database
    public function destroy($id)
    {
        $commission = Commission::findOrFail($id);

        // Pastikan hanya user yang memiliki commission yang bisa menghapus
        if ($commission->user_id !== Auth::id()) {
            return redirect()->route('commissions.index')->with('error', 'Unauthorized action.');
        }

        // Hapus commission
        $commission->delete();

        return redirect()->route('commissions.index')->with('success', 'Commission berhasil dihapus!');
    }

    public function toggleLove($id)
    {
        $commission = Commission::findOrFail($id);
        $user = auth()->user();
    
        // Cek apakah user sudah love commission ini
        if ($commission->loves()->where('user_id', $user->id)->exists()) {
            // Jika sudah love, kurangi loved_count
            $commission->loved_count -= 1;
            $commission->save();
    
            // Hapus love dari user ini di tabel pivot
            $commission->loves()->detach($user->id);
    
            $loved = false;
        } else {
            // Jika belum love, tambahkan loved_count
            $commission->loved_count += 1;
            $commission->save();
    
            // Tambahkan love untuk user ini di tabel pivot
            $commission->loves()->attach($user->id);
    
            $loved = true;
        }
    
        // Return response dalam bentuk JSON
        return response()->json([
            'loved' => $loved,
            'loved_count' => $commission->loved_count
        ]);
    }

    public function addReview(Request $request, $id)
    {
        $request->validate([
            'review' => 'required|string|max:255',
        ]);
    
        // Cari commission berdasarkan ID
        $commission = Commission::findOrFail($id);
    
        // Tambahkan review baru ke tabel reviews
        Review::create([
            'commission_id' => $commission->id,
            'user_id' => auth()->user()->id, // ID user yang memberikan review
            'review' => $request->review, // Isi review
        ]);
    
        return back()->with('success', 'Review added successfully!');
    }
    
    
}
