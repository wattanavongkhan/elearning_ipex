<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Activity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ActivityController extends Controller
{
    // แสดงรายการกิจกรรมทั้งหมด
    public function index(Request $request)
    {
        $activities = Activity::latest()->paginate(10);
        return view('admin.activities.index', compact('activities'));
    }

    // หน้าฟอร์มสร้างกิจกรรม
    public function create()
    {
        return view('admin.activities.create');
    }

    public function show($slug)
    {
        // ค้นหากิจกรรมจาก slug และต้องมีสถานะเปิดใช้งาน (status = 1)
        $activity = Activity::where('slug', $slug)
                            ->where('status', 1)
                            ->firstOrFail(); // ถ้าไม่เจอจะส่งหน้า 404 อัตโนมัติ

        // (Optional) เพิ่มยอดเข้าชมทุกครั้งที่มีคนเปิดอ่าน
        $activity->increment('view_count');

        return view('home.activities.show', compact('activity'));
    }


    // บันทึกข้อมูลกิจกรรมใหม่
    public function store(Request $request)
    {

        $data = $request->all();

        // จัดการอัปโหลดรูปภาพ
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('activities', 'public');
            $data['thumbnail'] = $path;
        }

        // สร้าง Slug อัตโนมัติจากชื่อ (ถ้าจำเป็น)
        $data['slug'] = Str::slug($request->title);
        $data['status'] = $request->has('status') ? 1 : 0;

        Activity::create($data);

        return redirect()->route('admin.activities.index')
                         ->with('success', 'สร้างกิจกรรมเรียบร้อยแล้ว');
    }

    // หน้าฟอร์มแก้ไข
    public function edit(Activity $activity)
    {
        return view('admin.activities.edit', compact('activity'));
    }


    // อัปเดตข้อมูล
    public function update(Request $request, Activity $activity)
    {
        $data = $request->all();

        if ($request->hasFile('image')) {
            // ลบรูปเก่าออกก่อนเพื่อประหยัดพื้นที่ Server
            if ($activity->image) {
                Storage::disk('public')->delete($activity->image);
            }
            $path = $request->file('image')->store('activities', 'public');
            $data['thumbnail'] = $path;
        }

        $data['status'] = $request->has('status') ? 1 : 0;
        if($activity->slug == null){
            $data['slug'] = Str::slug($request->title,'-',true);
        }

        $activity->update($data);

        return redirect()->route('admin.activities.index')
                         ->with('success', 'อัปเดตข้อมูลกิจกรรมเรียบร้อยแล้ว');
    }

    // ลบกิจกรรม
    public function destroy(Activity $activity)
    {
        if ($activity->image) {
            Storage::disk('public')->delete($activity->image);
        }

        $activity->delete();
        return back()->with('success', 'ลบกิจกรรมเรียบร้อยแล้ว');
    }
}
