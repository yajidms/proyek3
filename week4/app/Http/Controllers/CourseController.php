<?php

namespace App\Http\Controllers;

use App\Models\Course;
use Illuminate\Http\Request;

class CourseController extends Controller
{
    public function index()
    {
        $courses = Course::latest()->paginate(10);
        return view('admin.courses.index', compact('courses'));
    }

    public function create()
    {
        return view('admin.courses.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'code' => ['required', 'string', 'max:50', 'unique:courses,code'],
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'credits' => ['required', 'integer', 'min:0', 'max:50'],
        ]);
        Course::create($data);
        return redirect()->route('admin.courses.index')->with('status', 'Course created');
    }

    public function edit(Course $course)
    {
        return view('admin.courses.edit', compact('course'));
    }

    public function update(Request $request, Course $course)
    {
        $data = $request->validate([
            'code' => ['required', 'string', 'max:50', 'unique:courses,code,'.$course->id],
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'credits' => ['required', 'integer', 'min:0', 'max:50'],
        ]);
        $course->update($data);
        return redirect()->route('admin.courses.index')->with('status', 'Course updated');
    }

    public function destroy(Course $course)
    {
        $course->delete();
        return redirect()->route('admin.courses.index')->with('status', 'Course deleted');
    }
}
