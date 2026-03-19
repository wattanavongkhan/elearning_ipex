<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class CoursesFile extends Model
{
    use HasFactory;
    protected $table = 'courses_files';
    protected $fillable = ['id','course_id','file_id','user_id','created_at','updated_at'];
}
