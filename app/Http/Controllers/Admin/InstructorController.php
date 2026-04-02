<?php

namespace App\Http\Controllers\Admin;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\User;
use App\Models\Patal;
use App\Models\Patal_detail;

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

    public function patals()
    {
        $patals=Patal::orderby('seq_no','asc')->latest()->get();

        return view('admin.patal.index', compact('patals'));
    }

    public function patals_store(Request $request)
    {
        if($request->id==null)
        {
            $iconPath = null;
            if ($request->hasFile('icon')) {
                $iconPath = $request->file('icon')->store('icons', 'public');
            }

            Patal::create([
                'title'  => $request->title,
                'url'    => $request->url,
                'icon'   => $iconPath, // เก็บ Path ของไฟล์ (เช่น icons/filename.png)
                'status' => $request->status ?? 1,
                'seq_no'=>$request->seq_no
            ]);
            return redirect()->back()->with('success', 'บันทึกทางลัดและอัปโหลดไอคอนเรียบร้อยแล้ว');
        }else
        {
            // 1. หาข้อมูลเดิม
            $patal = Patal::findOrFail($request->id);
            $iconPath = $patal->icon; // ใช้รูปเดิมไว้ก่อน

            if ($request->hasFile('icon')) {
                if ($patal->icon && Storage::disk('public')->exists($patal->icon)) {
                    Storage::disk('public')->delete($patal->icon);
                }
                $iconPath = $request->file('icon')->store('icons', 'public');
            }

            $patal->update([
                'title'  => $request->title,
                'url'    => $request->url,
                'icon'   => $iconPath,
                'status' => $request->status,
                'seq_no'=>$request->seq_no
            ]);

            return redirect()->back()->with('success', 'อัปเดตข้อมูลเรียบร้อยแล้ว');
        }
    }

    public function patals_destroy($id)
    {
        try {
            // 1. ค้นหาข้อมูลจาก ID
            $patal = Patal::findOrFail($id);

            // 2. ตรวจสอบว่ามีไฟล์ Icon หรือไม่ ถ้ามีให้ลบออกจาก Disk
            if ($patal->icon) {
                if (Storage::disk('public')->exists($patal->icon)) {
                    Storage::disk('public')->delete($patal->icon);
                }
            }

            // 3. ลบข้อมูลจาก Database
            $patal->delete();

            return redirect()->back()->with('success', 'ลบรายการและไฟล์ไอคอนเรียบร้อยแล้ว');

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'ไม่สามารถลบข้อมูลได้: ' . $e->getMessage());
        }
    }


    public function patals_show($id)
    {
        return response()->json(Patal::findOrFail($id));
    }

    public function patals_detail($id)
    {
        $title=Patal::select('title')->where('id',$id)->first();

        $patals=Patal_detail::where('patal_id',$id)->get();

        return view('admin.patal.patal_detail', compact('patals','title','id'));
    }

    public function patals_detail_store(Request $request)
    {
        if($request->id==null)
        {
            $iconPath = null;
           
            Patal_detail::create([
                'patal_id'  => $request->patal_id,
                'title'  => $request->title,
                'url'    => $request->url,
                'seq_no'=>$request->seq_no,
                'status'=>$request->status ?? 1,
            ]);
            return redirect()->back()->with('success', 'บันทึกทางลัดและอัปโหลดไอคอนเรียบร้อยแล้ว');
        }else
        {
            $patal_detail = Patal_detail::findOrFail($request->id);

            $patal_detail->update([
                'title'  => $request->title,
                'url'    => $request->url,
                'seq_no'=>$request->seq_no,
                'status'=>$request->status ?? 1,
                ]);

            return redirect()->back()->with('success', 'อัปเดตข้อมูลเรียบร้อยแล้ว');
        }
    }

    public function patals_detail_destroy(Request $req)
    {
        dd($req);
        try {
            // 1. ค้นหาข้อมูลจาก ID
            $patal_detail = Patal_detail::findOrFail($id);

            // 2. (เพิ่มเติม) ลบไฟล์ไอคอนออกจาก Storage ถ้ามีการเก็บไฟล์ไว้
            // สมมติว่าคอลัมน์ชื่อ 'icon' เก็บพาธไฟล์เอาไว้
            if ($patal_detail->icon && \Storage::exists('public/' . $patal_detail->icon)) {
                \Storage::delete('public/' . $patal_detail->icon);
            }

            // 3. ลบข้อมูลจาก Database (แก้ไขชื่อตัวแปรให้ตรงกัน)
            $patal_detail->delete();

            return redirect()->back()->with('success', 'ลบรายการและไฟล์ไอคอนเรียบร้อยแล้ว');

        } catch (\Exception $e) {
            // บันทึก Log ไว้ดูย้อนหลังได้ถ้าเกิด Error รุนแรง
            \Log::error("Error deleting patal_detail ID {$id}: " . $e->getMessage());
            
            return redirect()->back()->with('error', 'ไม่สามารถลบข้อมูลได้: ' . $e->getMessage());
        }
    }

    public function patals_detail_show($id)
    {
        return response()->json(Patal_detail::where('patal_id',$id)->where('status',1)->get());
    }



}
