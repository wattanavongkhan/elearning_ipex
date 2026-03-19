<?php namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Lesson;
use App\Models\User;
use App\Models\Category;
use App\Models\Enrollment;
use App\Models\Quiz;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller {

    public function index() 
    {
       $user = Auth::user();
    
        // ดึงคอร์สที่ลงทะเบียนไว้ พร้อมข้อมูลคอร์ส
        $enrollments = Enrollment::where('user_id', $user->id)
            ->with('course')
            ->latest()
            ->get();

        // ตัวอย่างการคำนวณสถิติ (ถ้ามีระบบเก็บข้อมูลบทเรียนที่เรียนจบแล้ว)
        $totalCourses = $enrollments->count();
        $completedCourses = $enrollments->where('status', 'completed')->count(); // หรือเช็คจาก progress = 100

        // ดึงรายการ Lesson ID ที่ User นี้เรียนจบแล้วทั้งหมด
    $completedLessonIds = \App\Models\Lesson_user::where('user_id', $user->id)
        ->pluck('lesson_id')
        ->toArray();

        return view('home.profile.index', compact('user', 'enrollments', 'totalCourses', 'completedCourses', 'completedLessonIds'));
    }
}
