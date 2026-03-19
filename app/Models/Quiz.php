<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Quiz extends Model
{
    protected $fillable = ['course_id', 'title', 'type', 'user_id'];

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    /**
     * เปลี่ยนจาก question() เป็น questions() 
     */
    public function questions() // <--- เติม s ตรงนี้ครับ
    {
        return $this->hasMany(Question::class);
    }
}