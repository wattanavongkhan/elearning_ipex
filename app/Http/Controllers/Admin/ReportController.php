<?php namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Course;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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


}
