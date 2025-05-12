<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Subject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SubjectController extends Controller
{
    public function index()
    {
        $subjects = Subject::with(['teachers.user', 'classes'])->get();

        return response()->json([
            'status' => 'success',
            'data' => $subjects
        ]);
    }

    public function show($id)
    {
        $subject = Subject::with(['teachers.user', 'classes'])->find($id);

        if (!$subject) {
            return response()->json([
                'status' => 'error',
                'message' => 'Mata pelajaran tidak ditemukan'
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'data' => $subject
        ]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'code' => 'required|string|max:255|unique:subjects',
            'name' => 'required|string|max:255',
            'credits' => 'required|integer|min:0',
            'description' => 'nullable|string',
            'teachers' => 'required|array',
            'teachers.*' => 'exists:teachers,id',
            'classes' => 'required|array',
            'classes.*' => 'exists:classes,id',
            'days' => 'required|array',
            'days.*' => 'required|in:Senin,Selasa,Rabu,Kamis,Jumat,Sabtu',
            'start_times' => 'required|array',
            'start_times.*' => 'required|date_format:H:i',
            'end_times' => 'required|array',
            'end_times.*' => 'required|date_format:H:i'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        $subject = Subject::create($request->only(['code', 'name', 'credits', 'description']));

        $subject->teachers()->attach($request->teachers);

        foreach ($request->classes as $index => $classId) {
            $subject->classes()->attach($classId, [
                'day' => $request->days[$index],
                'start_time' => $request->start_times[$index],
                'end_time' => $request->end_times[$index]
            ]);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Mata pelajaran berhasil ditambahkan',
            'data' => $subject->load(['teachers.user', 'classes'])
        ], 201);
    }

    public function update(Request $request, $id)
    {
        $subject = Subject::find($id);

        if (!$subject) {
            return response()->json([
                'status' => 'error',
                'message' => 'Mata pelajaran tidak ditemukan'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'code' => 'required|string|max:255|unique:subjects,code,' . $id,
            'name' => 'required|string|max:255',
            'credits' => 'required|integer|min:0',
            'description' => 'nullable|string',
            'teachers' => 'required|array',
            'teachers.*' => 'exists:teachers,id',
            'classes' => 'required|array',
            'classes.*' => 'exists:classes,id',
            'days' => 'required|array',
            'days.*' => 'required|in:Senin,Selasa,Rabu,Kamis,Jumat,Sabtu',
            'start_times' => 'required|array',
            'start_times.*' => 'required|date_format:H:i',
            'end_times' => 'required|array',
            'end_times.*' => 'required|date_format:H:i'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        $subject->update($request->only(['code', 'name', 'credits', 'description']));

        $subject->teachers()->sync($request->teachers);
        $subject->classes()->detach();

        foreach ($request->classes as $index => $classId) {
            $subject->classes()->attach($classId, [
                'day' => $request->days[$index],
                'start_time' => $request->start_times[$index],
                'end_time' => $request->end_times[$index]
            ]);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Mata pelajaran berhasil diperbarui',
            'data' => $subject->load(['teachers.user', 'classes'])
        ]);
    }

    public function destroy($id)
    {
        $subject = Subject::find($id);

        if (!$subject) {
            return response()->json([
                'status' => 'error',
                'message' => 'Mata pelajaran tidak ditemukan'
            ], 404);
        }

        $subject->teachers()->detach();
        $subject->classes()->detach();
        $subject->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Mata pelajaran berhasil dihapus'
        ]);
    }
}
