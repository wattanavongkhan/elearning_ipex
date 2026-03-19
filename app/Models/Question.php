<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    public $table = "question"; // กำหนดชื่อ table ให้ตรงกับฐานข้อมูล (ถ้าไม่ใส่ Laravel จะใช้ชื่อแบบ plural คือ categories)

    // กำหนดฟิลด์ที่อนุญาตให้บันทึกข้อมูลแบบ Mass Assignment
    protected $fillable = [
        'quiz_id', 
        'question_text', 
        'options', // ตัวเลือก A, B, C, D (แนะนำให้เก็บเป็น JSON)
        'correct_answer',
        'user_id', // เพิ่มฟิลด์ user_id เพื่อเก็บข้อมูลผู้สร้างคำถาม
    ];

    // บอก Laravel ว่าคอลัมน์ options เป็น JSON/Array อัตโนมัติ
    protected $casts = [
        'options' => 'array',
    ];

    public function quiz()
    {
        return $this->belongsTo(Quiz::class);
    }
}