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

    public function store(Request $request, Quiz $quiz) 
    {
         // 1. Validation: เพิ่มการเช็คไฟล์รูปภาพ
        $validated = $request->validate([ 
            'question_text' => 'required|string',
            'options' => 'required|array',
            'correct_answer' => 'required|in:A,B,C,D',
        ]);

        // 2. จัดการรูปภาพหลักของคำถาม
        if($request->hasFile('question_image')) {
            $path = $request->file('question_image')->store('question_images', 'public');
            $validated['question_image'] = 'storage/' . $path;
        }

        $optionImagePaths = [];
        if($request->hasFile('option_images')) {
            foreach($request->file('option_images') as $choice => $file) {
                $fileName = "quiz_{$quiz->id}_opt_{$choice}_" . time() . "." . $file->getClientOriginalExtension();
                $path = $file->storeAs('option_images', $fileName, 'public');
                $optionImagePaths[$choice] = 'storage/' . $path;
            }
        }
        
        $validated['option_images'] =  $optionImagePaths; 

        // 4. บันทึกข้อมูลลงฐานข้อมูล
        $quiz->questions()->create($validated);

        return redirect()->route('admin.questions.index', $quiz->id) ->with('success', 'เพิ่มคำถามเรียบร้อยแล้ว');
    }

    // 1. หน้าแสดงฟอร์มแก้ไข
    public function edit(Quiz $quiz, Question $question) {
        return view('admin.questions.edit', compact('quiz', 'question'));
    }

    // 2. ฟังก์ชันประมวลผลการแก้ไข
    public function update(Request $request, Quiz $quiz, Question $question) 
    {
        
        $validated=$request->validate(['question_text'=> 'required|string',
            'options'=> 'required|array|min:4',
            'correct_answer'=> 'required|in:A,B,C,D',
            ]);

        if($request->hasFile('question_image')) {
            $path=$request->file('question_image')->store('public/question_images');
            $validated['question_image']=Str::replaceFirst('public/', 'storage/', $path);
        }

        if($request->filled('no_image')) {
            $validated['question_image']=null;
        }

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
