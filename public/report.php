<?php
// 1. ตั้งค่าเริ่มต้น
$id = 'SENSOR_01';
$temp = 'No Data';
$hum = 'No Data';

// 2. ส่งคำสั่งดึงข้อมูลสถานะล่าสุด (JSON) จากตัวเครื่อง Shelly ตรงๆ ผ่าน Network
$shelly_ip = '192.168.230.150';
$api_url = "http://{$shelly_ip}/rpc/Shelly.GetStatus";

// กำหนดเวลา Timeout ป้องกันสคริปต์ค้างหากเครื่องหลับไปก่อน
$ctx = stream_context_create([
    'http' => ['timeout' => 3] 
]);

$response = @file_get_contents($api_url, false, $ctx);

if ($response !== false) {
    $data = json_decode($response, true);
    
    // 3. แกะโครงสร้าง JSON ของเครื่อง Shelly Gen3 ตัวจริง
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

// 4. จัดรูปแบบข้อความบันทึก
$current_time = date('Y-m-d H:i:s');
$log_message  = "[$current_time] ID: $id | Temp: {$temp}°C | Hum: {$hum}%\n";

// 5. บันทึกลงไฟล์ log.txt ในไดรฟ์ D
file_put_contents('D:/xampp/htdocs/elearning_ipex/public/log.txt', $log_message, FILE_APPEND);

// 6. ตอบกลับสเตตัสให้กับเครื่อง Shelly
header('Content-Type: application/json');
echo json_encode(["status" => "success"]);