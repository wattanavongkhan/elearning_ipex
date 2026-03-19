<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class District extends Model
{
    use HasFactory;
    protected $table = 'district'; // ชื่อตารางในฐานข้อมูล
    protected $fillable = ['DISTRICT_ID','DISTRICT_CODE','DISTRICT_NAME','AMPHUR_ID','PROVINCE_ID'];
}
