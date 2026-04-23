<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable; // ต้องสืบทอดจากตัวนี้
use Illuminate\Notifications\Notifiable;

class Role extends Authenticatable
{
    use Notifiable;

    // ระบุ Connection และชื่อตารางให้ชัดเจน
    protected $connection = 'central_staff_db';
    protected $table = 'tblroles';

    // ระบุว่า Column ไหนคือ Primary Key (ถ้าไม่ใช่ id)
    protected $primaryKey = 'role_id'; 

    // รายการ Field ที่อนุญาตให้จัดการข้อมูล
    protected $fillable = [
        'sys_id'
        , 'role_name'
        , 'role_id'
    ];
}