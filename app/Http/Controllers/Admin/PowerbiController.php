<?php namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use App\Models\Inventory_lo;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel; 

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

        return view('home.power_bi.index', compact('sections'));
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

    public function dashboard_mng($id, Request $req)
    {
        $dash_link = DB::connection('dashboard_bi_db')->table('tbldashboard_link as sec');
        // $section=DB::connection('central_staff_db')->table('tblsection as sec')->select('sec.id')
        // ->where('sec.id', $id)->first()->id;
        $section = DB::connection('dashboard_bi_db')
            ->table('tblsection_dashboard as sec')
            ->where('sec.section_no', $id)
            ->pluck('sec.id')
            ->toArray();

        $sec_id = [];
        $table="";
        $category=$req->category_id;

        $dash_link=$dash_link->whereIn('section_id',$section);
        $dash_link_all=$dash_link->whereIn('section_id',$section)->get();

        if($req->category_id!=null)
        {
            $table = $dash_link->where('id',$req->category_id)->first()->table;
        }else{
            dd($dash_link->first());
            $table = $dash_link->first()->table;
        }
        dd($table);
        $columns = DB::connection('dashboard_bi_db')->select('SHOW FIELDS FROM '.$table);

        $rows = DB::connection('dashboard_bi_db')->select("SELECT * FROM ".$table." ORDER BY created_at DESC");

        $dash_link=$dash_link->get();


        $tabale_name=DB::connection('dashboard_bi_db')->table('tbltable_name')
        ->where('section_id',$id)
        ->get();

        return view('home.power_bi.management_it', compact('columns', 'rows', 'dash_link',
         'id','table','dash_link_all',
        'tabale_name'));
    }


    public function dashboard_upload(Request $req)
    {
        // 1. Validate ไฟล์ที่ส่งมา
        $req->validate([
            'file_bi' => 'required|mimes:xlsx,xls,csv|max:10240', // จำกัดขนาด 10MB
        ]);

        // try {
        


        // 1. เปิดไฟล์ที่ Upload ขึ้นมา (Read Mode)
        $file = fopen($req->file('file_bi')->getRealPath(), "r");

        // 2. ข้ามบรรทัดแรก (Header)
        $header = fgetcsv($file); 

        // 3. วนลูปอ่านข้อมูลทีละบรรทัด
        $data_i=[];
        $i=0;
        while (($row = fgetcsv($file, 1000, ",")) !== FALSE) {
            // $row จะเป็น Array ตามลำดับคอลัมน์ในไฟล์
            // index 0 = คอลัมน์แรก, 1 = คอลัมน์สอง...
            
            // DB::table('tblparts_stock')->insert([
            //     'record_month'   => $row[0],
            //     'customer_name'  => $row[1],
            //     'part_name'      => $row[2],
            //     'part_no'        => $row[3],
            //     'current_status' => $row[4],
            //     'price'          => $row[5],
            //     'unit'           => $row[6],
            //     'stock_quantity' => $row[7],
            //     'amount_thb'     => $row[8],
            //     'part_type'      => $row[9],
            //     'supplier_name'  => $row[10],
            //     'created_at'     => now(),
            //     'updated_at'     => now(),
            // ]);
            $data_i.add($row[0]);
            $i++;
        }
        dd($data_i);
        fclose($file);
        //     // 3. ส่งกลับพร้อมข้อความสำเร็จ
        //     return back()->with('success', 'นำเข้าข้อมูลจาก Excel เข้าสู่ MySQL เรียบร้อยแล้ว!');

        // } catch (\Exception $e) {
        //     // กรณีเกิด Error (เช่น หัว Column ไม่ตรง หรือไฟล์พัง)
        //     Log::error('Excel Import Error: ' . $e->getMessage());
        //     return back()->with('error', 'เกิดข้อผิดพลาด: ' . $e->getMessage());
        // }
    }


}
