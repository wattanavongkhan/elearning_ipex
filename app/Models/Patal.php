<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class Patal extends Model
{
    use HasFactory;
    protected $table = 'patal'; // ชื่อตารางในฐานข้อมูล
    protected $fillable = ['id','title','url','icon','status','seq_no'];
}
