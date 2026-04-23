<?php namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Lesson;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\Quiz;
use App\Models\Question;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class QuizController extends Controller {

    // 1. หน้าแสดงรายการบทเรียนทั้งหมดของคอร์สนั้นๆ
    public function index(Course $course) {
        $quizzes=$course->quizzes()->withCount('questions')->get();
        return view('admin.quizzes.index', compact('course', 'quizzes'));
    }

    // 2. หน้าฟอร์มเพิ่มบทเรียน
    public function create(Course $course) {
        return view('admin.quizzes.create', compact('course'));
    }

    public function store(Request $request, Course $course) {

        $validated=$request->validate([ 'title'=> 'required|string|max:255',
            'type'=> 'required|in:pre-test,post-test',
            ]);

        // สร้าง Quiz ใหม่
        $validated['user_id']=auth()->id();
        $quiz=$course->quizzes()->create($validated);

        // หลังจากสร้างเสร็จ ให้ส่งไปหน้าจัดการคำถามทันที
        return redirect()->route('admin.questions.index', $quiz->id)->with('success', 'สร้างชุดข้อสอบเรียบร้อยแล้ว เริ่มเพิ่มคำถามได้เลย!');
    }

    // 4. หน้าฟอร์มแก้ไข
    public function manageQuestions(Quiz $quiz) {
        $questions=$quiz->questions;
        return view('admin.quizzes.questions', compact('quiz', 'questions'));
    }


    public function edit(Course $course,$id) {
        $quiz=Quiz::find($id);
        return view('admin.quizzes.edit', compact('course', 'quiz'));
    }

    public function update(Request $request, Quiz $quiz) {
        // 1. ตรวจสอบข้อมูล (Validation)
        $validated=$request->validate([ 'title'=> 'required|string|max:255',
            'type'=> 'required|in:pre-test,post-test',
            ], [ 'title.required'=> 'กรุณาระบุชื่อชุดข้อสอบ',
            'type.required'=> 'กรุณาเลือกประเภทข้อสอบ',
            ]);

        try {
            // 2. อัปเดตข้อมูลลงฐานข้อมูล
            $quiz->update($validated);
            Lesson::where('id', $request->lesson_id)
            ->update(['pre_quiz_id' => $request->type == 'pre-test' ? $quiz->id : null,
                'post_quiz_id' => $request->type == 'post-test' ? $quiz->id : null]);

            // 3. Redirect กลับไปหน้า Index ของ Quizzes โดยใช้ course_id จากตัว Quiz เอง
            return $this->index($quiz->course); // หรือจะใช้ redirect()->route('admin.quizzes.index', $quiz->course_id) ก็ได้ครับ
        }

        catch (\Exception $e) {
            return redirect() ->back() ->withInput() ->with('error', 'เกิดข้อผิดพลาดในการอัปเดต: '. $e->getMessage());
        }
    }

    public function destroy(Quiz $quiz) {
        // 1. เก็บ ID ของคอร์สไว้ก่อนลบ เพื่อใช้ในการ Redirect กลับ
        $courseId=$quiz->course_id;

        try {
            // 2. ลบคำถามทั้งหมดที่อยู่ในชุดข้อสอบนี้ก่อน (ถ้าไม่ได้ตั้ง On Delete Cascade ใน Migration)
            $quiz->questions()->delete();

            // 3. ลบตัวชุดข้อสอบ
            $quiz->delete();

            return redirect()->route('admin.quizzes.index', $courseId) ->with('success', 'ลบชุดข้อสอบและคำถามทั้งหมดเรียบร้อยแล้ว');
        }

        catch (\Exception $e) {
            return redirect()->back()->with('error', 'ไม่สามารถลบได้: '. $e->getMessage());
        }
    }

    public function submit(Request $request)
    {
        $quiz_id = $request->quiz_id;
        $questions = Question::where('quiz_id', $quiz_id)->get();
        
        $score = 0;
        $total = $questions->count();

       
        foreach ($questions as $question) {
            $userAnswer = $request->input('question_' . $question->id); // จะได้ค่า A, B, C หรือ D
            
            if ($userAnswer === $question->correct_answer) {
                $score++;
            }
        }

        $passed = ($score / $total) >= 0.8; // ผ่าน 80%

        // บันทึกผลลง QuizAttempt
        \App\Models\QuizAttempt::updateOrCreate(
            ['user_id' => auth()->id(), 'quiz_id' => $quiz_id],
            [
                'score' => $score, 
                'total' => $total, 
                'status' => $passed ? 'passed' : 'failed'
            ]
        );

        return response()->json([
            'passed' => $passed,
            'score' => $score,
            'total' => $total
        ]);
    }

    public function show($quiz_id)
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'กรุณาเข้าสู่ระบบก่อนลงทะเบียนคอร์ส');    
        }

        $quiz = Quiz::findOrFail($quiz_id);
        
        $questions = Question::where('quiz_id', $quiz_id)->get();

        $lesson = \App\Models\Lesson::where('pre_quiz_id', $quiz_id)
                    ->orWhere('post_quiz_id', $quiz_id)
                    ->first();

        if (!$lesson) {
            abort(404, 'Lesson not found for this quiz');
        }

        $course = \App\Models\Course::find($lesson->course_id);

        return view('home.quiz.show', compact('quiz', 'questions', 'lesson', 'course'));
    }

}
