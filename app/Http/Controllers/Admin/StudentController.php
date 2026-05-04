<?php namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Lesson;
use App\Models\User;
use App\Models\Category;
use App\Models\Employee;
use App\Models\Quiz;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use DB;

class StudentController extends Controller {

    // app/Http/Controllers/Admin/StudentController.php
    public function index() {
        $students = Employee::
            leftJoin('central_staff_db.tbluser_permissions as tp', function($join) {
                $join->on('tblemployee.id', '=', 'tp.emp_id')         // เงื่อนไขที่ 2: ต้องมีสถานะ Active
                    ->where('tp.sys_id', '=', 2); // เงื่อนไขที่ 3: ระบุ ID ของระบบ E-learning
            })
            ->leftjoin('central_staff_db.tblroles as trole', 'tp.role_id', '=', 'trole.role_id')

            ->leftjoin('central_staff_db.tblposition as tpos', 'tblemployee.position_id', '=', 'tpos.id')
            ->select('tblemployee.*', 'tpos.position', 'trole.role_name')
            ->orderby('tp.role_id', 'desc')
            ->get();

        return view('admin.students.index', compact('students'));
    }

    public function show($id) {
        $student = User::findOrFail($id);
        
        // ดึงบทเรียนที่เรียนจบแล้ว
        $completedLessons = \App\Models\LessonUser::where('user_id', $id)->get();
        
        // ดึงประวัติการสอบ
        $quizAttempts = \App\Models\QuizAttempt::where('user_id', $id)
            ->with('quiz')
            ->latest()
            ->get();

        return view('admin.students.show', compact('student', 'completedLessons', 'quizAttempts'));
    }

    public function destroy($id)
    {
        $student = User::findOrFail($id);
        $student->delete();
        return redirect()->back()->with('success', 'ลบข้อมูลนักเรียนเรียบร้อยแล้ว');
    }


    public function edit($id)
    {
        $provinces = \App\Models\Province::all();   
        $student = User::findOrFail($id);
        return view('admin.students.edit', compact('student', 'provinces'));
    }

    public function update(Request $request, $id)
    {
        $student = User::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $id,
            'password' => 'nullable|min:8|confirmed', // ใส่รหัสผ่านเฉพาะตอนต้องการเปลี่ยน
        ]);

        $student->name = $request->name;
        $student->email = $request->email;
        
        if ($request->filled('password')) {
            $student->password = bcrypt($request->password);
        }

        $student->save();

        return redirect()->route('admin.students.index')->with('success', 'อัปเดตข้อมูลนักเรียนเรียบร้อยแล้ว');
    }

    public function create()
    {
        $provinces = \App\Models\Province::all();
        return view('admin.students.create', compact('provinces'));
    }

    public function store(Request $request)
    {
        if ($request->id ==null) {
            $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . ($request->id ?? 'NULL'),
            'password' => ($request->id ? 'nullable|min:8|confirmed' : 'required|min:8|confirmed'),
            ]);

            User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => bcrypt($request->password),
                'password_member' => $request->password,
                'address' => $request->address,
                'phone' => $request->phone,
                'province' => $request->province,
                'amphur' => $request->amphur,
                'district' => $request->district,
                'zipcode' => $request->zipcode,
                'status' => $request->status,
                'status_activate' => $request->status_activate,
            ]);
        } else {
            $student = User::findOrFail($request->id);
            $student->name = $request->name;
            $student->email = $request->email;
            $student->address = $request->address;
            $student->phone = $request->phone;
            $student->province = $request->province;
            $student->amphur = $request->amphurs;
            $student->district = $request->district;
            $student->zipcode = $request->zipcode;
            $student->status = $request->status;
            $student->status_activate = $request->status_activate;
            if ($request->filled('password')) {
                $student->password = bcrypt($request->password);
                $student->password_member = $request->password;
            }
            $student->save();
        }

        return redirect()->route('students.index')->with('success', $request->id ? 'อัปเดตข้อมูลเรียบร้อยแล้ว' : 'เพิ่มข้อมูลใหม่เรียบร้อยแล้ว');
    }

    public function getAmphurs($provinceCode)
    {
        $amphurs = \App\Models\Amphur::where('PROVINCE_ID', $provinceCode)->get();
        return response()->json($amphurs);
    }

    public function getDistricts($amphurCode)
    {
        $districts = \App\Models\District::where('AMPHUR_ID', $amphurCode)->get();
        return response()->json($districts);
    }

    public function getStudent($id)
    {
        // ดึงข้อมูลพนักงานพร้อมสิทธิ์ (ถ้ามี)
        $student = DB::table('central_staff_db.tblemployee as emp')
            // ->leftJoin('central_staff_db.tbluser_permissions as tp', 'emp.id', '=', 'tp.emp_id')
            ->leftJoin('central_staff_db.tbluser_permissions as tp', function($join) {
                $join->on('emp.id', '=', 'tp.emp_id')         // เงื่อนไขที่ 2: ต้องมีสถานะ Active
                    ->where('tp.sys_id', '=', 2); // เงื่อนไขที่ 3: ระบุ ID ของระบบ E-learning
            })
            ->leftjoin('central_staff_db.tblroles as trole', 'tp.role_id', '=', 'trole.role_id')
            ->select('emp.id', 'emp.em_code', 'emp.full_name_eng', 'trole.role_id', 'trole.role_name')
            ->where('emp.id', $id)
            ->first();

        return response()->json($student);
    }

    public function updateRole(Request $request)
    {
        $request->validate([
            'emp_id' => 'required',
            'role_name' => 'required'
        ]);

        DB::table('central_staff_db.tbluser_permissions')->updateOrInsert(
            ['emp_id' => $request->emp_id, 'sys_id' => 2],
            [
                'role_id' => $request->role_name,
                'sys_id'  => 1
            ]
        );

        return redirect()->back()->with('success', 'ปรับปรุงสิทธิ์การใช้งานเรียบร้อยแล้ว');
    }

    
}
