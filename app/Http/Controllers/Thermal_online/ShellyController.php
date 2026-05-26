<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
// use App\Models\SensorLog; // เปิดใช้งานหากมี Model สำหรับเก็บลง Database แล้ว

class ShellyController extends Controller
{
    /**
     * รองรับ Webhook Action ที่ยิงมาจากเครื่อง Shelly H&T
     */
    public function handleWebhook(Request $request)
    {

        $deviceId = 'SENSOR_01';
        $temp = 'No Data';
        $hum = 'No Data';

        // 1. ยิงดึงค่าล่าสุดจากตัวเครื่องผ่าน Laravel HTTP Client
        $shellyIp = '192.168.230.150';
        $apiUrl = "http://{$shellyIp}/rpc/Shelly.GetStatus";

        try {
            // ตั้ง Timeout ไว้ 3 วินาที ป้องกันเซิร์ฟเวอร์ค้างหากเครื่องหลับไปก่อน
            $response = Http::timeout(3)->get($apiUrl);

            if ($response->successful()) {
                $data = $response->json();

                // 2. แกะโครงสร้าง JSON ของเครื่อง Shelly Gen3
                if (isset($data['temperature:0']['tC'])) {
                    $temp = $data['temperature:0']['tC'];
                }
                if (isset($data['humidity:0']['rh'])) {
                    $hum = $data['humidity:0']['rh'];
                }
            } else {
                $temp = 'Fetch Error';
                $hum = 'Fetch Error';
            }
        } catch (\Exception $e) {
            // ดักจับเคสเน็ตเวิร์กหลุด หรือเครื่องหลับลึกจนคิวรีไม่ได้
            $temp = 'Connection Timeout';
            $hum = 'Connection Timeout';
            
            // แอบโยนข้อความระบบเข้า Laravel Logs หลักเพื่อวิเคราะห์ย้อนหลัง
            Log::warning("Shelly Connection Failed: " . $e->getMessage());
        }

        $currentTime = Carbon::now()->toDateTimeString();

        // 3. จัดการบันทึกข้อมูล (เลือกเปิด/ปิดใช้งานตามต้องการ)

        // 🔹 แบบที่ A: บันทึกลงตารางฐานข้อมูลโดยตรงผ่าน Laravel Eloquent (แนะนำสำหรับทำ Dashboard / Power BI)
        /*
        SensorLog::create([
            'device_id'   => $deviceId,
            'temperature' => is_numeric($temp) ? $temp : null,
            'humidity'    => is_numeric($hum) ? $hum : null,
            'created_at'  => $currentTime
        ]);
        */

        // 🔹 แบบที่ B: พ่นลงไฟล์ log.txt แยกเฉพาะกลุ่มแบบมาตรฐาน Laravel
        $logMessage = "[$currentTime] ID: $deviceId | Temp: {$temp}°C | Hum: {$hum}%\n";
        
        // บันทึกไปที่พาธ storage/app/public/shelly_logs.txt 
        Storage::disk('public')->append('shelly_logs.txt', trim($logMessage));

        // 4. ส่ง HTTP JSON ตอบกลับความสำเร็จให้เครื่อง Shelly รับทราบ
        return response()->json([
            'status' => 'success',
            'timestamp' => $currentTime
        ], 200);
    }
}