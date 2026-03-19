<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Province extends Model
{
    use HasFactory;
     protected $table = 'province'; // ชื่อตารางในฐานข้อมูล
    protected $fillable = [
        'PROVINCE_ID ','PROVINCE_CODE','PROVINCE_NAME','GEO_ID'];
}
