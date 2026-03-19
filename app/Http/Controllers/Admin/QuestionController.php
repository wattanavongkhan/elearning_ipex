<?php namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Lesson;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\Quiz;
use App\Models\Question;
use Illuminate\Support\Facades\Storage;

class QuestionController extends Controller {

    public function index(Quiz $quiz) {
        // $questions=$quiz->questions;
        // return view('admin.quizzes.questions', compact('quiz', 'questions'));
        $questions=$quiz->questions;

        return view('admin.questions.index', compact('quiz', 'questions'));
    }

    // 2. หน้าฟอร์มเพิ่มบทเรียน
    public function create(Quiz $quiz) {
        // ดึงรายการคำถามที่มีอยู่แล้วของ Quiz นี้
        $questions=$quiz->questions;

        return view('admin.questions.create', compact('quiz', 'questions'));
    }

    public function store(Request $request, Quiz $quiz) {
        // $request->validate([ 'question_text'=> 'required',
        //     'options'=> 'required|array|min:4',
        //     'correct_answer'=> 'required|in:A,B,C,D',
        //     ]);

        // $validated['user_id']=auth()->id();

        // $quiz->questions()->create($validated + $request->all());

        // return redirect()->route('admin.questions.index', $quiz->id)->with('success', 'เพิ่มข้อสอบเรียบร้อยแล้ว');
        $validated=$request->validate([ 'question_text'=> 'required|string',
            'options'=> 'required|array',
            'correct_answer'=> 'required|in:A,B,C,D',
            ]);

        // สร้างคำถามโดยผูกกับ quiz_id (ID 12)
        $quiz->questions()->create($validated);

        return redirect()->route('admin.questions.index', $quiz->id) ->with('success', 'เพิ่มคำถามเรียบร้อยแล้ว');
    }

    // 1. หน้าแสดงฟอร์มแก้ไข
    public function edit(Quiz $quiz, Question $question) {
        return view('admin.questions.edit', compact('quiz', 'question'));
    }

    // 2. ฟังก์ชันประมวลผลการแก้ไข
    public function update(Request $request, Quiz $quiz, Question $question) {
        $validated=$request->validate([ 'question_text'=> 'required|string',
            'options'=> 'required|array|min:4',
            'correct_answer'=> 'required|in:A,B,C,D',
            ]);

        $question->update($validated);

        return redirect()->route('admin.questions.index', $quiz->id) ->with('success', 'แก้ไขคำถามเรียบร้อยแล้ว');
    }

    public function destroy(Quiz $quiz, Question $question) {
        try {
            // ลบคำถามออกจากฐานข้อมูล
            $question->delete();

            // Redirect กลับไปที่หน้ารวมคำถามของ Quiz นั้น พร้อมข้อความสำเร็จ
            return redirect() ->route('admin.questions.index', $quiz->id) ->with('success', 'ลบคำถามเรียบร้อยแล้ว');

        }

        catch (\Exception $e) {
            return redirect() ->back() ->with('error', 'ไม่สามารถลบคำถามได้: '. $e->getMessage());
        }
    }

    // 4. หน้าฟอร์มแก้ไข
    // public function manageQuestions(Quiz $quiz) {
    //     $questions=$quiz->questions;
    //     return view('admin.quizzes.questions', compact('quiz', 'questions'));
    // }


}
