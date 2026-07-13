<?php namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Enrollment;
use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class ReportController extends Controller 
{
    public function index(Request $req) 
    {
        $student = Enrollment::select(
            'enrollments.*', 
            'emp.full_name_th', 
            'c.title as course_title',
            'sec.section', 
            'emp.em_code'
        )
        ->join('center_staff_db.tblemployee as emp', 'enrollments.user_id', '=', 'emp.id')
        ->join('center_staff_db.tblsection as sec', 'emp.section_id', '=', 'sec.id') 
        ->join('courses as c', 'enrollments.course_id', '=', 'c.id');
        if ($req->course_id !=null) 
        {
            $student = $student->where('c.id', $req->course_id);
        }

        if ($req->student_id!=null) 
        {
            $student = $student->where('emp.id', $req->student_id);
        }

        if ($req->start_date!=null) {
            $student = $student->where('enrollments.created_at', '>=', $req->start_date)
            ->where('enrollments.created_at', '<=', $req->end_date);
        }
        $student=$student->get();

        $user = DB::table('center_staff_db.tblemployee')
        ->select('id', 'full_name_th')
        ->get();


        return view('admin.reports.student', compact('student','user'));
    }

    public function course_report()
    {
        $courses = Course::
        select('courses.*','cat.category_name')
        ->leftjoin('categories as cat', 'courses.category_id', '=', 'cat.id')
        ->withCount('enrollments')->get();
        
        $stats = [
            'total' => $courses->count(),
            'active' => $courses->where('status', 2)->count(), 
            'pending' => $courses->where('status', 1)->count(),
            'total_students' => $courses->sum('enrollments_count')
        ];

        return view('admin.reports.course_report', compact('courses', 'stats'));
    }

    public function dashboard()
    {
        // --- 1. ข้อมูลภาพรวม (Overview Stats) ---
        $total_students = DB::table('center_staff_db.tblemployee as emp')
        ->select(DB::raw('COUNT(DISTINCT emp.id) as total'))
        ->first()
        ->total;

        // นับจำนวนคนที่กำลังเรียน (In Progress: status 1)
        $learning_count = Enrollment::where('status', 1)->count();
        
        // นับจำนวนคนที่เรียนจบแล้ว (Completed: status 2)
        $completed_count = Enrollment::where('progress_percent', 100)
        ->select(DB::raw('COUNT(DISTINCT CONCAT(user_id, "-", course_id)) as total'))
        ->first()
        ->total;

        $total_courses = Course::where('status', 0)->count();

        // Top Learners: แสดงรายชื่อพนักงานที่เรียนจบมากที่สุด
        $top_learners = DB::table('elearning_db.enrollments as en') // ระบุชื่อ DB ที่นี่
        ->join('center_staff_db.tblemployee as emp', 'en.user_id', '=', 'emp.id') // ระบุชื่อ DB พนักงาน
        ->where('en.progress_percent', 100)
        ->select('emp.full_name_th', DB::raw('count(en.id) as course_count'))
        ->groupBy('emp.id', 'emp.full_name_th')
        ->orderBy('course_count', 'desc')
        ->limit(5)
        ->get();

        // Average Progress: ดูว่าพนักงานในระบบมีค่าเฉลี่ยความคืบหน้ากี่ % (ใช้ progress_percent ที่เราเพิ่งทำ)
        $avg_progress = Enrollment::avg('progress_percent') ?? 0;



        // Monthly Trends: กราฟแสดงจำนวนพนักงานที่ Register เข้ามาใหม่ใน 6 เดือน
        $monthly_data = User::select(
                DB::raw('COUNT(id) as count'),
                DB::raw("DATE_FORMAT(created_at, '%b') as month") // '%b' เพื่อให้ได้ชื่อเดือนย่อ (Jan, Feb)
            )
            ->where('created_at', '>=', Carbon::now()->subMonths(6))
            ->groupBy('month')
            ->orderBy(DB::raw('MIN(created_at)'), 'ASC')
            ->get();


        $course_trends = Enrollment::select(
            'course_id',
            DB::raw('COUNT(DISTINCT user_id) as total_students'), // นับจำนวนพนักงานแบบไม่ซ้ำคน
            DB::raw("DATE_FORMAT(created_at, '%b') as month") // ดึงชื่อเดือนย่อ (Mar, Apr, May)
        )
        ->where('created_at', '>=', Carbon::now()->subMonths(3)) // ย้อนหลัง 3 เดือน
        ->groupBy('course_id', 'month')
        ->orderBy(DB::raw('MIN(created_at)'), 'ASC')
        ->with('course:id,title') // ดึงชื่อคอร์สมาด้วย (Eager Loading)
        ->get();


        // ดึงชื่อเดือนที่ไม่ซ้ำกันสำหรับทำแกน X ของกราฟ (เช่น ['Mar', 'Apr', 'May'])
        $labels = $course_trends->pluck('month')->unique()->values()->all();
        // จัดกรุ๊ปตามชื่อคอร์ส เพื่อสร้าง Dataset ของกราฟแต่ละเส้น/แต่ละแท่ง
        $datasets = $course_trends->groupBy('course_id')->map(function ($items) use ($labels) {
        $courseTitle = $items->first()->course->title ?? 'คอร์สทั่วไป';
            $data = collect($labels)->map(function ($month) use ($items) {
                $found = $items->where('month', $month)->first();
                return $found ? $found->total_students : 0;
            })->all();

            return [
                'label' => $courseTitle,
                'data' => $data,
                'borderWidth' => 2
            ];
        })->values()->all();



        // คะแนนเฉลี่ยของการทำแบบทดสอบทั้งหมดในระบบ
        $avg_quiz_score = DB::table('quiz_attempts')->avg('score') ?? 0;
        $monthlyData = DB::table('enrollments')
            ->select(DB::raw('MONTH(created_at) as month'), DB::raw('COUNT(id) as total'))
            ->whereYear('created_at', Carbon::now()->year)
            ->groupBy(DB::raw('MONTH(created_at)'))
            ->orderBy('month', 'asc')
            ->get();

        // เตรียมข้อมูลให้ครบ 12 เดือน (หากเดือนไหนไม่มีคนเรียนให้เป็น 0)
        $months_labels = ['ม.ค.', 'ก.พ.', 'มี.ค.', 'เม.ย.', 'พ.ค.', 'มิ.ย.', 'ก.ค.', 'ส.ค.', 'ก.ย.', 'ต.ค.', 'พ.ย.', 'ธ.ค.'];
        $students_count = array_fill(0, 12, 0);

        foreach ($monthlyData as $data) {
            $students_count[$data->month - 1] = $data->total;
        }

        return view('admin.dashboard.index', compact(
            'total_students', 'learning_count', 'completed_count', 'total_courses',
            'top_learners', 'labels', 'datasets', 'avg_progress', 'avg_quiz_score', 'months_labels', 'students_count'
        ) + [
            'reportId'    => env('PBI_REPORT_ID'),
            'workspaceId' => env('PBI_WORKSPACE_ID')
        ]);
    }

    public function quiz_report($id)
    {
        $quizzes = DB::table('quizzes as q')
        ->join('quiz_attempts as qa', 'q.id', '=', 'qa.quiz_id')
        ->join('center_staff_db.tblemployee as emp', 'emp.id', '=', 'qa.user_id')
        ->select('q.title', 'qa.score', 'qa.created_at','emp.full_name_th','q.type')
        ->where('q.course_id', $id)
        ->get();
        return view('admin.reports.quiz_report', compact('quizzes'));
    }

}