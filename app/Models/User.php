<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password','img','status','password_member','status_activate',
        'address','province','amphur','district','zipcode','phone','backgroud','online_time',
        'last_session'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'name', 'email', 'password','img','status','password_member','status_activate',
        'address','province','amphur','district','zipcode','phone','backgroud','online_time',
        'last_session'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function courses()
    {
        // สมมติว่าคุณใช้ตารางกลางชื่อ course_user หรือใช้ตารางที่มีอยู่แล้ว
        // หากคุณใช้ตารางอื่น เช่น enrolments ให้เปลี่ยนชื่อตารางใน parameter ที่สอง
        return $this->belongsToMany(Course::class, 'course_user', 'user_id', 'course_id')
                    ->withTimestamps();
    }
    
    // หากคุณต้องการดึงประวัติการทำข้อสอบจากหน้าจัดการนักเรียน
    public function quiz_attempts()
    {
        return $this->hasMany(QuizAttempt::class);
    }
    
}
