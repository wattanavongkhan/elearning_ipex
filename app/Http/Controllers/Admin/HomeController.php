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

class HomeController extends Controller {

    public function index() {
        
        $featuredCourses=Course::select("courses.*", "categories.category_name")
        ->leftJoin('categories', 'courses.category_id', '=', 'categories.id') ->get();
        return view('dashboard', compact('featuredCourses'));
    }

    public function courses_show($id) {
        $course=Course::findOrFail($id);
        $lessons=Lesson::where('course_id', $id)->get();
        $quizzes=Quiz::where('course_id', $id)->get();
        return view('home.courses.show', compact('course', 'lessons', 'quizzes'));
    }

    public function course_all(Request $request) 
    {
       $courses = Course::leftJoin('categories', 'courses.category_id', '=', 'categories.id')
       ->select('courses.*', 'categories.category_name')
       ->when($request->category_id, function ($query, $category_id) {
           return $query->where('courses.category_id', $category_id);
       })
       ->latest()->paginate(9);

       $categories = Category::withCount('courses') // นับจำนวน courses ในแต่ละ category
       ->get();
       return view('home.courses.index', compact('courses', 'categories'));
    }

    public function categories_all() 
    {
        $categories = Category::withCount('courses')->get();
        return view('home.courses.categories', compact('categories'));
    }
}
