<?php namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Enrollment;
use App\Models\Course;
use Illuminate\Http\Request;
use App\Models\Employee;
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
            'emp.em_code', 
            'qa.score'
        )
        ->join('central_staff_db.tblemployee as emp', 'enrollments.user_id', '=', 'emp.id')
        // สมมติว่า Join section ผ่านตาราง employee
        ->join('central_staff_db.tblsection as sec', 'emp.section_id', '=', 'sec.id') 
        ->leftjoin('quizzes as q', function ($join) {
            $join->on('enrollments.course_id', '=', 'q.course_id')
                 ->where('q.type', 'post-test')
                //  ->orwhere('q.type', 'pre-test')
                 ;
        })
        ->leftjoin('quiz_attempts as qa', function ($join) {
            $join->on('q.id', '=', 'qa.quiz_id');
        })
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

        return view('admin.reports.student', compact('student'));
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
        $total_students = User::count();
        
        // นับจำนวนคนที่กำลังเรียน (In Progress: status 1)
        $learning_count = Enrollment::where('status', 1)->count();
        
        // นับจำนวนคนที่เรียนจบแล้ว (Completed: status 2)
        $completed_count = Enrollment::where('status', 2)->count();
        
        $total_courses = Course::count();

        // --- 2. การวิเคราะห์ข้อมูล (Analytical Data) ---

        // Top Learners: แสดงรายชื่อพนักงานที่เรียนจบมากที่สุด
        $top_learners = DB::table('elearning_db.enrollments as en') // ระบุชื่อ DB ที่นี่
        ->join('central_staff_db.tblemployee as emp', 'en.user_id', '=', 'emp.id') // ระบุชื่อ DB พนักงาน
        ->where('en.status', 2)
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

        $labels = $monthly_data->pluck('month');
        $data = $monthly_data->pluck('count');

        // --- 3. ข้อมูล Quiz Results (Optional แต่อยากให้มี) ---
        // คะแนนเฉลี่ยของการทำแบบทดสอบทั้งหมดในระบบ
        $avg_quiz_score = DB::table('quiz_attempts')->avg('score') ?? 0;

        return view('admin.dashboard.index', compact(
            'total_students', 'learning_count', 'completed_count', 'total_courses',
            'top_learners', 'labels', 'data', 'avg_progress', 'avg_quiz_score'
        ) + [
            'reportId'    => env('PBI_REPORT_ID'),
            'workspaceId' => env('PBI_WORKSPACE_ID')
        ]);
    }

    public function quiz_report($id)
    {
        $quizzes = DB::table('quizzes as q')
        ->join('quiz_attempts as qa', 'q.id', '=', 'qa.quiz_id')
        ->join('central_staff_db.tblemployee as emp', 'emp.id', '=', 'qa.user_id')
        ->select('q.title', 'qa.score', 'qa.created_at','emp.full_name_th','q.type')
        ->where('q.course_id', $id)
        ->get();
        return view('admin.reports.quiz_report', compact('quizzes'));
    }


    // public function getPowerBIToken()
    // {
    // $tokenResponse = Http::withOptions(['verify' => false]) // ใส่ verify false กรณีรันใน XAMPP แล้วติด SSL
    //     ->asForm()->post("https://login.microsoftonline.com/" . env('PBI_TENANT_ID') . "/oauth2/v2.0/token", [
    //         'client_id'     => env('PBI_CLIENT_ID'),
    //         'scope'         => 'https://analysis.windows.net/powerbi/api/.default',
    //         'client_secret' => env('PBI_CLIENT_SECRET'),
    //         'grant_type'    => 'client_credentials',
    //     ]);

    // // --- จุดเช็คที่ 1: ถ้าหน้าจอค้างตรงนี้ แสดงว่า Client ID หรือ Secret ผิด ---
    // if ($tokenResponse->failed()) {
    //     dd("1. Azure Auth Failed", $tokenResponse->json(), "Check your .env Client ID/Secret");
    // }

    // $accessToken = $tokenResponse->json()['access_token'];

    // $embedResponse = Http::withToken($accessToken)
    //     ->post("https://api.powerbi.com/v1.0/myorg/groups/" . env('PBI_WORKSPACE_ID') . "/reports/" . env('PBI_REPORT_ID') . "/GenerateToken", [
    //         'accessLevel' => 'View'
    //     ]);

    // // --- จุดเช็คที่ 2: ถ้าหน้าจอค้างตรงนี้ แสดงว่าลืมแอด App เข้า Workspace ---
    // if ($embedResponse->failed()) {
    //     dd("2. Power BI API Failed", $embedResponse->json(), "Did you add the App to Workspace Members?");
    // }

    // $embedToken = $embedResponse->json()['token'];
    // }
}