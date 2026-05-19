<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class Business_trip extends Model
{
    use HasFactory;
    protected $table = 'business_trip';
    protected $fillable = ['id','purpose','to','start_date','end_date'
    ,'category_color','departure_flight','arrive_flight','remarks','user_id'];
}
