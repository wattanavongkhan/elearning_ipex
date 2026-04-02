<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class Patal_detail extends Model
{
    use HasFactory;
    protected $table = 'patal_detail'; // ชื่อตารางในฐานข้อมูล
    protected $fillable = ['id','title','url','patal_id','seq_no','status'];
}
