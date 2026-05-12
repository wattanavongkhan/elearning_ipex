<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lesson extends Model
{
    use HasFactory;
    protected $fillable = [
        'course_id', 'title', 'slug', 'content', 'video_url','video_path',
        'position', 'is_free', 'user_id', 'pre_quiz_id', 'post_quiz_id','status'
    ];

    public function course() {
        return $this->belongsTo(Course::class);
    }

    // เพิ่มความสัมพันธ์กับ Quiz (ถ้ามี Model Quiz)
    public function pre_quiz() {
        return $this->belongsTo(Quiz::class, 'pre_quiz_id');
    }

    public function post_quiz() {
        return $this->belongsTo(Quiz::class, 'post_quiz_id');
    }
}
