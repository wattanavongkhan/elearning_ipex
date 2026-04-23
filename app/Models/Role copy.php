<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable; // ต้องสืบทอดจากตัวนี้
use Illuminate\Notifications\Notifiable;

class Permissions extends Authenticatable
{
    use Notifiable;

    // ระบุ Connection และชื่อตารางให้ชัดเจน
    protected $connection = 'central_staff_db';
    protected $table = 'tbluser_permissions';

    // ระบุว่า Column ไหนคือ Primary Key (ถ้าไม่ใช่ id)
    protected $primaryKey = 'perm_id'; 

    // รายการ Field ที่อนุญาตให้จัดการข้อมูล
    protected $fillable = [
        'emp_id'
        , 'sys_id'
        , 'role_id'
    ];
}