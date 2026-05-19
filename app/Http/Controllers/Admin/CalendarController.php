<?php namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Schedule;
use App\Models\Guest;
use App\Models\User;
use App\Models\Business_trip;
use App\Models\Enrollment;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class CalendarController extends Controller {

    public function schedule_index() 
    {
        $calendarEvents = Schedule::get();
        $user = Auth::user();
        return view('admin.schedule.index', compact('user', 'calendarEvents'));
    }
    public function schedule_create() {
        $schedules=Schedule::all();
        return view('admin.schedule.create', compact('schedules'));
    }

    public function schedule_edit($id) {
        $calendarEvent  = Schedule::findOrFail($id);
        return view('admin.schedule.edit', compact('calendarEvent'));
    }

    public function schedule_store (Request $req, Schedule $schedule) 
    {
        $req["user_id"] = auth()->id(); // เพิ่ม user_id ลงในข้อมูลที่ส่งมา
        if(!$req->id)
        {
           $schedule->create($req->all());
        }else{
            if($req->status == null){
                $req["status"] = 1; 
            }
            $schedule = Schedule::findOrFail($req->id);
            $schedule->update($req->all());
        }
       
        return redirect()->route('schedule.index')->with('success', 'ดำเนินการสำเร็จ');
    }

    public function schedule_destroy($id)
    {
        $schedule = Schedule::findOrFail($id);
        $schedule->delete();
        return redirect()->back()->with('success', 'ลบกิจกรรมเรียบร้อยแล้ว');
    }

    public function guest_index() 
    {
        $calendarEvents = Guest::get();
        return view('admin.guest.index', compact('calendarEvents'));
    }

    public function guest_create() {
        $guests=Guest::all();
        return view('admin.guest.create', compact('guests'));
    }

    public function guest_store (Request $req, Guest $guest) 
    {
        $req["user_id"] = auth()->id(); // เพิ่ม user_id ลงในข้อมูลที่ส่งมา
        if(!$req->id)
        {
             $guest->create($req->all());
        }else{
            if($req->status == null){
                $req["status"] = 1; // ถ้าไม่ได้ส่งค่า status มา ให้ตั้งเป็น 1 (active)
            }
            $guest = Guest::findOrFail($req->id);
            $guest->update($req->all());
        }
       
        return redirect()->route('guest.index')->with('success', 'ดำเนินการสำเร็จ');
    }

    public function guest_edit($id) {
        $calendarEvent  = Guest::findOrFail($id);
        return view('admin.guest.edit', compact('calendarEvent'));
    }

    public function guest_destroy($id)
    {
        $guest = Guest::findOrFail($id);
        $guest->delete();
        return redirect()->back()->with('success', 'ลบข้อมูลเรียบร้อยแล้ว');
    }

    public function business_trip_index() 
    {
        $calendarEvents = Business_trip::get();
        return view('admin.business_trip.index', compact('calendarEvents'));
    }

    public function business_trip_create() 
    {
        $business_trips=Business_trip::all();
        return view('admin.business_trip.create', compact('business_trips'));
    }

    public function business_trip_store (Request $req, Business_trip $business_trip) 
    {
        $req["user_id"] = auth()->id(); // เพิ่ม user_id ลงในข้อมูลที่ส่งมา
        if(!$req->id)
        {
             $business_trip->create($req->all());
        }else{
            if($req->status == null){
                $req["status"] = 1; // ถ้าไม่ได้ส่งค่า status มา ให้ตั้งเป็น 1 (active)
            }
            $business_trip = Business_trip::findOrFail($req->id);
            $business_trip->update($req->all());
        }
       
        return redirect()->route('business_trip.index')->with('success', 'ดำเนินการสำเร็จ');
    }

    public function business_trip_edit($id) {
       $calendarEvent  = Business_trip::findOrFail($id);
        return view('admin.business_trip.edit', compact('calendarEvent'));
    }

    public function business_trip_destroy($id)
    {
        $business_trip = Business_trip::findOrFail($id);
        $business_trip->delete();
        return redirect()->back()->with('success', 'ลบข้อมูลเรียบร้อยแล้ว');
    }

    
}
