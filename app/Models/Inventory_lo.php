<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable; // ต้องสืบทอดจากตัวนี้
use Illuminate\Notifications\Notifiable;

class Inventory_lo extends Authenticatable
{
    use Notifiable;

    // ระบุ Connection และชื่อตารางให้ชัดเจน
    protected $connection = 'dashboard_bi_db';
    protected $table = 'tblinventory_lo';

    // 2. ระบุ Primary Key (ปกติ Laravel จะมองหา 'id' อยู่แล้ว)
    protected $primaryKey = 'id';

    // 3. ปิดการรัน timestamps อัตโนมัติของ Laravel 
    // เพราะเราใช้ CURRENT_TIMESTAMP ในฐานข้อมูลควบคุมเอง (created_at/updated_at)
    public $timestamps = false;
    // 4. รายชื่อคอลัมน์ที่อนุญาตให้บันทึกข้อมูล (Mass Assignment)
    protected $fillable = [
        'record_month',
        'customer_name',
        'part_name',
        'part_no',
        'current_status',
        'price',
        'unit',
        'stock_quantity',
        'amount_thb',
        'part_type',
        'supplier_name'
    ];

    // 5. (เพิ่มเติม) กำหนดการแปลงประเภทข้อมูลให้ถูกต้องเวลาเรียกใช้
    protected $casts = [
        'record_month' => 'date',
        'price' => 'decimal:2',
        'amount_thb' => 'decimal:2',
        'stock_quantity' => 'integer',
    ];
}