<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    use HasFactory;

    /**
     * กำหนดฟิลด์ที่อนุญาตให้บันทึกข้อมูลแบบ Array หรือ Mass Assignment
     * (ถ้าไม่ใส่ส่วนนี้ เวลาสั่ง Course::create() จะเกิด Error)
     */
    protected $fillable = [
        'course_code',
        'category_id',
        'title',
        'description',
        'benefits',
        'target_audience',
        'slug',
        'price',
        'thumbnail',
        'user_id',
    ];

    /**
     * สร้างความสัมพันธ์: คอร์สนี้เป็นของ User (ผู้สอน) คนไหน
     * (One-to-Many Inverse)
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * (เพิ่มเติม) สร้างความสัมพันธ์กับบทเรียน
     * หนึ่งคอร์สสามารถมีได้หลายบทเรียน
     */
    public function lessons()
    {
        return $this->hasMany(Lesson::class);
    }

    public function quizzes()
    {
        return $this->hasMany(Quiz::class);
    }
}