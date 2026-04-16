<?php namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
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
        $student = User::select('c.*', 'users.name as student_name', 'users.email as student_email','te.status')
        ->join('enrollments as te', 'users.id', '=', 'te.user_id')
        ->join('courses as c', 'te.course_id', '=', 'c.id');

        if ($req->course_id !=null) 
        {
            $student = $student->where('c.id', $req->course_id);
        }

        if ($req->student_id!=null) 
        {
            $student = $student->where('users.id', $req->student_id);
        }

        if ($req->start_date!=null) {
            $student = $student->where('te.created_at', '>=', $req->start_date)
            ->where('te.created_at', '<=', $req->end_date);
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
        // --- 1. ข้อมูลเดิมสำหรับการทำ Dashboard ---
        $total_students = User::count();
        $learning_count = User::where('status', 1)->count();
        $completed_count = User::where('status', '!=', 1)->count();
        $total_courses = Course::count();

        $top_learners = DB::table('users')
            ->join('enrollments', 'users.id', '=', 'enrollments.user_id')
            ->select('users.name', DB::raw('count(enrollments.id) as course_count'))
            ->groupBy('users.id', 'users.name')
            ->orderBy('course_count', 'desc')
            ->limit(5)
            ->get();

        $monthly_data = User::select(
                DB::raw('COUNT(id) as count'),
                DB::raw("DATE_FORMAT(created_at, '%M') as month")
            )
            ->where('created_at', '>=', Carbon::now()->subMonths(6))
            ->groupBy('month')
            ->orderBy(DB::raw('MIN(created_at)'), 'ASC')
            ->get();

        $labels = $monthly_data->pluck('month');
        $data = $monthly_data->pluck('count');

        // --- 2. (ใหม่) ส่วนการดึง Power BI Embed Token ---
        $embedToken = null;
        try {
            // ก. ขอ Access Token จาก Azure
            $tokenResponse = Http::asForm()->post("https://login.microsoftonline.com/" . env('PBI_TENANT_ID') . "/oauth2/v2.0/token", [
                'client_id'     => env('PBI_CLIENT_ID'),
                'scope'         => 'https://analysis.windows.net/powerbi/api/.default',
                'client_secret' => env('PBI_CLIENT_SECRET'),
                'grant_type'    => 'client_credentials',
            ]);

            if ($tokenResponse->successful()) {
                $accessToken = $tokenResponse->json()['access_token'];

                // ข. ขอ Embed Token สำหรับ Report
                $embedResponse = Http::withToken($accessToken)
                    ->post("https://api.powerbi.com/v1.0/myorg/groups/" . env('PBI_WORKSPACE_ID') . "/reports/" . env('PBI_REPORT_ID') . "/GenerateToken", [
                        'accessLevel' => 'View'
                    ]);

                if ($embedResponse->successful()) {
                    $embedToken = $embedResponse->json()['token'];
                }
            }
        } catch (\Exception $e) {
            // หากเกิด Error ให้เก็บค่าเป็น null หรือจัดการตามเหมาะสม
            $embedToken = null;
        }
        // --- 3. ส่งข้อมูลทั้งหมดไปยัง View ---
        return view('admin.dashboard.index', [
            'total_students'  => $total_students,
            'learning_count'  => $learning_count,
            'completed_count' => $completed_count,
            'total_courses'   => $total_courses,
            'top_learners'    => $top_learners,
            'labels'          => $labels,
            'data'            => $data,
            'embedToken'      => $embedToken, // ส่งตัวแปรนี้ไปที่ JS ในหน้า Blade
            'reportId'        => env('PBI_REPORT_ID'),
            'workspaceId'     => env('PBI_WORKSPACE_ID')
        ]);
    }




    public function getPowerBIToken()
    {
    $tokenResponse = Http::withOptions(['verify' => false]) // ใส่ verify false กรณีรันใน XAMPP แล้วติด SSL
        ->asForm()->post("https://login.microsoftonline.com/" . env('PBI_TENANT_ID') . "/oauth2/v2.0/token", [
            'client_id'     => env('PBI_CLIENT_ID'),
            'scope'         => 'https://analysis.windows.net/powerbi/api/.default',
            'client_secret' => env('PBI_CLIENT_SECRET'),
            'grant_type'    => 'client_credentials',
        ]);

    // --- จุดเช็คที่ 1: ถ้าหน้าจอค้างตรงนี้ แสดงว่า Client ID หรือ Secret ผิด ---
    if ($tokenResponse->failed()) {
        dd("1. Azure Auth Failed", $tokenResponse->json(), "Check your .env Client ID/Secret");
    }

    $accessToken = $tokenResponse->json()['access_token'];

    $embedResponse = Http::withToken($accessToken)
        ->post("https://api.powerbi.com/v1.0/myorg/groups/" . env('PBI_WORKSPACE_ID') . "/reports/" . env('PBI_REPORT_ID') . "/GenerateToken", [
            'accessLevel' => 'View'
        ]);

    // --- จุดเช็คที่ 2: ถ้าหน้าจอค้างตรงนี้ แสดงว่าลืมแอด App เข้า Workspace ---
    if ($embedResponse->failed()) {
        dd("2. Power BI API Failed", $embedResponse->json(), "Did you add the App to Workspace Members?");
    }

    $embedToken = $embedResponse->json()['token'];
    }
}