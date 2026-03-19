<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Lesson;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class LessonController extends Controller
{
    // 1. หน้าแสดงรายการบทเรียนทั้งหมดของคอร์สนั้นๆ
    public function index(Course $course)
    {
        $lessons = $course->lessons()->orderBy('position', 'asc')->get();
        
        return view('admin.lessons.index', compact('course', 'lessons'));
    }

    // 2. หน้าฟอร์มเพิ่มบทเรียน
    public function create(Course $course)
    {
        return view('admin.lessons.create', compact('course'));
    }

    // 3. บันทึกข้อมูลบทเรียน
    public function store(Request $request, Course $course)
    {
        $request->validate([
            'title' => 'required|max:255',
            'video_url' => 'nullable|url', // สำหรับลิงก์ YouTube/Vimeo
            'video_file' => 'nullable|mimetypes:video/mp4,video/quicktime|max:102400', // 100MB
            'content' => 'nullable',
            'is_free' => 'boolean'
        ]);

        $videoPath = null;
        if ($request->hasFile('video_file')) {
            $videoPath = $request->file('video_file')->store('courses/videos', 'public');
        }

        // หาตำแหน่งลำดับสุดท้าย
        $lastPosition = $course->lessons()->max('position') ?? 0;

        $course->lessons()->create([
            'title' => $request->title,
            'slug' => Str::slug($request->title) . '-' . time(),
            'content' => $request->content,
            'video_url' => $request->video_url,
            'video_path' => $videoPath,
            'position' => $lastPosition + 1,
            'is_free' => $request->has('is_free'),
            'user_id' => auth()->id()
        ]);

        return redirect()->route('lessons.index', $course->id)
            ->with('success', 'เพิ่มบทเรียนเรียบร้อยแล้ว');
    }

  public function edit(Lesson $lesson)
{
    // ดึงข้อมูล Course ที่บทเรียนนี้สังกัดอยู่
    $course = $lesson->course; 
    // ส่งทั้ง $lesson และ $course ไปที่ view
    // dd($course);
    return view('admin.lessons.edit', compact('lesson', 'course'));
}

    // 5. อัปเดตข้อมูล
    public function update(Request $request, Lesson $lesson)
    {
        $request->validate([
            'title' => 'required|max:255',
            'video_url' => 'nullable|url',
        ]);

        $data = $request->all();
        $data['slug'] = Str::slug($request->title) . '-' . $lesson->id;
        $data['is_free'] = $request->has('is_free');

        if ($request->hasFile('video_file')) {
            // ลบวิดีโอเก่า
            if ($lesson->video_path) {
                Storage::disk('public')->delete($lesson->video_path);
            }
            $data['video_path'] = $request->file('video_file')->store('courses/videos', 'public');
        }

        $lesson->update($data);

        return redirect()->route('lessons.index', $lesson->course_id)
            ->with('success', 'อัปเดตบทเรียนเรียบร้อยแล้ว');
    }

    // 6. ลบบทเรียน
    public function destroy(Lesson $lesson)
    {
        if ($lesson->video_path) {
            Storage::disk('public')->delete($lesson->video_path);
        }
        
        $courseId = $lesson->course_id;
        $lesson->delete();

        return redirect()->route('lessons.index', $courseId)
            ->with('success', 'ลบบทเรียนเรียบร้อยแล้ว');
    }
}
