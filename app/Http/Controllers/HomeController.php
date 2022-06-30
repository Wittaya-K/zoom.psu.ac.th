<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {   
        $date_time_now = Carbon::now();
        $datetimenow = date('Y-m-d', strtotime($date_time_now->toDateTimeString())); //แปลงวันที่เวลาเป็นวันที่อย่างเดียว

        $bookings = DB::table('bookings as book')
                        ->select(DB::raw('DATE_FORMAT(book.time_to, "%Y-%m-%d") as formatted_dob'))
                        ->distinct()
                        ->whereDate('book.time_to','>=',$datetimenow)
                        ->get(); //ดึงข้อมูลเฉพาะวันที่มีการจองและไม่ซ้ำกัน

        $zooms = DB::table('zooms')->where('category_id', '=', 1)->get();
        
        $oldbookings = DB::table('bookings as book')
        ->select(DB::raw('DATE_FORMAT(book.time_to, "%Y-%m-%d") as formatted_dob'))
        ->distinct()
        ->whereDate('book.time_to','<',$datetimenow)
        ->get(); //ดึงข้อมูลเฉพาะวันที่มีการจองและไม่ซ้ำกัน
        
        $day = date("d-m-Y"); //คำนวณวันเดือนปี หาจำนวนวันของเดือนนั้น ๆ
        $date = explode("-", $day);

        if ($date[1]=="2")
        {
            if($date[2] % 4 ==0)
            {
                // echo "day = 29";
                $daycount = 29;
            }
            else
            {
                // echo "day = 28";
                $daycount = 28;
            }
            
        }
        elseif($date[1]=="4" || $date[1]=="6" || $date[1]=="9" || $date[1]=="11")
        {
            // echo "day = 30";
            $daycount = 30;
        }
        else
        {
            // echo "day = 31";
            $daycount = 31;
        }

        return view('home',compact('bookings','zooms','daycount','oldbookings'));
    }
}
