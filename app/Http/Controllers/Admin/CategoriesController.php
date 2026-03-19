<?php namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Lesson;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\Quiz;
use App\Models\Question;
use App\Models\Category;
use Illuminate\Support\Facades\Storage;

class CategoriesController extends Controller {

    // 1. หน้าแสดงรายการบทเรียนทั้งหมดของคอร์สนั้นๆ
    public function index(Category $category) {
        $categories=Category::all();
        return view('admin.categories.index', compact('category', 'categories'));
    }

    // 2. หน้าแบบฟอร์มสร้างบทเรียนใหม่
    public function create() {
        $categories=Category::all();
        return view('admin.categories.create', compact('categories'));
    }

    public function store(Request $request) {
        // 1. Validate ข้อมูล
        $request->validate([
            'category_name' => 'required|string|max:255',
            'thumbnail' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048', // เช็คว่าเป็นรูปภาพ
        ]);

        $thumbnailPath = null;

        // 2. จัดการอัปโหลดไฟล์
        if ($request->hasFile('thumbnail')) {
            // เก็บไฟล์ไว้ใน folder 'categories' ใน disk 'public'
            // Laravel จะสุ่มชื่อไฟล์ให้เพื่อป้องกันชื่อซ้ำ
            $thumbnailPath = $request->file('thumbnail')->store('categories', 'public');
        }

        // 3. บันทึกลงฐานข้อมูล (ส่งเฉพาะฟิลด์ที่ต้องการ)
        Category::create([
            'category_name' => $request->category_name,
            'description'   => $request->description,
            'thumbnail'     => $thumbnailPath, // เก็บ path เช่น 'categories/abc.jpg'
        ]);

        return redirect()->route('categories.index')->with('success', 'เพิ่มหมวดหมู่เรียบร้อยแล้ว');
    }

    public function edit(Category $category) {
        $categories=Category::all();
        return view('admin.categories.edit', compact('category', 'categories'));
    }

    public function update(Request $request, Category $category) {
        // 1. Validate ข้อมูล
        $request->validate([
            'category_name' => 'required|string|max:255',
            'thumbnail' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048', // เช็คว่าเป็นรูปภาพ
        ]);

        $data = [
            'category_name' => $request->category_name,
            'description'   => $request->description,
        ];

        // 2. จัดการอัปโหลดไฟล์ใหม่ (ถ้ามี)
        if ($request->hasFile('thumbnail')) {
            // ลบไฟล์เก่าออกจากดิสก์ (ถ้ามี)
            if ($category->thumbnail) {
                Storage::disk('public')->delete($category->thumbnail);
            }

            // อัปโหลดไฟล์ใหม่และเก็บ path
            $data['thumbnail'] = $request->file('thumbnail')->store('categories', 'public');
        }

        // 3. อัปเดตข้อมูลในฐานข้อมูล
        $category->update($data);

        return redirect()->route('categories.index')->with('success', 'อัปเดตหมวดหมู่เรียบร้อยแล้ว');
    }

    public function destroy(Category $category) {
        try {
            // 1. ลบไฟล์รูปภาพจากดิสก์ (ถ้ามี)
            if ($category->thumbnail) {
                Storage::disk('public')->delete($category->thumbnail);
            }

            // 2. ลบข้อมูลจากฐานข้อมูล
            $category->delete();

            return redirect()->route('categories.index')->with('success', 'ลบหมวดหมู่และไฟล์ที่เกี่ยวข้องเรียบร้อยแล้ว');

        } catch (\Exception $e) {
            return back()->with('error', 'ไม่สามารถลบหมวดหมู่นี้ได้: ' . $e->getMessage());
        }
    }


}
