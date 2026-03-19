<?php namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Lesson;
use App\Models\User;
use App\Models\Province;
use App\Models\District;
use App\Models\Subdistrict;
use App\Models\Enrollment;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class CourseRegisterController extends Controller 
{
    public function courses_register($id) {
        $course = Course::findOrFail($id);
        // ตรวจสอบว่าเคยลงทะเบียนไปแล้วหรือยัง
        $alreadyEnrolled = Enrollment::where('user_id', Auth::id())
                                     ->where('course_id', $id)
                                     ->exists();

        if ($alreadyEnrolled) {
            return redirect()->route('courses.show', $id)
                             ->with('error', 'คุณได้ลงทะเบียนคอร์สนี้ไปแล้ว');
        }

        return view('home.courses.register', compact('course'));

    }

    public function courses_store(Request $request, $id) 
    {
        $course = Course::findOrFail($id);
        $user = Auth::user();
        // dd($course, $user);
        // เช็คซ้ำอีกรอบว่าเคยสมัครไปยัง (ป้องกันการกดเบิ้ล)
        $exists = Enrollment::where('user_id', $user->id)
                            ->where('course_id', $course->id)
                            ->first();

        if ($exists) {
            return redirect()->route('courses.show', $id)->with('info', 'คุณสมัครคอร์สนี้แล้ว');
        }

        // Logic 0 บาท หรือ มากกว่า 0 บาท
        $status = ($course->price <= 0) ? '1' : '0';

        Enrollment::create([
            'user_id' => $user->id,
            'course_id' => $course->id,
            'amount' => $course->price ?? 0,
            'status' => $status,
        ]);
       

        if ($status == '1') {
            return redirect()->route('courses.show', $id)
                            ->with('success', 'ลงทะเบียนสำเร็จ! พร้อมเข้าเรียนได้ทันที');
        } else {
            return redirect()->route('courses.show', $id)
                            ->with('success', 'ลงทะเบียนสำเร็จ! กรุณารอเจ้าหน้าที่ตรวจสอบการชำระเงิน');
        }

    }

}
