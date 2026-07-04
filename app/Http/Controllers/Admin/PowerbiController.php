<?php namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class PowerbiController extends Controller 
{
    public function index() 
    {
        /*
        // --------------------------
        $filePath = public_path('inj.xlsx');

        if (!file_exists($filePath)) {
            return response()->json(['status' => 'error', 'message' => 'หาไฟล์ Daily.xlsx ไม่เจอ'], 404);
        }

        $sheetData = [];
        $zip = new \ZipArchive;
        // if ($zip->open($filePath) === TRUE) {
        //     $sheetXml = $zip->getFromName('xl/worksheets/sheet1.xml');
        //     $stringsXml = $zip->getFromName('xl/sharedStrings.xml');
            
        //     $zip->close();

        //     if (!$sheetXml) {
        //         return response()->json(['status' => 'error', 'message' => 'ไม่พบโครงสร้างข้อมูลในไฟล์ Excel'], 500);
        //     }

        //     $sharedStrings = [];
        //     if ($stringsXml) {
        //         $xmlStrings = new \SimpleXMLElement($stringsXml);
        //         foreach ($xmlStrings->si as $val) {
        //             $sharedStrings[] = (string)$val->t;
        //         }
        //     }

        //     // 3. อ่านโครงสร้างตาราง (Rows และ Columns)
        //     $xmlSheet = new \SimpleXMLElement($sheetXml);
        //     $isHeader = true;

        //     $i=0;
        //     foreach ($xmlSheet->sheetData->row as $row) {
        //         if ($isHeader) {
        //             $isHeader = false;
        //             continue;
        //         }

        //         $rowAttr = $row->attributes();
        //         $rowNumber = (int)$rowAttr['r']; 

        //         $rowData = [];

        //         foreach ($row->c as $cell) {
        //             $cellAttr = $cell->attributes();
        //             $cellCoordinate = (string)$cellAttr['r']; // เช่น C8, D8, E8...

        //             $columnLetter = preg_replace('/[0-9]/', '', $cellCoordinate); 

        //             if ($columnLetter === 'A' || $columnLetter === 'B') {
        //                 continue;
        //             }

        //             $cellType = isset($cellAttr['t']) ? (string)$cellAttr['t'] : '';
        //             $value = isset($cell->v) ? (string)$cell->v : '';

        //             if ($cellType === 's' && isset($sharedStrings[$value])) {
        //                 $value = $sharedStrings[$value];
        //             }

        //             if ($value !== '' && is_numeric($value)) {
        //                 $value = round($value);
        //             }

        //             $rowData[$columnLetter] = $value;
                   
        //         }

        //             if ($rowNumber === 8) {
        //                 $row_plan = $rowData;
        //             } elseif ($rowNumber === 9) {
        //                 $row_ACC_PLAN = $rowData;
        //             } elseif ($rowNumber === 10) { 
        //                 $row_ACTUAL = $rowData;
        //             } else if ($rowNumber === 11) {
        //                 $row_ACC_ACTUAL = $rowData;
        //             }

        //         if($i==12)
        //         {
        //             $day = 1;
        //             foreach ($rowData as $key=> $value) 
        //             {
        //                 $formattedDate = date('Y') . '-05-' . str_pad($day, 2, '0', STR_PAD_LEFT);
        //                 DB::connection('dashboard_bi_db')
        //                 ->table('tblsale_report_daily_pc')->insert([
        //                     'cus_code'         => 'ADVICS', // คอลัมน์ A
        //                     'plan'        => $row_plan[$key] ?? null, // คอลัมน์ B
        //                     'accplan'    => $row_ACC_PLAN[$key] ?? null, // คอลัมน์ C
        //                     'acture'             => $row_ACTUAL[$key] ?? null, 
        //                     'acc_acture'             => $row_ACC_ACTUAL[$key] ?? null, 
        //                     'date'             => $formattedDate, // คอลัมน์ D
        //                 ]);

        //                 $day++;
        //             }
        //                 dd($row_plan, $row_ACC_PLAN, $row_ACTUAL, $row_ACC_ACTUAL);
                                        
        //         }
                
        //         if (!empty($rowData)) {
        //             $sheetData[] = $rowData;

                    // DB::table('tbldata_cut_pc')->insert([
                    //     'cus_name'         => $rowData[0] ?? null, // คอลัมน์ A
                    //     'part_name'        => $rowData[1] ?? null, // คอลัมน์ B
                    //     'product_group'    => $rowData[2] ?? null, // คอลัมน์ C
                    //     'date'             => $rowData[3] ?? null, // คอลัมน์ D
                    //     'created_at'       => now(),
                    //     'updated_at'       => now(),
                    // ]);
        //         }
        //         $i++;
        //     }



        //     return response()->json([
        //         'status' => 'success',
        //         'message' => 'แกะข้อมูลจาก .xlsx สำเร็จด้วย PHP ดิบ (ไม่ใช้ปลั๊กอินและไม่มีสูตรค้าง)!',
        //         'total_rows' => count($sheetData),
        //         'data' => $sheetData
        //     ]);

        // } else {
        //     return response()->json(['status' => 'error', 'message' => 'ไม่สามารถเปิดไฟล์ในระบบ Zip ได้'], 500);
        // }
        // dd("0");

          if ($zip->open($filePath) === TRUE) {
    $sheetXml = $zip->getFromName('xl/worksheets/sheet1.xml');
    $stringsXml = $zip->getFromName('xl/sharedStrings.xml');
    $zip->close();

    if (!$sheetXml) {
        return response()->json(['status' => 'error', 'message' => 'ไม่พบโครงสร้างข้อมูลในไฟล์ Excel'], 500);
    }

    $sharedStrings = [];
    if ($stringsXml) {
        $xmlStrings = new \SimpleXMLElement($stringsXml);
        foreach ($xmlStrings->si as $val) {
            $sharedStrings[] = isset($val->t) ? (string)$val->t : '';
        }
    }

    $xmlSheet = new \SimpleXMLElement($sheetXml);
    
    // ⭐ จุดแก้ไขที่ 1: ปรับเลขแถวให้เป็นช่วง 1081 - 1094
    $rowMapping = [
        'plan'                    => 1081,
        'accumulated_shot'        => 1082,
        'actual'                  => 1083,
        'diff_from_plan'          => 1084,
        'throw_away'              => 1085,
        'qc_sample_partrol'       => 1086,
        'inj_sample_partrol'      => 1087,
        'actual_cav'              => 1088,
        'shot_cont'               => 1089,
        'mold_oh'                 => 1090,
        'actual_accum_shot'       => 1091,
        'total_mold_shot'         => 1092,
        'screw_oh'                => 1093,
        'actual_accum_shot_count' => 1094,
    ];

    $data = array_fill_keys(array_keys($rowMapping), []);

    $colToNumber = function($col) {
        $length = strlen($col); $number = 0;
        for ($i = 0; $i < $length; $i++) { $number = $number * 26 + (ord($col[$i]) - ord('A') + 1); }
        return $number;
    };

    $minColNum = $colToNumber('E');  $maxColNum = $colToNumber('AH');

    foreach ($xmlSheet->sheetData->row as $row) {
        $rowAttr = $row->attributes();
        $rowNumber = (int)$rowAttr['r']; 

        // ⭐ จุดแก้ไขที่ 2: ดักช่วงแถวให้ตรงกับขอบเขตใหม่ (1081 - 1094)
        if ($rowNumber < 1081 || $rowNumber > 1094) {
            continue;
        }

        if (isset($row->c)) {
            foreach ($row->c as $cell) {
                $cellAttr = $cell->attributes();
                if (!isset($cellAttr['r'])) continue;

                $columnLetter = strtoupper(preg_replace('/[0-9]/', '', (string)$cellAttr['r'])); 
                if ($colToNumber($columnLetter) < $minColNum || $colToNumber($columnLetter) > $maxColNum) {
                    continue;
                }

                $value = isset($cell->v) ? (string)$cell->v : '';
                if (isset($cellAttr['t']) && (string)$cellAttr['t'] === 's' && $value !== '' && isset($sharedStrings[(int)$value])) {
                    $value = $sharedStrings[(int)$value];
                }

                $value = round($value);

                $fieldKey = array_search($rowNumber, $rowMapping);
                if ($fieldKey !== false) {
                    $data[$fieldKey][$columnLetter] = $value;
                }
            }
        }

        // ประมวลผลและ Insert เมื่อวิ่งมาถึงแถวสุดท้ายของช่วง (แถว 1094)
        if ($rowNumber === 1094) {
            $day = 1;
            foreach ($data['plan'] as $key => $value) {
                $dates = date('Y') . '-06-' . str_pad($day, 2, '0', STR_PAD_LEFT);
                
                $insertData = [
                    'cus_code'    => "Tray",
                    'mc_no'       => "A10 (150tons)",
                    'part_name'   => "Bar for  Ramp Carmel 2D",
                    'std_oh_shot' => "STD. shot mold O/H 1time/month",
                    'date'        => $dates,
                ];

                foreach (array_keys($rowMapping) as $field) {
                    $insertData[$field] = $data[$field][$key] ?? null;
                }

                DB::connection('dashboard_bi_db')->table('tblinjection_output_inj')->insert($insertData);
                $day++;
            }
            dd("-");
        }
    }
}
*/
// ----------------------------
        

        $sections = DB::connection('dashboard_bi_db')
        ->table('tblsection_dashboard as sec')
        ->leftJoin('dashboard_bi_db.tbldashboard_link as bi', 'sec.id', '=', 'bi.section_id')
        ->select('sec.id','sec.section_no', 'sec.section', DB::raw('count(bi.id) as total_links'))
        ->where('sec.status', '0')
        ->groupBy('sec.id', 'sec.section')
        ->get();
        
        $data_hr = DB::connection('dashboard_bi_db')
            ->table('tblworking_hr')
            ->select('section', DB::raw('SUM(6_day_ith + 6_day_subcon) as total_employees'))
            ->groupBy('section')
            ->having('total_employees', '>', 0) 
            // แสดงเฉพาะแผนกที่มีพนักงานทำงาน 6 วัน
            ->orderBy('total_employees', 'desc')
            ->get();

        $data_it = DB::connection('dashboard_bi_db')->table('tblsupport_request_it')
            ->select('department', DB::raw('count(*) as total'))
            ->groupBy('department')
            ->orderBy('total', 'desc')
            ->get();

        $data_iso = DB::connection('dashboard_bi_db')->table('tbltotal_electric_isosf')
            ->whereBetween('year', [2021, 2025])
            ->orderBy('year', 'asc')
            ->get();

        $data_lo = DB::connection('dashboard_bi_db')->table('tblinventory_finished_good_lo')
            ->select('part_name', DB::raw('SUM(amount_thb) as total_value'))
            ->groupBy('part_name')
            ->orderBy('total_value', 'desc')
            ->limit(5)
            ->get();

        $data_mt = DB::connection('dashboard_bi_db')
        ->table('tbldaily_prod_record_mt')
        ->select('mc', DB::raw('AVG(
            CAST(REPLACE(`column_a`, "%", "") AS DECIMAL(10,2)) * 
            CAST(REPLACE(`column_p`, "%", "") AS DECIMAL(10,2)) * 
            CAST(REPLACE(`column_q`, "%", "") AS DECIMAL(10,2)) / 10000) as avg_oee'))
          ->where('mc', 'LIKE', 'R%') 
        ->groupBy('mc')
        ->orderBy('avg_oee', 'desc')
        ->get();

        $data_pc = DB::connection('dashboard_bi_db')
            ->table('tbldata_cut_pc')
            ->select('part_name', DB::raw('SUM(actual) as total_actual'))
            ->groupBy('part_name')
            ->orderBy('total_actual', 'desc')
            ->limit(5)
            ->get();
        
        $data_pr = DB::connection('dashboard_bi_db')
            ->table('tblsupplier_part_pr')
            ->select('suppliers_name', DB::raw('SUM(new_total) as total_new_total'))
            ->groupBy('suppliers_name')
            ->orderBy('total_new_total', 'desc')
            ->limit(5)
            ->get();



        //QA
        // รวมยอด Claim ทั้ง 3 ประเภทแยกตามปี
        $claimData = DB::connection('dashboard_bi_db')
            ->table('tblcustomer_claim_qa')
            ->select(
                'year',
                DB::raw('SUM(customer_claim_a) as total_a'),
                DB::raw('SUM(customer_claim_b) as total_b'),
                DB::raw('SUM(customer_claim_c) as total_c')
            )
            ->groupBy('year')
            ->get();

        // ปรับโครงสร้างข้อมูลให้เหมาะกับการแสดงผล Pie Chart (Label & Value)
        $data_qa = [];
        foreach ($claimData as $row) {
            if ($row->total_a > 0) $data_qa[] = ['label' => "Claim (A) {$row->year}", 'value' => (int)$row->total_a, 'year' => $row->year];
            if ($row->total_b > 0) $data_qa[] = ['label' => "Claim (B) {$row->year}", 'value' => (int)$row->total_b, 'year' => $row->year];
            if ($row->total_c > 0) $data_qa[] = ['label' => "Claim (C) {$row->year}", 'value' => (int)$row->total_c, 'year' => $row->year];
        }


        $data_qc = DB::connection('dashboard_bi_db')
            ->table('tblescape_qc')
            ->select('year', 'slip_through_item', DB::raw('SUM(ng_qty_pcs) as total_ng'))
            ->groupBy('year', 'slip_through_item')
            ->orderBy('year', 'asc')
            ->get();

        // SO
        $inspect_data = DB::connection('dashboard_bi_db')
        ->table('tblinspect_output_so')
        ->select(
            'items',
            DB::raw("
                CASE 
                    WHEN CAST(REPLACE(plan, ',', '') AS DECIMAL(10,2)) > 0 
                    THEN ROUND((CAST(REPLACE(actual, ',', '') AS DECIMAL(10,2)) / CAST(REPLACE(plan, ',', '') AS DECIMAL(10,2))) * 100, 0)
                    ELSE 0 
                END as rate
            ")
        )
        ->whereNotNull('items')
        ->where('plan', '<>', '-') // ข้ามรายการที่ไม่มีแผนงาน
        ->where('plan', '<>', '')
        ->orderBy('rate', 'desc') // เรียงลำดับเปอร์เซ็นต์จากมากไปน้อยแบบในภาพ
        ->get();

        // เตรียมข้อมูลส่งไปยังหน้า Blade
        $inspectItems_so = $inspect_data->pluck('items')->toArray();
        $inspectRates_so = $inspect_data->pluck('rate')->map(function($item) {
            return (int)$item; // ส่งค่าไปเป็นตัวเลข Integer เช่น 193, 105, 99
        })->toArray();

        //TL
        $rawRecords = DB::connection('dashboard_bi_db')
            ->table('tblcostdamage_tl')
            ->select(
                'year',
                DB::raw('SUM(cost_new_order_insert) as total_insert'),
                DB::raw('SUM(cost_repair) as total_repair')
            )
            ->groupBy('year')
            ->orderBy('year', 'desc')
            ->get();

        // ปรับโครงสร้างเพื่อส่งให้กราฟ (รวมยอดต่อปีเพื่อแสดงป้ายกำกับ)
        $data_tl = [];
        foreach ($rawRecords as $row) {
            if ($row->total_insert > 0) {
                $data_tl[] = [
                    'label' => "Cost Insert ({$row->year})",
                    'value' => (float)$row->total_insert,
                    'year' => $row->year
                ];
            }
            if ($row->total_repair > 0) {
                $data_tl[] = [
                    'label' => "Cost Repair ({$row->year})",
                    'value' => (float)$row->total_repair,
                    'year' => $row->year
                ];
            }
        }


        //FA
       // 1. ดึงข้อมูลและคำนวณยอดรวม external และ internal แยกตาม customer
        $data_fa = DB::connection('dashboard_bi_db')
            ->table('tblsummary_pcr_fape')
            ->select(
                'customer',
                DB::raw('SUM(CASE WHEN external = 1 THEN 1 ELSE 0 END) as total_external'),
                DB::raw('SUM(CASE WHEN internal = 1 THEN 1 ELSE 0 END) as total_internal'),
                DB::raw('COUNT(*) as total_all') // สำหรับเอาไว้เรียงลำดับจากมากไปน้อยแบบในรูป
            )
            ->groupBy('customer')
            ->orderBy('total_all', 'desc') // เรียงลำดับจากลูกค้าที่มีเคสเยอะที่สุดไปน้อยที่สุด เหมือนในกราฟ
            ->get();

        // 2. แยกชื่อลูกค้าออกมาทำเป็นแกน X (Labels)
        $customers_fa = $data_fa->pluck('customer')->toArray();

        // 3. เตรียมข้อมูลสำหรับ Series ของกราฟ (แกน Y)
        $externalData_fa = $data_fa->pluck('total_external')->toArray();
        $internalData_fa = $data_fa->pluck('total_internal')->toArray();


        // ดึงข้อมูลและนับจำนวนเคสแยกตามสถานะ (status)
        $status_data_fa = DB::connection('dashboard_bi_db')
            ->table('tblsummary_pcr_fape') // หรือเปลี่ยนเป็นชื่อตารางเก็บสถานะของคุณ เช่น tblpcr_status
            ->select('status', DB::raw('COUNT(*) as total'))
            ->whereIn('status', ['Approved', 'On Process', 'Cancel']) // ดึงเฉพาะสถานะที่มีในกราฟ
            ->groupBy('status')
            ->get();

        // เตรียมข้อมูลส่งไปที่หน้า View
        $statusLabels_fa = $status_data_fa->pluck('status')->toArray(); // ผลลัพธ์ เช่น ['Approved', 'On Process', 'Cancel']
        $statusSeries_fa = $status_data_fa->pluck('total')->map(function($item) {
            return (int)$item; // แปลงค่าข้อความจาก MySQL ให้เป็นตัวเลข Integer สำหรับกราฟวงกลม
        })->toArray();


        // ดึงข้อมูลและนับจำนวนแยกตามเหตุผลการเปลี่ยน (reason_change)
        $changing_data_fa = DB::connection('dashboard_bi_db')
            ->table('tblsummary_pcr_fape')
            ->select('reason_change', DB::raw('COUNT(*) as total'))
            ->whereNotNull('reason_change')
            ->where('reason_change', '<>', '') // ไม่เอาค่าว่าง
            ->groupBy('reason_change')
            ->orderBy('total', 'desc') // เรียงลำดับจากมากไปน้อยเหมือนในกราฟต้นฉบับ
            ->get();

        // เตรียมข้อมูลส่งไปที่หน้า View (Blade)
        $changingLabels_fa = $changing_data_fa->pluck('reason_change')->toArray(); 
        $changingSeries_fa = $changing_data_fa->pluck('total')->map(function($item) {
            return (int)$item; // แปลงประเภทข้อมูลให้เป็น Integer
        })->toArray();

        return view('home.power_bi.index', compact('sections', 'data_hr', 'data_it', 
        'data_iso', 'data_lo', 'data_mt', 'data_pc', 'data_pr', 'data_qa', 
        'data_qc', 

         'data_tl', 'internalData_fa', 'externalData_fa','customers_fa',
         'statusLabels_fa','statusSeries_fa',
         'changingLabels_fa','changingSeries_fa',
         'inspectItems_so',
        'inspectRates_so'
         ));
    }

    public function show($id) 
    {
        $dashboard_links = DB::connection('dashboard_bi_db')
        ->table('tbldashboard_link')
        ->where('section_id', $id)
        ->get();

        return json_decode($dashboard_links);
    }

    public function daily_sale(Request $req) 
    {
        $year = date('Y'); // ใช้ปีปัจจุบันเป็นค่าเริ่มต้น
        if($req->month==null)
        {
            $month = date('m'); // ใช้เดือนปัจจุบันเป็นค่าเริ่มต้น
        }else{
            $month = $req->month;
        }

        $startDate = "{$year}-{$month}-01";
        $daysInMonth = (int)date("t", strtotime($startDate)); 
        $endDate = "{$year}-{$month}-{$daysInMonth}"; 

        $annualPlanRow = DB::connection('dashboard_bi_db')->table('tblannual_plan_pc')
            ->where('year', $year)
            ->where('month', (int)$month)
            ->first();

        $annualPlanValue = $annualPlanRow ? $annualPlanRow->annual_plan : 0;

        $dailyAnnualStep = ($daysInMonth > 0) ? ($annualPlanValue / $daysInMonth / 1000) : 0;
        
        $chartData = DB::connection('dashboard_bi_db')
        ->table('tblsale_report_daily_pc')
        ->select(
                'date',
                DB::raw('SUM(plan) as daily_plan'),
                DB::raw('SUM(accplan) as daily_accplan'),
                DB::raw('SUM(acture) as daily_acture'),
                DB::raw('SUM(acc_acture) as daily_acc_acture'),
                DB::raw('SUM(acc_annual) as daily_acc_annual')
            )
            // ใช้ตัวแปร startDate และ endDate ที่คำนวณไว้มาดักกรองวันที่
            ->where('date', '>=', $startDate)
            ->where('date', '<=', $endDate)
            ->groupBy('date')
            ->orderBy('date', 'asc')
            ->get()
            ->keyBy(function($item) {
                return (int)date('j', strtotime($item->date)); // เอาเลขวัน (1-31) เป็น Key เพื่อนำไป map ข้อมูลได้ง่าย
            });

        // 6. ลูปสร้างข้อมูลรายวันให้ครบถ้วนตั้งแต่วันที่ 1 ถึงวันสุดท้ายของเดือนนั้นๆ
        $labels = [];
        $accPlanData = [];
        $accAnnualData = [];
        $accActualData = [];

        for ($day = 1; $day <= $daysInMonth; $day++) {
            $labels[] = $day; // ได้เลขอาร์เรย์วัน [1, 2, 3, ..., 31]

            // ตรวจสอบว่าในฐานข้อมูลมีข้อมูลของวันนั้นๆ ไหม
            if (isset($chartData[$day])) {
                $accPlanData[] = (float)$chartData[$day]->daily_accplan;
                $accActualData[] = (float)$chartData[$day]->daily_acc_acture;
                $accAnnualData[] = (float)$chartData[$day]->daily_acc_annual;
            } else {
            //     // หากวันไหนไม่มีข้อมูลสะสมใน DB ให้สืบทอดดึงค่าล่าสุดของวันก่อนหน้ามาใส่ (สไตล์กราฟสะสม)
                $accPlanData[] = end($accPlanData) !== false ? end($accPlanData) : 0;
                $accActualData[] = end($accActualData) !== false ? end($accActualData) : 0;
            }

            // $accAnnualData[] = round($dailyAnnualStep * $day);
        }

        // 7. อาเรย์รายชื่อเดือนภาษาอังกฤษตามที่ระบุ
        $months = [
            '01' => 'January',
            '02' => 'February',
            '03' => 'March',
            '04' => 'April',
            '05' => 'May',
            '06' => 'June',
            // '07' => 'July',
            // '08' => 'August',
            // '09' => 'September',
            // '10' => 'October',
            // '11' => 'November',
            // '12' => 'December',
        ];

        
        $monthinj=$req->month ?? 5;
        $yearinj=date('Y');
        $totalDays = cal_days_in_month(CAL_GREGORIAN, (int)$monthinj, (int)$yearinj);

        // สร้างกล่องแกน X บนกราฟเป็นวันที่ [1, 2, 3, ..., 30]
        $labelsinj = range(1, $totalDays); 

        // 2. Query ข้อมูลทั้งหมดของเดือนและปีนั้นมาแบบทีเดียวจบ (ใส่เงื่อนไขดักเดือน/ปี เพื่อความถูกต้องของข้อมูล)
        $rawinj = DB::connection('dashboard_bi_db')
            ->table('tblinjection_output_inj')
            // ->where('date', 'like', "{$year}-" . str_pad($month, 2, '0', STR_PAD_LEFT) . "-%")
            // ->orderBy('date', 'asc')
            ->get();

        // 3. ยุบรวมข้อมูลดิบ: ใน 1 วันอาจจะมีหลายลูกค้า (หลาย Row) ให้เอามา SUM ยอดดิบรวมกันรายวันก่อน
        $dailySumij = [];
        foreach ($rawinj as $row) {
            $dayNum = (int)date('d', strtotime($row->date));
            
            if (!isset($dailySumij[$dayNum])) {
                $dailySumij[$dayNum] = [
                    'plan'   => 0,
                    'actual' => 0
                ];
            }
            
            // รวมยอดดิบของทุก cus_code ที่อยู่ในวันเดียวกันเข้าด้วยกัน
            $dailySumij[$dayNum]['plan']   += (int)$row->plan;
            $dailySumij[$dayNum]['actual'] += (int)$row->actual;
        }

        // 4. เตรียมชุดข้อมูลสะสมรวม (Total Sum) รายวันส่งไปให้ View
        $accPlan       = 0;
        $accActual     = 0;

        $accPlanDatainj   = [];
        $accActualDatainj = [];

        for ($d = 1; $d <= $totalDays; $d++) 
        {
            if (isset($dailySumij[$d])) 
            {
                $accPlanDatainj[]   = $dailySumij[$d]['plan'];
                $accActualDatainj[] = $dailySumij[$d]['actual'];
            }
        }

        // 8. ส่งข้อมูลทั้ง 3 เส้นสถิติสะสม พร้อมรายชื่อเดือนออกไปยังหน้า View
        return view('home.power_bi.daily_sale', compact(
            'labels', 
            'accPlanData', 
            'accAnnualData', // 🔴 ส่งเส้นสีดำที่คำนวณใหม่เพิ่มเข้าไป
            'accActualData', 
            'months','month','year',

            'labelsinj',
            'accPlanDatainj', 
            'accActualDatainj', 
            'monthinj',
            'yearinj'
        ));
    }

    public function pi_show() 
    {
        return json_decode();   
    }

    public function dashboard_mng(Request $req)
    {
        if(DB::connection('dashboard_bi_db')->table('tblsection_dashboard')->where('section_no',Auth::user()->section_id)->count()==0)
		{
			return $this->index();
		}else
		{
            $dash_link = DB::connection('dashboard_bi_db')->table('tbltable_name')
            ->where('section_id',DB::connection('dashboard_bi_db')
            ->table('tblsection_dashboard')
            ->where('section_no',Auth::user()->section_id)->first()->id
            )
            ->get();

    
            $table="";
            $title="";
            if (empty($req->category_id)) {
                $default = $dash_link->first();
                if ($default) {
                    $req->merge(['category_id' => $default->id]);
                }
            }

            if($req->category_id!=null)
            {
                $table = $dash_link->where('id',$req->category_id)->first()->table_name;
                $title = $dash_link->where('id',$req->category_id)->first()->table_des;
            } else {
                $table = $dash_link->first()->table_name;
                $title = $dash_link->first()->table_des;
            }

            $columns = DB::connection('dashboard_bi_db')->select("SHOW FIELDS FROM {$table} WHERE Field NOT IN ('created_at', 'updated_at','id')");
            $rows = DB::connection('dashboard_bi_db')->select("SELECT * FROM ".$table." ORDER BY created_at DESC");

            return view('home.power_bi.management_it', compact('columns', 'rows', 'dash_link','table','title'));
        }
    }

    public function dashboard_upload(Request $req)
    {
       $fields = DB::connection('dashboard_bi_db')->select("SHOW FIELDS FROM {$req->table_name} WHERE Field NOT IN ('created_at', 'updated_at', 'id')");
        $columns = collect($fields)->pluck('Field')->toArray();
        $table = $req->table_name;

        // ตรวจสอบเบื้องต้นว่ามีไฟล์ส่งมาจริงก่อนเปิดใช้งาน
        if (!$req->hasFile('file_bi')) {
            return back()->with('error', 'กรุณาเลือกไฟล์สำหรับอัปโหลด');
        }

        $file_path = $req->file('file_bi')->getRealPath();
        $file = fopen($file_path, "r");
        $header = fgetcsv($file); // ข้ามบรรทัด Header ของไฟล์ CSV

        $insert_batch = []; // สร้างตัวแปรพักข้อมูลเพื่อทำ Bulk Insert

        try {
            DB::connection('dashboard_bi_db')->beginTransaction();

            DB::connection('dashboard_bi_db')->table($table)->delete();

            while (($row = fgetcsv($file, 1000, ",")) !== FALSE) {
                if (count($columns) === count($row)) {
                    $insert_batch[] = array_combine($columns, $row);
                }
                if (count($insert_batch) >= 200) {
                    DB::connection('dashboard_bi_db')->table($table)->insert($insert_batch);
                    $insert_batch = []; // ล้างคิวรอรับรอบถัดไป
                }
            }

            if (!empty($insert_batch)) {
                DB::connection('dashboard_bi_db')->table($table)->insert($insert_batch);
            }

            DB::connection('dashboard_bi_db')->commit();

        } catch (\Exception $e) {
            DB::connection('dashboard_bi_db')->rollBack();
            
            if ($file) { fclose($file); }
            
            return back()->with('error', 'เกิดข้อผิดพลาดบนเซิร์ฟเวอร์: ' . $e->getMessage());
        }

        if ($file) { fclose($file); }

        $category = DB::connection('dashboard_bi_db')->table('tbltable_name')->where('table_name', $table)->first();

        $req->merge([
            'category_id' => $category ? $category->id : null,
        ]);
    }


    public function upload_excel()
    {
        $filePath = public_path('pc_3.xlsx');

        if (!file_exists($filePath)) {
            return response()->json(['status' => 'error', 'message' => 'หาไฟล์ pc.xlsx ไม่เจอ'], 404);
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

            // 2. เตรียมตัวแปรสำหรับพักข้อมูลแถว 57, 58, 59, 60
            $row_plan = [];
            $row_ACC_PLAN = [];
            $row_ANNUAL_PLAN = [];
            $row_ACC_ANNUAL = [];
            $row_ACTUAL = [];
            $row_ACC_ACTUAL = [];

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
                        // สกัดดึงตัวอักษรคอลัมน์ (เช่น C, D, E)
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

                        // if ($value !== '' && is_numeric($value)) {
                            $value = round($value);
                        // }

                        // จัดเก็บข้อมูลลงอาร์เรย์แบบผูก Key ด้วยชื่อคอลัมน์ (เช่น $rowData['C'] = 250)
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

            

                dd("-");

            return response()->json([
                'status' => 'success',
                'message' => 'แกะข้อมูลจาก C57 ถึงคอลัมน์สุดท้ายแถว 62 และลงฐานข้อมูลสำเร็จแล้ว!',
                'data' => $sheetData
            ]);

            dd(00);

        } else {
            return response()->json(['status' => 'error', 'message' => 'ไม่สามารถเปิดไฟล์ในระบบ Zip ได้'], 500);
        }


    }

}
