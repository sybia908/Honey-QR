<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\Subject;
use Carbon\Carbon;
use Illuminate\Http\Request;

class StudentScheduleController extends Controller
{
    public function subjects(Request $request)
    {
        $user = $request->user();
        $student = $user->student;

        $subjects = $student->class->subjects()
            ->with(['teachers' => function ($query) {
                $query->select('users.name', 'teachers.*')
                    ->join('users', 'users.id', '=', 'teachers.user_id');
            }])
            ->get()
            ->map(function ($subject) {
                return [
                    'id' => $subject->id,
                    'code' => $subject->code,
                    'name' => $subject->name,
                    'credits' => $subject->credits,
                    'description' => $subject->description,
                    'schedule' => [
                        'day' => $subject->pivot->day,
                        'start_time' => $subject->pivot->start_time,
                        'end_time' => $subject->pivot->end_time,
                    ],
                    'teachers' => $subject->teachers->map(function ($teacher) {
                        return [
                            'id' => $teacher->id,
                            'name' => $teacher->name,
                        ];
                    }),
                ];
            });

        return response()->json(['subjects' => $subjects]);
    }

    public function todaySchedule(Request $request)
    {
        $user = $request->user();
        $student = $user->student;
        $today = Carbon::now()->format('l');

        // Convert English day name to Indonesian
        $dayMap = [
            'Sunday' => 'Minggu',
            'Monday' => 'Senin',
            'Tuesday' => 'Selasa',
            'Wednesday' => 'Rabu',
            'Thursday' => 'Kamis',
            'Friday' => 'Jumat',
            'Saturday' => 'Sabtu',
        ];

        $subjects = $student->class->subjects()
            ->with(['teachers' => function ($query) {
                $query->select('users.name', 'teachers.*')
                    ->join('users', 'users.id', '=', 'teachers.user_id');
            }])
            ->wherePivot('day', $dayMap[$today])
            ->orderBy('start_time')
            ->get()
            ->map(function ($subject) {
                return [
                    'id' => $subject->id,
                    'code' => $subject->code,
                    'name' => $subject->name,
                    'credits' => $subject->credits,
                    'schedule' => [
                        'day' => $subject->pivot->day,
                        'start_time' => $subject->pivot->start_time,
                        'end_time' => $subject->pivot->end_time,
                    ],
                    'teachers' => $subject->teachers->map(function ($teacher) {
                        return [
                            'id' => $teacher->id,
                            'name' => $teacher->name,
                        ];
                    }),
                    'attendance_status' => $this->getAttendanceStatus($subject, $student),
                ];
            });

        return response()->json([
            'today' => $dayMap[$today],
            'subjects' => $subjects
        ]);
    }

    private function getAttendanceStatus($subject, $student)
    {
        $today = Carbon::today();
        $attendance = $student->attendances()
            ->where('subject_id', $subject->id)
            ->whereDate('created_at', $today)
            ->first();

        if (!$attendance) {
            return 'not_yet';
        }

        return $attendance->status;
    }
}
