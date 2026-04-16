<?php namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use App\Models\Section;
use Illuminate\Support\Facades\DB;

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

    public function dashboard_mng($id)
    {
        $dash_link = DB::connection('dashboard_bi_db')
        ->table('tbldashboard_link as sec')->get();


        return view('home.power_bi.management_it', compact('dash_link'));
    }
}
