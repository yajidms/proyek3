<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Enrollment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StudentController extends Controller
{
    public function courses()
    {
        $courses = Course::orderBy('code')->paginate(10);
        return view('student.courses.index', compact('courses'));
    }

    public function enroll(Request $request, Course $course)
    {
        $user = Auth::user();
        Enrollment::firstOrCreate([
            'user_id' => $user->id,
            'course_id' => $course->id,
        ], ['status' => true]);

        return redirect()->route('student.courses')->with('status', 'Enrolled to '.$course->name);
    }
}
