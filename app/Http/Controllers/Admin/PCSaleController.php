<?php namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PCSaleController extends Controller 
{
    public function index(Request $req) 
    {
        $sale_report=DB::connection('dashboard_bi_db')
        ->table('tblsale_report_daily_pc as ts');
        
        if($req->st_date!=null && $req->end_date!=null)
        {
            $sale_report->where('ts.date', '>=', $req->st_date)
            ->where('ts.date', '<=', $req->end_date);
        }
        $sale_report=$sale_report->limit(500)->get();
        
        return view('home.power_bi.daily_sale_mgn', compact('sale_report'));
    }


    public function store(Request $req)
    {
        DB::connection('dashboard_bi_db')
        ->table('tblsale_report_daily_pc')
        ->insert([
            'cus_code'       => "All",
            'date'       => trim($req->date),
            'accplan'    => trim($req->annual_plan),
            'acc_acture' => trim($req->acc_acture),
            'acc_annual' => trim($req->acc_annual),
        ]);

        return redirect()->route('daily_sale_mgn')->with('success', 'เรียบร้อยแล้ว');
    }


    public function destroy($id)
    {
        DB::connection('dashboard_bi_db')
        ->table('tblsale_report_daily_pc')
        ->where('id', $id)
        ->delete();

        return redirect()->route('daily_sale_mgn')->with('success', 'เรียบร้อยแล้ว');
    }


    public function upload_daily()
    {
        $filePath = '\\\\192.168.230.3\\Power_BI\\PC\\7-Daily Sale report Jul-26.xlsx';

        // 2. เช็คว่าเจอไฟล์ไหม
        if (!file_exists($filePath)) {
            return response()->json([
                'status' => 'error', 
                'message' => 'หาไฟล์บน Network Share ไม่เจอ หรือไม่มีสิทธิ์เข้าถึงโฟลเดอร์นี้'
            ], 404);
        }

        $sheetData = [];
        $zip = new \ZipArchive;
        
        if ($zip->open($filePath) === TRUE) {
            $sheetXml = $zip->getFromName('xl/worksheets/sheet1.xml');
            $stringsXml = $zip->getFromName('xl/sharedStrings.xml');
            $zip->close();

            if (!$sheetXml) {
                return response()->json(['status' => 'error', 'message' => 'ไม่พบโครงสร้างข้อมูลในไฟล์ Excel'], 500);
            }

            // 1. แกะ Shared Strings ข้อมูลข้อความ
            $sharedStrings = [];
            if ($stringsXml) {
                $xmlStrings = new \SimpleXMLElement($stringsXml);
                foreach ($xmlStrings->si as $val) {
                    $sharedStrings[] = (string)$val->t;
                }
            }

            $plan_THB = [];
            $annual_THB = [];
            $accum_JPY = [];
            $actual_THB = [];
            $actual_JPY = [];

            // 3. อ่านโครงสร้างตาราง (Rows และ Columns)
            $xmlSheet = new \SimpleXMLElement($sheetXml);
            $isHeader = true;

            foreach ($xmlSheet->sheetData->row as $row) 
            {
                if ($isHeader) {
                    $isHeader = false;
                    continue;
                }

                $rowAttr = $row->attributes();
                $rowNumber = (int)$rowAttr['r']; 

                // 🎯 ปรับฟิลเตอร์กรอง: อ่านเฉพาะแถวที่ 57 ถึง 60 เท่านั้น
                if ($rowNumber < 57 || $rowNumber > 62) {
                    continue;
                }
                    $rowData = [];
                    // วนลูปแกะข้อมูลแต่ละเซลล์ในแถวปัจจุบัน
                    foreach ($row->c as $cell) {
                        $cellAttr = $cell->attributes();
                        $cellCoordinate = (string)$cellAttr['r'];
                        $columnLetter = preg_replace('/[0-9]/', '', $cellCoordinate); 

                        // 🎯 ข้ามคอลัมน์ A และ B (เริ่มประมวลผลที่คอลัมน์ C เป็นต้นไป)
                        if ($columnLetter === 'A' || $columnLetter === 'B') {
                            continue;
                        }

                        $cellType = isset($cellAttr['t']) ? (string)$cellAttr['t'] : '';
                        $value = isset($cell->v) ? (string)$cell->v : '';

                        if ($cellType === 's' && isset($sharedStrings[$value])) {
                            $value = $sharedStrings[$value];
                        }

                        $value = round($value);
                        $rowData[$columnLetter] = $value;

                    }

                    if ($rowNumber === 57) {
                        $row_plan = $rowData;
                    }elseif($rowNumber === 58) {
                        $row_ACC_PLAN = $rowData;
                    }elseif($rowNumber === 59) {
                        $row_ANNUAL_PLAN = $rowData;
                    }elseif($rowNumber === 60) {
                        $row_ACC_ANNUAL = $rowData;
                    }elseif($rowNumber === 61) { 
                        $row_ACTUAL = $rowData;
                    }elseif($rowNumber === 62) {
                        $row_ACC_ACTUAL = $rowData;
                    }
                }

                dd($row_ACC_ACTUAL);
                
                   $day = 1;
                    foreach ($row_plan as $key => $value) 
                    {
                        $formattedDate = date('Y') . '-03-' . str_pad($day, 2, '0', STR_PAD_LEFT);
                        DB::connection('dashboard_bi_db')
                            ->table('tblsale_report_daily_pc')
                            ->insert([
                                'cus_code'   => 'All',
                                'plan'       => $row_plan[$key] ?? null,
                                'accplan'    => $row_ACC_PLAN[$key] ?? null,
                                'annual_plan'    => $row_ANNUAL_PLAN[$key] ?? null,
                                'acc_annual'    => $row_ACC_ANNUAL[$key] ?? null,
                                'acture'     => $row_ACTUAL[$key] ?? null, 
                                'acc_acture' => $row_ACC_ACTUAL[$key] ?? null, 
                                'date'       => $formattedDate,
                            ]);

                        $day++;
                    }
            return response()->json([
                'status' => 'success',
                'message' => 'ฐานข้อมูลสำเร็จแล้ว!',
                'data' => $sheetData
            ]);

        } else {
            return response()->json(['status' => 'error', 'message' => 'ไม่สามารถเปิดไฟล์ในระบบ Zip ได้'], 500);
        }


    }

}

