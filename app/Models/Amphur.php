<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class Amphur extends Model
{
        protected $table = 'amphur'; // ชื่อตารางในฐานข้อมูล
    protected $fillable = [
        'AMPHUR_ID','AMPHUR_CODE','AMPHUR_NAME','GEO_ID','PROVINCE_ID'];
}
