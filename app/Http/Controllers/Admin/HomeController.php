<?php namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Lesson;
use App\Models\Patal;
use App\Models\Category;
use App\Models\Activity;
use App\Models\Schedule;
use App\Models\Quiz;
use App\Models\Room;
use App\Models\Enrollment;
use Illuminate\Http\Request;
use DB;
use Illuminate\Support\Facades\Auth;
class HomeController extends Controller {

    public function index(Request $request)
    {
        $featuredCourses=Course::select("courses.*", "categories.category_name")
            ->leftJoin('categories', 'courses.category_id', '=', 'categories.id')
            ->where('courses.status', 0)
            ->get();


            
        $activities = Activity::where('status', 1)
                                ->latest()
                                ->take(3)
                                ->get();
        $patals = Patal::orderBy('seq_no', 'asc')->get();

       
        // 1. ดึงข้อมูลตารางหลัก (Activities/Schedule) ด้วย Eloquent
        $schedule = Schedule::all();
        $events1 = $schedule->map(function($item) {
            return [
                'id' => $item->id,
                'title' => $item->purpose, //
                'start' => $item->start_date, //
                'end' => $item->end_date, //
                'backgroundColor' => $item->category_color ?? '#10b981', //
                'borderColor' => $item->category_color ?? '#10b981',
                'extendedProps' => [
                    'type' => 'activity',
                    'info1' => Room::find($item->room)->name ?? 'N/A', // เลขห้อง
                    'info2' => $item->members // รายชื่อ
                ]
            ];
        });

        // 2. ดึงข้อมูลจากตาราง guest (ต้อนรับลูกค้า)
        $guests = DB::table('guest')->get();
        $events2 = $guests->map(function($item) {
            return [
                'id' => $item->id,
                'title' => '👤 ' . $item->members, // ดึงรายชื่อแขกขึ้นแสดง
                'start' => $item->start_date, //
                'end' => $item->end_date, //
                'backgroundColor' => $item->category_color ?? '#f59e0b', //
                'borderColor' => $item->category_color ?? '#f59e0b',
                'extendedProps' => [
                    'type' => 'guest',
                    'info1' => $item->hotel_name, // โรงแรม
                    'info2' => $item->request_booking, // คนจอง
                    'info3' => DB::table('center_staff_db.tblemployee')->where('id', $item->user_id)->first()->full_name_eng ?? 'N/A' // เอกสารการจอง
                ]
            ];
        });

        // 3. ดึงข้อมูลจากตาราง business_trip ข้าม Database (elearning_db)
        $trips = DB::table('elearning_db.business_trip')->get();
        $events3 = $trips->map(function($item) {
            return [
                'id' => $item->id,
                'title' => '✈️ ' . $item->purpose, // วัตถุประสงค์ (ไปที่ไหน)
                'start' => $item->start_date, //
                'end' => $item->end_date, //
                'backgroundColor' => $item->category_color ?? '#3b82f6', //
                'borderColor' => $item->category_color ?? '#3b82f6',
                'extendedProps' => [
                    'type' => 'trip',
                    'info1' => $item->departure_flight, // เที่ยวบินไป
                    'info2' => $item->arrive_flight, // เที่ยวบินกลับ
                    'info3' => $item->remarks // หมายเหตุ
                ]
            ];
        });

        // 🔥 มัดรวมข้อมูลทั้ง 3 Collection เข้าด้วยกันเป็นตัวแปรเดียวเพื่อส่งไปหน้าปฏิทิน
        $events = $events1->concat($events2)->concat($events3);


        return view('dashboard', compact('featuredCourses','activities','patals','events'));
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
