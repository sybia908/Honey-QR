<?php

namespace App\Http\Controllers;

use App\Models\Classes;
use App\Models\Subject;
use App\Models\Teacher;
use Illuminate\Http\Request;

class SubjectController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:admin,guru']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $subjects = Subject::with(['classes', 'teachers'])
            ->latest()
            ->paginate(10);

        return view('subjects.index', compact('subjects'));
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $teachers = Teacher::with('user')->get();
        $classes = Classes::all();
        return view('subjects.create', compact('teachers', 'classes'));
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
            'code' => 'required|string|max:50|unique:subjects',
            'description' => 'nullable|string',
            'credits' => 'required|integer|min:1'
        ]);

        $subject = Subject::create($request->all());

        if ($request->has('teachers')) {
            $subject->teachers()->attach($request->teachers);
        }

        if ($request->has('classes')) {
            $classData = [];
            
            // Pastikan array memiliki panjang yang sama
            $classes = $request->classes ?? [];
            $days = $request->days ?? [];
            $startTimes = $request->start_times ?? [];
            $endTimes = $request->end_times ?? [];
            
            foreach ($classes as $index => $classId) {
                if (isset($days[$index]) && isset($startTimes[$index]) && isset($endTimes[$index])) {
                    $classData[$classId] = [
                        'day' => $days[$index],
                        'start_time' => $startTimes[$index],
                        'end_time' => $endTimes[$index],
                    ];
                }
            }
            
            $subject->classes()->attach($classData);
        }

        return redirect()->route('subjects.index')
            ->with('success', 'Mata pelajaran berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Subject $subject)
    {
        $subject->load(['classes', 'teachers.user']);
        return view('subjects.show', compact('subject'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Subject $subject)
    {
        $teachers = Teacher::with('user')->get();
        $classes = Classes::all();
        $subject->load(['classes', 'teachers']);
        return view('subjects.edit', compact('subject', 'teachers', 'classes'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Subject $subject)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:subjects,code,' . $subject->id,
            'description' => 'nullable|string',
            'credits' => 'required|integer|min:1'
        ]);

        $subject->update($request->all());

        $subject->teachers()->sync($request->teachers ?? []);
        
        // Persiapkan data kelas dengan jadwal
        if ($request->has('classes')) {
            $classData = [];
            
            // Pastikan array memiliki panjang yang sama
            $classes = $request->classes ?? [];
            $days = $request->days ?? [];
            $startTimes = $request->start_times ?? [];
            $endTimes = $request->end_times ?? [];
            
            $debugInfo = "Classes: " . json_encode($classes) . "<br>";
            $debugInfo .= "Days: " . json_encode($days) . "<br>";
            $debugInfo .= "Start Times: " . json_encode($startTimes) . "<br>";
            $debugInfo .= "End Times: " . json_encode($endTimes) . "<br>";
            
            foreach ($classes as $index => $classId) {
                if (isset($days[$index]) && isset($startTimes[$index]) && isset($endTimes[$index])) {
                    $classData[$classId] = [
                        'day' => $days[$index],
                        'start_time' => $startTimes[$index],
                        'end_time' => $endTimes[$index],
                    ];
                } else {
                    // Data tidak lengkap, tambahkan nilai default
                    $classData[$classId] = [
                        'day' => $days[$index] ?? 'Senin',
                        'start_time' => $startTimes[$index] ?? '08:00',
                        'end_time' => $endTimes[$index] ?? '09:30',
                    ];
                    $debugInfo .= "Data tidak lengkap untuk kelas ID: {$classId}, indeks: {$index}<br>";
                }
            }
            $debugInfo .= "Final Class Data: " . json_encode($classData);
            
            // Simpan jadwal kelas dengan data lengkap
            $subject->classes()->sync($classData);
            
            // Kirim informasi debug ke session
            //return redirect()->route('subjects.edit', $subject)->with('debug', $debugInfo);
        } else {
            $subject->classes()->detach();
        }

        return redirect()->route('subjects.index')
            ->with('success', 'Mata pelajaran berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Subject $subject)
    {
        $subject->delete();
        return redirect()->route('subjects.index')
            ->with('success', 'Mata pelajaran berhasil dihapus.');
    }
}
