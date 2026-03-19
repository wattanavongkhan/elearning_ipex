<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lesson_user extends Model
{
    use HasFactory;
    public $table = "lesson_user"; // กำหนดชื่อ table ให้ตรงกับฐานข้อมูล (ถ้าไม่ใส่ Laravel จะใช้ชื่อแบบ plural คือ categories)

    protected $fillable = ['lesson_id', 'user_id', 'course_id','is_completed'];

     public function lesson() {
        return $this->belongsTo(Lesson::class, 'lesson_id');
    }

    public function courses() {
        return $this->hasMany(Course::class, 'category_id');
    }
}
