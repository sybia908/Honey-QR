<?php

namespace App\Http\Controllers;

use App\Models\Teacher;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class TeacherController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth']);
        $this->middleware(['role:admin'])->except(['index', 'show']);
    }

    public function index()
    {
        $teachers = Teacher::with('user')->paginate(10);
        return view('teachers.index', compact('teachers'));
    }

    public function create()
    {
        return view('teachers.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'username' => 'required|string|max:255|unique:users',
            'password' => 'required|string|min:8',
            'nip' => 'required|string|max:255|unique:teachers',
            'phone' => 'nullable|string|max:255',
            'address' => 'nullable|string',
            'birth_date' => 'nullable|date',
            'gender' => 'required|in:L,P',
        ]);

        DB::beginTransaction();

        try {
            // Proses upload avatar jika ada
            $avatarPath = null;
            if ($request->hasFile('avatar')) {
                $avatar = $request->file('avatar');
                $filename = time() . '_' . uniqid() . '.' . $avatar->getClientOriginalExtension();
                $avatar->move(public_path('storage/avatars'), $filename);
                $avatarPath = 'storage/avatars/' . $filename;
            }
            
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'username' => $request->username,
                'password' => Hash::make($request->password),
                'avatar' => $avatarPath,
                'is_active' => true,
            ]);

            $user->assignRole('guru');

            Teacher::create([
                'nip' => $request->nip,
                'user_id' => $user->id,
                'phone' => $request->phone,
                'address' => $request->address,
                'birth_date' => $request->birth_date,
                'gender' => $request->gender,
                'is_active' => true,
            ]);

            DB::commit();

            return redirect()->route('teachers.index')
                ->with('success', 'Guru berhasil dibuat.');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Terjadi kesalahan saat membuat guru.');
        }
    }

    public function show(Teacher $teacher)
    {
        $teacher->load(['user', 'homeRoomClasses']);
        return view('teachers.show', compact('teacher'));
    }

    public function edit(Teacher $teacher)
    {
        return view('teachers.edit', compact('teacher'));
    }

    public function update(Request $request, Teacher $teacher)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $teacher->user_id,
            'username' => 'required|string|max:255|unique:users,username,' . $teacher->user_id,
            'password' => 'nullable|string|min:8',
            'nip' => 'required|string|max:255|unique:teachers,nip,' . $teacher->id,
            'phone' => 'nullable|string|max:255',
            'address' => 'nullable|string',
            'birth_date' => 'nullable|date',
            'gender' => 'required|in:L,P',
            'is_active' => 'boolean',
        ]);

        DB::beginTransaction();

        try {
            // Proses upload avatar jika ada
            if ($request->hasFile('avatar')) {
                $avatar = $request->file('avatar');
                $filename = time() . '_' . uniqid() . '.' . $avatar->getClientOriginalExtension();
                $avatar->move(public_path('storage/avatars'), $filename);
                $avatarPath = 'storage/avatars/' . $filename;
                
                // Update data pengguna dengan avatar baru
                $teacher->user->update([
                    'name' => $request->name,
                    'email' => $request->email,
                    'username' => $request->username,
                    'avatar' => $avatarPath,
                    'is_active' => $request->is_active ?? false,
                ]);
            } else {
                // Update data pengguna tanpa mengubah avatar
                $teacher->user->update([
                    'name' => $request->name,
                    'email' => $request->email,
                    'username' => $request->username,
                    'is_active' => $request->is_active ?? false,
                ]);
            }

            if ($request->filled('password')) {
                $teacher->user->update(['password' => Hash::make($request->password)]);
            }

            $teacher->update([
                'nip' => $request->nip,
                'phone' => $request->phone,
                'address' => $request->address,
                'birth_date' => $request->birth_date,
                'gender' => $request->gender,
                'is_active' => $request->is_active ?? false,
            ]);

            DB::commit();

            return redirect()->route('teachers.index')
                ->with('success', 'Guru berhasil diupdate.');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Terjadi kesalahan saat mengupdate guru.');
        }
    }

    public function destroy(Teacher $teacher)
    {
        if ($teacher->homeRoomClasses()->exists()) {
            return back()->with('error', 'Tidak dapat menghapus guru yang masih menjadi wali kelas.');
        }

        DB::beginTransaction();

        try {
            $teacher->delete();
            $teacher->user->delete();

            DB::commit();

            return redirect()->route('teachers.index')
                ->with('success', 'Guru berhasil dihapus.');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Terjadi kesalahan saat menghapus guru.');
        }
    }
}
