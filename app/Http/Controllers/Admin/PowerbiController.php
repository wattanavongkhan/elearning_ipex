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

        $data_so = DB::connection('dashboard_bi_db')
            ->table('tbllar_so')
            ->select('defect', DB::raw('SUM(qty) as total_qty'))
            ->groupBy('defect')
            ->orderBy('total_qty', 'desc')
            ->limit(5)
            ->get();

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
        // ดึงข้อมูลกลุ่มตาม Customer และ PCR Level
        $data_fa = DB::connection('dashboard_bi_db')
            ->table('tblsummary_pcr_fape')
            ->select('customer', 'pcr_level', DB::raw('count(*) as total'))
            ->groupBy('customer', 'pcr_level')
            ->get();

        // จัดกลุ่มข้อมูลใหม่เพื่อให้ง่ายต่อการใส่ใน Series ของกราฟ
        $customers_fa = $data_fa->pluck('customer')->unique()->values()->toArray();
        
        $externalData_fa = [];
        $internalData_fa = [];

        foreach ($customers_fa as $customer) {
            $externalData_fa[] = $data_fa->where('customer', $customer)->where('pcr_level', 'external')->first()->total ?? 0;
            $internalData_fa[] = $data_fa->where('customer', $customer)->where('pcr_level', 'internal')->first()->total ?? 0;
        }

        return view('home.power_bi.index', compact('sections', 'data_hr', 'data_it', 
        'data_iso', 'data_lo', 'data_mt', 'data_pc', 'data_pr', 'data_qa', 
        'data_qc', 'data_so', 'data_tl', 'internalData_fa', 'externalData_fa','customers_fa'));
    }

    public function show($id) 
    {
        $dashboard_links = DB::connection('dashboard_bi_db')
        ->table('tbldashboard_link')
        ->where('section_id', $id)
        ->get();

        return json_decode($dashboard_links);
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


}
