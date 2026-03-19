<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Lesson;
use App\Models\User;
use App\Models\Province;
use App\Models\District;
use App\Models\Subdistrict;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\Quiz;
use App\Models\Question;
use Illuminate\Support\Facades\Storage;

class InstructorController extends Controller {

    public function index() 
    {
        $user=User::where('status', '1')->get();
      
        return view('admin.instructor.lecturer', compact('user'));
    }

    // 2. หน้าฟอร์มเพิ่มบทเรียน
    public function create(User $user) 
    {
        $users=User::where('status', '1')->get();
        $privance=Province::select('code','name_in_thai')->get();
        return view('admin.instructor.create', compact('user', 'users', 'privance'));
    }

    public function store(Request $request, User $user) {
        
        $validated=$request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'province_code' => 'required|string',
            'district_code' => 'required|string',
            'subdistrict_code' => 'required|string',
            ]);

        // สร้างคำถามโดยผูกกับ quiz_id (ID 12)
        $user->questions()->create($validated);

        return redirect()->route('admin.instructor.index') ->with('success', 'เพิ่มคำถามเรียบร้อยแล้ว');
    }

    public function getDistricts($provinceCode) {
        $districts = District::where('province_id', $provinceCode)->get();
        return response()->json($districts);
    }

    public function getSubdistricts($districtCode) {
        $subdistricts = Subdistrict::where('district_id', $districtCode)->get();
        return response()->json($subdistricts);
    }

    public function getZipcode($subdistrictCode) {
        $subdistrict = Subdistrict::where('code', $subdistrictCode)->first();

        return response()->json(['zip_code' => $subdistrict ? $subdistrict->zip_code : null]);
    }

}
