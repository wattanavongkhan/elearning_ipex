<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dashboard_link extends Model
{
    use HasFactory;
    public $table = "tbldashboard_link";
    protected $fillable = [
        'section_id', 'title', 'powerbi_link'
    ];
}
