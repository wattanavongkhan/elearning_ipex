<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class Guest extends Model
{
    use HasFactory;
    protected $table = 'guest'; // ชื่อตารางในฐานข้อมูล
    protected $fillable = ['id','members','start_date','end_date','category_color','hotel_name',
    'request_booking','user_id','status'];
}
