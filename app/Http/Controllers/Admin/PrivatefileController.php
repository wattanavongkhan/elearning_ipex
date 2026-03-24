<?php namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Lesson;
use App\Models\User;
use App\Models\Category;
use App\Models\Enrollment;
use App\Models\Quiz;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class PrivatefileController extends Controller 
{
    public function index() 
    {
        $privatefiles = \App\Models\PrivateFile::select('private_files.*','users.name as user_name')->where('user_id', Auth::id())
        ->leftjoin('users', 'private_files.user_id', '=', 'users.id')
        ->select('private_files.*', 'users.name as user_name')
        ->latest()->paginate(10);
        return view('admin.privatefiles.index', compact('privatefiles'));
    }

    public function create()
    {
        return view('admin.privatefiles.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'file_type' => 'required|in:pdf,excel',
            'file_name' => 'required|file|max:10240', // 10MB max
        ]);

        $file = $request->file('file_name');
        $filename = Str::random(20) . '.' . $file->getClientOriginalExtension();
        $path = $file->storeAs('private_files', $filename, 'public');

        \App\Models\PrivateFile::create([
            'user_id' => Auth::id(),
            'file_type' => $request->file_type,
            'file_name' => $file->getClientOriginalName(),
            'file_path' => $path,
            'size_file' => $file->getSize(),
        ]);

        return redirect()->route('privatefiles.index')->with('success', 'ไฟล์ส่วนตัวถูกเพิ่มเรียบร้อยแล้ว');
    }


    public function destroy($id)
    {
        $file = \App\Models\PrivateFile::findOrFail($id);
        Storage::disk('public')->delete($file->file_path);
        $file->delete();

        return redirect()->route('privatefiles.index')->with('success', 'ไฟล์ส่วนตัวถูกลบเรียบร้อยแล้ว');
    }
}

