<?php

namespace App\Http\Controllers;

use App\Facades\Supabase;
use Illuminate\Http\Request;

class SupabaseExampleController extends Controller
{
    /**
     * Menampilkan contoh penggunaan Supabase
     */
    public function index()
    {
        // Contoh mengambil data dari tabel users di Supabase
        $users = Supabase::query('users', [
            'select' => 'id,name,email,created_at',
            'order' => 'created_at.desc',
            'limit' => 10
        ]);

        return view('supabase.example', [
            'users' => $users ?? []
        ]);
    }

    /**
     * Menyimpan data ke Supabase
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'password' => 'required|string|min:6',
        ]);

        // Contoh menyimpan data ke tabel users di Supabase
        $user = Supabase::insert('users', [
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => bcrypt($validated['password']),
            'created_at' => now()->toIso8601String(),
            'updated_at' => now()->toIso8601String(),
        ]);

        if (!$user) {
            return back()->with('error', 'Gagal menyimpan data ke Supabase');
        }

        return redirect()->route('supabase.example')->with('success', 'Data berhasil disimpan ke Supabase');
    }

    /**
     * Memperbarui data di Supabase
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
        ]);

        // Contoh memperbarui data di tabel users di Supabase
        $updated = Supabase::update('users', [
            'name' => $validated['name'],
            'email' => $validated['email'],
            'updated_at' => now()->toIso8601String(),
        ], [
            'id' => 'eq.' . $id
        ]);

        if (!$updated) {
            return back()->with('error', 'Gagal memperbarui data di Supabase');
        }

        return redirect()->route('supabase.example')->with('success', 'Data berhasil diperbarui di Supabase');
    }

    /**
     * Menghapus data dari Supabase
     */
    public function destroy($id)
    {
        // Contoh menghapus data dari tabel users di Supabase
        $deleted = Supabase::delete('users', [
            'id' => 'eq.' . $id
        ]);

        if (!$deleted) {
            return back()->with('error', 'Gagal menghapus data dari Supabase');
        }

        return redirect()->route('supabase.example')->with('success', 'Data berhasil dihapus dari Supabase');
    }

    /**
     * Contoh upload file ke Supabase Storage
     */
    public function upload(Request $request)
    {
        $request->validate([
            'file' => 'required|file|max:10240', // Max 10MB
        ]);

        $file = $request->file('file');
        $fileName = time() . '_' . $file->getClientOriginalName();
        $filePath = $file->storeAs('uploads', $fileName, 'public');

        // Upload file ke Supabase Storage
        $url = Supabase::uploadFile('honeyqr-storage', $fileName, storage_path('app/public/' . $filePath));

        if (!$url) {
            return back()->with('error', 'Gagal mengupload file ke Supabase Storage');
        }

        return back()->with('success', 'File berhasil diupload ke Supabase Storage')->with('file_url', $url);
    }
}
