<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable; // ต้องสืบทอดจากตัวนี้
use Illuminate\Notifications\Notifiable;

class Employee extends Authenticatable
{
    use Notifiable;

    // ระบุ Connection และชื่อตารางให้ชัดเจน
    protected $connection = 'central_staff_db';
    protected $table = 'tblemployee';

    // ระบุว่า Column ไหนคือ Primary Key (ถ้าไม่ใช่ id)
    protected $primaryKey = 'id'; 

    // รายการ Field ที่อนุญาตให้จัดการข้อมูล
    protected $fillable = [
        'user_login'
        , 'password'
        , 'full_name_eng'
        , 'em_code'
        , 'full_name_th'
        , 'start_date'
        , 'section_id'
        , 'position_id'
        , 'status',

    ];

    // ปิดบังฟิลด์รหัสผ่าน
    protected $hidden = [
        'password', 'remember_token',
    ];
}