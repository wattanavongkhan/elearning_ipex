<?php namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Lesson;
use App\Models\Patal;
use App\Models\Category;
use App\Models\Activity;
use App\Models\Quiz;
use App\Models\Enrollment;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use DB;
use Illuminate\Support\Facades\Hash;

use Illuminate\Support\Facades\Auth;

class HomeController extends Controller {

    public function index(Request $request)
    {
        if(Auth::check()) 
        {
            // if(auth()->user()->status == 0) 
            if(session()->get('role_name') == 'Admin')
            {
                return redirect()->route('admin.dashboard.index');
            }   
        } 

        $featuredCourses=Course::select("courses.*", "categories.category_name")
            ->leftJoin('categories', 'courses.category_id', '=', 'categories.id')
            ->where('courses.status', 0)
            ->get();

        $activities = Activity::where('status', 1)
                                ->latest()
                                ->take(3)
                                ->get();
        $patals = Patal::orderBy('seq_no', 'asc')->get();

        return view('dashboard', compact('featuredCourses','activities','patals'));
    }

    public function courses_show($id) {
        $course=Course::findOrFail($id);
        $lessons=Lesson::where('course_id', $id)->get();
        $quizzes=Quiz::where('course_id', $id)->get();
        $alreadyEnrolled = Enrollment::where('user_id', Auth::id())
                                     ->where('course_id', $id)
                                     ->exists();
        

        return view('home.courses.show', compact('course', 'lessons', 'quizzes', 'alreadyEnrolled'));
    }

    public function course_all(Request $request)
    {
       $courses = Course::leftJoin('categories', 'courses.category_id', '=', 'categories.id')
       ->select('courses.*', 'categories.category_name')
       ->where('courses.status', 0) // แสดงเฉพาะคอร์สที่เปิดใช้งาน
       ->when($request->category_id, function ($query, $category_id) {
           return $query->where('courses.category_id', $category_id);
       });

       if ($request->search!=null) {
        $courses = $courses->where('courses.title', 'like', '%' . $request->search . '%');
       }
       $courses=$courses->latest()->paginate(9);

       $categories = Category::withCount('courses')->whereHas('courses', function ($query) {
           $query->where('courses.status', 0);
       })->get();
       return view('home.courses.index', compact('courses', 'categories'));
    }

    public function categories_all()
    {
        $categories = Category::withCount('courses')->get();
        return view('home.courses.categories', compact('categories'));
    }

    public function activities_all(Request $request)
    {
        $query = Activity::where('status', 1);

        // กรองตามหมวดหมู่ถ้ามีการส่งค่ามา
        if ($request->has('category') && $request->category != 'All') {
            $query->where('category', $request->category);
        }

        $activities = $query->latest()->paginate(9); // แสดงหน้าละ 9 รายการ

        return view('home.activities.showall', compact('activities'));
    }

}
