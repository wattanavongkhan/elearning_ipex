<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Enrollment extends Model
{
    use HasFactory;
    public $table = "enrollments"; // กำหนดชื่อ table ให้ตรงกับฐานข้อมูล (ถ้าไม่ใส่ Laravel จะใช้ชื่อแบบ plural คือ categories)

    protected $fillable = ['user_id', 'course_id', 'status','amount','payment_method'];
    public function user() {
        return $this->belongsTo(User::class);
    }
    public function course() {
        return $this->belongsTo(Course::class);
    }
}
