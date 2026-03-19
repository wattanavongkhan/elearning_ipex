<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class PrivateFile extends Model
{
    use HasFactory;
    protected $table = 'private_files'; // ชื่อตารางในฐานข้อมูล
    protected $fillable = ['id','file_type','file_name','file_path','user_id','size_file'];
}
