<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Activity extends Model
{
    use HasFactory;

    // กำหนดชื่อตาราง (หากไม่ได้ใช้ชื่อพหูพจน์ตามมาตรฐาน Laravel)
    protected $table = 'activities';

    // รายการ Field ที่อนุญาตให้บันทึกแบบ Mass Assignment
    protected $fillable = [
        'title',
        'slug',
        'category',
        'short_description',
        'content',
        'thumbnail',
        'event_date',
        'event_time',
        'location',
        'is_featured',
        'status',
        'view_count'
    ];

    /**
     * การแปลงประเภทข้อมูล (Data Casting)
     * ช่วยให้เราใช้งานวันที่และสถานะ Boolean ได้ง่ายขึ้น
     */
    protected $casts = [
        'event_date' => 'date', // แปลงเป็น Carbon instance อัตโนมัติ
        'event_time' => 'datetime:H:i',
        'is_featured' => 'boolean',
        'status' => 'boolean',
        'view_count' => 'integer',
    ];

    /**
     * Boot function สำหรับจัดการ Logic อัตโนมัติ
     */
    protected static function boot()
    {
        parent::boot();

        // สร้าง Slug อัตโนมัติจาก Title ก่อนบันทึกข้อมูล
        static::creating(function ($activity) {
            if (empty($activity->slug)) {
                $activity->slug = Str::slug($activity->title, '-');
            }
        });
    }

    /**
     * Scope สำหรับดึงเฉพาะข่าวสาร (News)
     */
    public function scopeNews($query)
    {
        return $query->where('category', 'News');
    }

    /**
     * Scope สำหรับดึงเฉพาะกิจกรรม (Activities)
     */
    public function scopeEvents($query)
    {
        return $query->where('category', 'Activity');
    }

    /**
     * Scope สำหรับรายการที่เปิดใช้งานอยู่เท่านั้น
     */
    public function scopeActive($query)
    {
        return $query->where('status', true);
    }
}
