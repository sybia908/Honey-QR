<?php

namespace App\Http\Controllers;

use App\Models\ClassRoom;
use App\Models\Teacher;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class ClassController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth']);
        $this->middleware(['role:admin'])->except(['index', 'show']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $page = $request->get('page', 1);
        $cacheKey = 'classes_page_' . $page;
        
        // Ambil data dari cache jika tersedia, atau jalankan query jika tidak ada di cache
        $classes = Cache::remember($cacheKey, now()->addMinutes(5), function () {
            return ClassRoom::with(['homeroomTeacher', 'students'])->withCount('students')->paginate(10);
        });
        
        return view('classes.index', compact('classes'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $teachers = Teacher::with('user')->get();
        return view('classes.create', compact('teachers'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'homeroom_teacher_id' => 'nullable|exists:teachers,id',
        ]);

        // Generate a unique code based on the name
        $code = strtoupper(substr(str_replace(' ', '', $request->name), 0, 10));
        $baseCode = $code;
        $counter = 1;

        // Make sure the code is unique
        while (ClassRoom::where('code', $code)->exists()) {
            $code = $baseCode . $counter;
            $counter++;
        }

        ClassRoom::create([
            'name' => $request->name,
            'code' => $code,
            'homeroom_teacher_id' => $request->homeroom_teacher_id,
            'is_active' => true,
        ]);

        return redirect()->route('classes.index')
            ->with('success', 'Kelas berhasil dibuat.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\ClassRoom  $class
     * @return \Illuminate\Http\Response
     */
    public function show(ClassRoom $class)
    {
        $class->load(['homeroomTeacher', 'students']);
        return view('classes.show', compact('class'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\ClassRoom  $class
     * @return \Illuminate\Http\Response
     */
    public function edit(ClassRoom $class)
    {
        $teachers = Teacher::with('user')->get();
        return view('classes.edit', compact('class', 'teachers'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
