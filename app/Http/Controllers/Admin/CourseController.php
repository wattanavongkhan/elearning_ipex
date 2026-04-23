<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Course;
use App\Models\Category;
use App\Models\Enrollment;
use App\Models\Lesson_user;
use App\Models\Lesson;
use App\Models\CoursesFile;
use DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
class CourseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (Auth::user()->status=="1") {
            return redirect()->route('home');
        }else{
            $courses = Course::latest()->paginate(10);
            return view('admin.courses.index', compact('courses'));
        }

    }

    // หน้าฟอร์มสร้างคอร์สใหม่
    public function create()
    {
        $categories = Category::all();
        return view('admin.courses.create', compact('categories'));
    }

    // บันทึกข้อมูลคอร์สลงฐานข้อมูล
    public function store(Request $request)
    {
        // 1. Validation: ตรวจสอบข้อมูลให้เข้มงวด
        $request->validate([
            'category_id'     => 'required|exists:categories,id',
            'title'           => 'required|string|max:255',
            'description'     => 'required|string',
            'benefits'        => 'nullable|string',
            'target_audience' => 'nullable|string',
            'price'           => 'required|numeric|min:0',
            'thumbnail'       => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048', // จำกัด 2MB
        ], [
            'category_id.required' => 'กรุณาเลือกประเภทคอร์ส',
            'title.required'       => 'กรุณากรอกชื่อคอร์สเรียน',
            'price.numeric'        => 'ราคาต้องเป็นตัวเลขเท่านั้น',
        ]);

        // 2. Logic: สร้าง Course Code (ตัวอย่าง: CRS-2026-001)
        $year = date('Y');
        $lastCourse = \App\Models\Course::whereYear('created_at', $year)->latest()->first();
        $nextNumber = $lastCourse ? (int)substr($lastCourse->course_code, -3) + 1 : 1;
        $courseCode = 'CRS-' . $year . '-' . str_pad($nextNumber, 3, '0', STR_PAD_LEFT);

        // 3. File Upload: จัดการรูปภาพหน้าปก
        $thumbnailPath = null;
        if ($request->hasFile('thumbnail')) {
            // บันทึกไฟล์ลงใน storage/app/public/courses (จะถูก link ไปที่ public/storage/courses)
            $fileName = time() . '_' . $request->file('thumbnail')->getClientOriginalName();
            $thumbnailPath = $request->file('thumbnail')->storeAs('courses', $fileName, 'public');
        }

        // 4. Database: บันทึกข้อมูล
        // try {
            \App\Models\Course::create([
                'course_code'     => $courseCode,
                'category_id'     => $request->category_id,
                'title'           => $request->title,
                'slug'            => \Illuminate\Support\Str::slug($request->title),
                'description'     => $request->description,
                'benefits'        => $request->benefits,
                'target_audience' => $request->target_audience,
                'price'           => $request->price,
                'thumbnail'       => $thumbnailPath,
                'user_id'         => auth()->id(), // ไอดีผู้สร้าง (Admin)
            ]);

            return redirect()
                ->route('courses.index')
                ->with('success', "สร้างคอร์สเรียนสำเร็จ! รหัสคอร์สของคุณคือ: $courseCode");

        // } catch (\Exception $e) {
        //     // หากเกิดข้อผิดพลาด ให้ส่งกลับพร้อมข้อความ Error
        //     return back()
        //         ->withInput()
        //         ->withErrors(['error' => 'เกิดข้อผิดพลาดในการบันทึกข้อมูล: ' . $e->getMessage()]);
        // }
    }


    public function edit(Course $course)
    {
        $categories = Category::all();
        $course_files = \App\Models\CoursesFile::select('courses_files.*', 'file.file_name as private_file_name')
        ->leftjoin('private_files as file', 'courses_files.file_id', '=', 'file.id')
        ->where('courses_files.course_id', $course->id)
        ->get();

        $privatefile=\App\Models\PrivateFile::where('user_id', Auth::id())->get();
      
        return view('admin.courses.edit', compact('course', 'categories','course_files','privatefile'));
    }

    // 2. รับข้อมูลจากฟอร์มเพื่อบันทึกลงฐานข้อมูล
    public function update(Request $request, Course $course)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'thumbnail' => 'nullable|image|mimes:jpeg,png,jpg|max:2048', // ถ้ามีการอัปโหลดรูป
            'status' => 'required|in:0,1',
            'category_id' => 'required|exists:categories,id',
        ]);

        // ถ้ามีการอัปโหลดรูปภาพใหม่
        if ($request->hasFile('thumbnail')) {
            // โค้ดสำหรับลบรูปเก่าและบันทึกรูปใหม่ (ถ้ามี)
            $path = $request->file('thumbnail')->store('courses', 'public');
            $validated['thumbnail'] = $path;
        }

        $course->update($validated);

        return redirect()->route('courses.index')
                        ->with('success', 'อัปเดตข้อมูลคอร์สเรียบร้อยแล้ว');
    }


    public function destroy(Course $course)
    {
        try {
            // 1. ตรวจสอบและลบรูปภาพหน้าปกออกจากดิสก์
            if ($course->thumbnail) {
                Storage::disk('public')->delete($course->thumbnail);
            }

            // 2. ลบข้อมูลออกจากฐานข้อมูล
            $course->delete();

            return redirect()->route('courses.index')->with('success', 'ลบคอร์สเรียนและไฟล์ที่เกี่ยวข้องเรียบร้อยแล้ว');

        } catch (\Exception $e) {
            return back()->with('error', 'ไม่สามารถลบคอร์สได้: ' . $e->getMessage());
        }
    }

    public function learn($id, $lesson_id = null)
    {
        $user = Auth::user();

        // 1. ดึงข้อมูลคอร์ส และบทเรียน (โหลดความสัมพันธ์ของ Quiz มาด้วยเพื่อลด Query)
        $course = Course::with(['lessons' => function($query) {
            $query->orderBy('position', 'asc'); // เรียงตามลำดับบทเรียน
        }, 'lessons.pre_quiz', 'lessons.post_quiz'])->findOrFail($id);

        // 2. หาบทเรียนปัจจุบัน
        if (!$lesson_id) {
            $lastCompleted = Lesson_user::where('user_id', $user->id)
                ->where('course_id', $id)
                ->latest('updated_at')
                ->first();

            if ($lastCompleted) {
                // ไปบทถัดไปจากที่เรียนจบล่าสุด
                $currentLesson = $course->lessons->where('id', '>', $lastCompleted->lesson_id)->first()
                                ?? $course->lessons->where('id', $lastCompleted->lesson_id)->first();
            } else {
                // ถ้ายังไม่เคยเรียนเลย ให้เริ่มบทแรก
                $currentLesson = $course->lessons->first();
            }
        } else {
            $currentLesson = $course->lessons->where('id', $lesson_id)->first();
        }

        // 3. ดึงรายการบทเรียนที่เรียนจบแล้ว
        $completedLessons = Lesson_user::where('user_id', $user->id)
            ->where('course_id', $id)
            ->pluck('lesson_id')
            ->toArray();

        // 4. เช็คสถานะแบบทดสอบ (Pre-test และ Post-test)
        $hasDonePreQuiz = false;
        if ($currentLesson && $currentLesson->pre_quiz_id) {
            $hasDonePreQuiz = \App\Models\QuizAttempt::where('user_id', $user->id)
                ->where('quiz_id', $currentLesson->pre_quiz_id)
                ->where('status', 'passed')
                ->exists();
        }

        $userDonePreQuiz = false; // เปลี่ยนชื่อจาก $hasDonePreQuiz เป็น $userDonePreQuiz
        if ($currentLesson && $currentLesson->pre_quiz_id) {
            $userDonePreQuiz = \App\Models\QuizAttempt::where('user_id', $user->id)
                ->where('quiz_id', $currentLesson->pre_quiz_id)
                ->where('status', 'passed')
                ->exists();
        }

        // เช็ค Post-quiz: (เพิ่มส่วนนี้เพื่อให้ View รู้ว่าต้องโชว์ข้อสอบหลังเรียนไหม)
        $hasDonePostQuiz = false;
        if ($currentLesson && $currentLesson->post_quiz_id) {
            $hasDonePostQuiz = \App\Models\QuizAttempt::where('user_id', $user->id)
                ->where('quiz_id', $currentLesson->post_quiz_id)
                ->where('status', 'passed')
                ->exists();
        }

        // 5. หาบทเรียนถัดไป
        $nextLesson = $course->lessons->where('position', '>', $currentLesson->position)->first();
        $nextLessonUrl = $nextLesson ? route('courses.learn', [$course->id, $nextLesson->id]) : null;

        $file = CoursesFile::select('tf.file_name','tf.file_path')
        ->leftjoin('private_files as tf','tf.id','courses_files.file_id')
        ->where('course_id',$course->id)->get();

        return view('home.courses.learn', compact(
            'course',
            'currentLesson',
            'completedLessons',
            'nextLessonUrl',
            'hasDonePreQuiz',
            'hasDonePostQuiz',
            'userDonePreQuiz',
            'file'
        ));
    }

    public function updateProgress(Request $request)
    {
        $userId = auth()->id();
        $courseId = $request->course_id;
        $lessonId = $request->lesson_id;

        return DB::transaction(function () use ($userId, $courseId, $lessonId) {
            // 1. บันทึกว่าเรียน Lesson นี้จบแล้ว (ตาราง Progress รายบท)
            $progress = Lesson_user::updateOrCreate(
                ['user_id' => $userId, 'lesson_id' => $lessonId, 'course_id' => $courseId],
                ['is_completed' => 1, 'completed_at' => now()]
            );

            // 2. เช็คว่านี่คือ Lesson สุดท้ายของ Course นี้หรือไม่
            $totalLessons = Lesson::where('course_id', $courseId)->count();
            $completedLessons = Lesson_user::where('user_id', $userId)
                                ->where('course_id', $courseId)
                                ->where('is_completed', 1)
                                ->count();

            // 3. ถ้าจำนวนที่เรียนจบ = จำนวนทั้งหมด ให้ Update ตาราง Enrollment
            // 1. เช็คข้อมูล Enrollment เดิม
            $enrollment = Enrollment::where('user_id', $userId)
                            ->where('course_id', $courseId)
                            ->first();

            // 2. เช็คว่าบทเรียนปัจจุบันมี Post-Quiz หรือไม่ และทำผ่านหรือยัง
            $currentLesson = Lesson::find($lessonId); // รับค่า lesson_id มาจาก Request
            $hasPostQuiz = !empty($currentLesson->post_quiz_id);

            // ตรวจสอบผลการสอบของบทเรียนนี้
            $isQuizPassed = false;
            if ($hasPostQuiz) {
                $isQuizPassed = UserQuizResult::where('user_id', $userId)
                                ->where('quiz_id', $currentLesson->post_quiz_id)
                                // ->where('score', '>=', 50) // ถ้าคุณมีเกณฑ์คะแนนขั้นต่ำ
                                ->exists();
            }

            // 3. เช็คเงื่อนไขการจบหลักสูตร
            // เงื่อนไข: (เรียนครบทุกบท) และ (ถ้าบทนี้มีควิซ ต้องทำควิซผ่านแล้ว)
            if ($completedLessons >= $totalLessons) {
                
                if ($hasPostQuiz && !$isQuizPassed) {
                    // กรณีเรียนครบจำนวนบทแล้ว แต่ "ติด" ควิซบทสุดท้ายยังไม่ได้ทำ/ไม่ผ่าน
                    return response()->json([
                        'status' => 'success',
                        'course_completed' => false,
                        'message' => 'คุณเรียนเนื้อหาครบแล้ว กรุณาทำแบบทดสอบหลังเรียนให้ผ่านเพื่อจบหลักสูตร'
                    ]);
                }else {
                    // กรณีเรียนครบทุกบทแล้ว และไม่มีควิซ หรือมีควิซแต่ทำผ่านแล้ว -> อัปเดต Enrollment เป็นจบหลักสูตร (2)
                    $enrollment->update(['status' => 2]);

                    return response()->json([
                        'status' => 'success',
                        'course_completed' => true,
                        'message' => 'ยินดีด้วย! คุณเรียนจบหลักสูตรนี้แล้ว'
                    ]);
                }

                // ถ้าผ่านทุกเงื่อนไข -> อัปเดต Enrollment เป็นจบหลักสูตร (2)
                $enrollment->update(['status' => 2]);

                return response()->json([
                    'status' => 'success',
                    'course_completed' => true,
                    'message' => 'ยินดีด้วย! คุณเรียนจบหลักสูตรนี้แล้ว'
                ]);
            }

            // กรณีปกติ (ยังเรียนไม่ครบทุกบท)
            return response()->json([
                'status' => 'success',
                'course_completed' => false,
                'message' => 'บันทึกความคืบหน้าเรียบร้อย'
            ]);

            return response()->json([
                'status' => 'success',
                'course_completed' => false
            ]);
        });
    }

    public function updateProgressPercent(Request $request)
    {
        $userId = auth()->id();
        $courseId = $request->course_id;
        $currentPercent = $request->progress_percent; // ส่งมาจาก JavaScript (0-100)

        // 1. ดึง Enrollment
        $enrollment = Enrollment::where('user_id', $userId)
                        ->where('course_id', $courseId)
                        ->first();

        if (!$enrollment) {
            return response()->json(['message' => 'Enrollment not found'], 404);
        }

        // 2. อัปเดต Percentage เฉพาะเมื่อค่าใหม่มากกว่าเดิม (ป้องกันการส่งค่าถอยหลัง)
        if ($currentPercent > $enrollment->progress_percent) {
            $enrollment->progress_percent = $currentPercent;
        }

        // 3. เช็คเงื่อนไขการจบหลักสูตร (Status = 2)
        // เงื่อนไข: (เปอร์เซ็นต์รวม == 100) และ (ทำแบบทดสอบครบทุกอัน)
        
        // ดึง ID ควิซทั้งหมดที่มีในคอร์สนี้
        $requiredQuizIds = Lesson::where('course_id', $courseId)
                            ->whereNotNull('post_quiz_id')
                            ->pluck('post_quiz_id')
                            ->toArray();

        // เช็คว่าทำผ่านกี่อัน (สมมติใช้ table user_quiz_results)
        $passedQuizzesCount = UserQuizResult::where('user_id', $userId)
                                ->whereIn('quiz_id', $requiredQuizIds)
                                ->where('is_passed', true) 
                                ->count();

        $totalRequiredQuizzes = count($requiredQuizIds);

        // ตรรกะตัดสินใจเปลี่ยนสถานะ
        if ($enrollment->progress_percent >= 100 && $passedQuizzesCount >= $totalRequiredQuizzes) {
            $enrollment->status = 2; // 2 = Completed
            $enrollment->completed_at = now();
            $message = "ยินดีด้วย! คุณเรียนจบหลักสูตรและผ่านแบบทดสอบครบถ้วนแล้ว";
        } else {
            $enrollment->status = 1; // 1 = In Progress
            $message = "บันทึกความคืบหน้าเรียบร้อย (" . $enrollment->progress_percent . "%)";
            
            // ถ้าดูจบแต่ควิซยังไม่ครบ ให้แจ้งเตือนเสริม
            if ($enrollment->progress_percent >= 100 && $passedQuizzesCount < $totalRequiredQuizzes) {
                $message .= " แต่อย่าลืมทำแบบทดสอบให้ครบเพื่อจบหลักสูตร";
            }
        }

        $enrollment->save();

        return response()->json([
            'status' => 'success',
            'progress' => $enrollment->progress_percent,
            'course_completed' => ($enrollment->status == 2),
            'message' => $message
        ]);
    }

    public function addFile(Request $request)
    {
        if(\App\Models\CoursesFile::where('file_id',$request->file_id)
            ->where('course_id',$request->course_id)->count()==0)
        {
            \App\Models\CoursesFile::create([
                'course_id' => $request->course_id,
                'file_id' => (int)$request->file_id,
                'user_id' => auth()->id(), // เพิ่ม user_id เพื่อเก็บว่าใครเพิ่มไฟล์นี้
            ]);

            $value='เพิ่มไฟล์เรียบร้อยแล้ว';
        }else{
            $value='ไม่สำเร็จ!';
        }
        return redirect()->back()->with('success',$value);
    }

    public function removeFile(Request $req)
    {
        $courseFile = \App\Models\CoursesFile::where('file_id', $req->file_id)->first();
        $courseFile->delete();
        return redirect()->back()->with('success', 'ลบไฟล์เรียบร้อยแล้ว');
    }

    public function showFiles($id)
    {
        $courseFiles = \App\Models\CoursesFile::select('courses_files.*', 'file.file_name as private_file_name', 'file.file_path')
            ->leftjoin('private_files as file', 'courses_files.file_id', '=', 'file.id')
            ->where('courses_files.course_id', $id)
            ->get();
        return response()->json($courseFiles);
    }


}
