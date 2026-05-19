<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class Schedule extends Model
{
    use HasFactory;
    protected $table = 'schedule';
    protected $fillable = ['id','purpose','start_date','end_date','category_color'
    ,'members','room','status','user_id'];
}
