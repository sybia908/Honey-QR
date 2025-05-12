<?php

namespace App\Http\Controllers;

use App\Models\ClassRoom;
use App\Models\Student;
use App\Models\User;
use App\Exports\StudentsExport;
use App\Imports\StudentsImport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Facades\Excel;

class StudentController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth']);
        $this->middleware(['role:admin'])->except(['index', 'show']);
    }

    public function index(Request $request)
    {
        $query = Student::with(['user', 'class']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->whereHas('user', function($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%");
                })->orWhere('nis', 'like', "%{$search}%");
            });
        }

        if ($request->filled('class_id')) {
            $query->where('class_id', $request->class_id);
        }

        $students = $query->latest()->paginate(10);
        $classes = ClassRoom::where('is_active', true)->get();

        return view('students.index', compact('students', 'classes'));
    }

    public function create()
    {
        $classes = ClassRoom::where('is_active', true)->get();
        return view('students.create', compact('classes'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'username' => 'required|string|max:255|unique:users',
            'password' => 'required|string|min:8',
            'nis' => 'required|string|max:255|unique:students',
            'class_id' => 'required|exists:classes,id',
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

            $user->assignRole('siswa');

            Student::create([
                'nis' => $request->nis,
                'user_id' => $user->id,
                'class_id' => $request->class_id,
                'phone' => $request->phone,
                'address' => $request->address,
                'birth_date' => $request->birth_date,
                'gender' => $request->gender,
                'is_active' => true,
            ]);

            DB::commit();

            return redirect()->route('students.index')
                ->with('success', 'Siswa berhasil dibuat.');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Terjadi kesalahan saat membuat siswa.');
        }
    }

    public function show(Student $student)
    {
        $student->load(['user', 'class']);
        return view('students.show', compact('student'));
    }

    public function edit(Student $student)
    {
        $classes = ClassRoom::where('is_active', true)->get();
        return view('students.edit', compact('student', 'classes'));
    }

    public function update(Request $request, Student $student)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $student->user_id,
            'username' => 'required|string|max:255|unique:users,username,' . $student->user_id,
            'password' => 'nullable|string|min:8',
            'nis' => 'required|string|max:255|unique:students,nis,' . $student->id,
            'class_id' => 'required|exists:classes,id',
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
                $student->user->update([
                    'name' => $request->name,
                    'email' => $request->email,
                    'username' => $request->username,
                    'avatar' => $avatarPath,
                    'is_active' => $request->is_active ?? false,
                ]);
            } else {
                // Update data pengguna tanpa mengubah avatar
                $student->user->update([
                    'name' => $request->name,
                    'email' => $request->email,
                    'username' => $request->username,
                    'is_active' => $request->is_active ?? false,
                ]);
            }

            if ($request->filled('password')) {
                $student->user->update(['password' => Hash::make($request->password)]);
            }

            $student->update([
                'nis' => $request->nis,
                'class_id' => $request->class_id,
                'phone' => $request->phone,
                'address' => $request->address,
                'birth_date' => $request->birth_date,
                'gender' => $request->gender,
                'is_active' => $request->is_active ?? false,
            ]);

            DB::commit();

            return redirect()->route('students.index')
                ->with('success', 'Siswa berhasil diupdate.');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Terjadi kesalahan saat mengupdate siswa.');
        }
    }

    public function destroy(Student $student)
    {
        DB::beginTransaction();

        try {
            $student->delete();
            $student->user->delete();

            DB::commit();

            return redirect()->route('students.index')
                ->with('success', 'Siswa berhasil dihapus.');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Terjadi kesalahan saat menghapus siswa.');
        }
    }

    public function template()
    {
        return Excel::download(new StudentsExport(true), 'template-siswa.xlsx');
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls',
        ]);

        try {
            Excel::import(new StudentsImport, $request->file('file'));

            return redirect()->route('students.index')
                ->with('success', 'Data siswa berhasil diimpor.');
        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan saat mengimpor data: ' . $e->getMessage());
        }
    }

    public function export()
    {
        return Excel::download(new StudentsExport, 'data-siswa.xlsx');
    }
}
