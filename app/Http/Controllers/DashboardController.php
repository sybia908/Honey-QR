<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Classes;
use App\Models\Student;
use App\Models\Teacher;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $data = [
            'total_classes' => Classes::count(),
            'total_students' => Student::count(),
            'total_teachers' => Teacher::count(),
            'total_attendances' => Attendance::whereDate('created_at', Carbon::today())->count(),
        ];

        if (auth()->user()->hasRole('admin')) {
            return view('dashboard.admin', $data);
        } elseif (auth()->user()->hasRole('guru')) {
            return view('dashboard.teacher', $data);
        } else {
            return view('dashboard.student', $data);
        }
    }
}
