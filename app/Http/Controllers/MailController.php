<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Booking;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
class MailController extends Controller
{
    public static function simpleDateFormat($arg)
    {
        $thai_months = [
            1 => 'มกราคม',
            2 => 'กุมภาพันธ์',
            3 => 'มีนาคม',
            4 => 'เมษายน',
            5 => 'พฤษภาคม',
            6 => 'มิถุนายน',
            7 => 'กรกฎาคม',
            8 => 'สิงหาคม',
            9 => 'กันยายน',
            10 => 'ตุลาคม',
            11 => 'พฤศจิกายน',
            12 => 'ธันวาคม',
        ];
        $date = Carbon::parse($arg);
        $month = $thai_months[$date->month];
        $year = $date->year + 543;
        // return $date->format("j $month $year H:i:s");
        return $date->format("j $month $year");
    }

    public static function DateThai($strDate)
	{
		$strYear = date("Y",strtotime($strDate))+543;
		$strMonth= date("n",strtotime($strDate));
		$strDay= date("j",strtotime($strDate));
		$strHour= date("H",strtotime($strDate));
		$strMinute= date("i",strtotime($strDate));
		$strSeconds= date("s",strtotime($strDate));
		$strMonthCut = Array("","ม.ค.","ก.พ.","มี.ค.","เม.ย.","พ.ค.","มิ.ย.","ก.ค.","ส.ค.","ก.ย.","ต.ค.","พ.ย.","ธ.ค.");
		$strMonthThai=$strMonthCut[$strMonth];
        $timesplit = "เวลา";
		return "$strDay $strMonthThai $strYear $timesplit $strHour:$strMinute";
	}

    public function txt_mail($user_name)
    {
        $info = array(
            'name' => "Alex"
        );

        Mail::send([], [], function ($message) use ($user_name)
        {
            $bookings = DB::table('bookings')->where('user_name', '=', $user_name)->get();
            foreach ($bookings as $booking) 
            {
                
                $booking->user_name;
                $zooms = DB::table('zooms')->where('zoom_number', '=', $booking->zoom_number)->get();
                $users = DB::table('users')->where('username', '=', $booking->user_name)->get(); //dd($users);

                $time_from = MailController::DateThai($booking->time_from);
                $time_to = MailController::DateThai($booking->time_to);

                foreach ($zooms as $zoom) {
                    foreach ($users as $user) {
                        $zoom->zoom_number;
                        $zoom->password;

                        $html = 'การขอใช้บัญชี ZOOM <br>
                        เรียนคุณ '. $user->fullname .'<br>
                        คุณได้ทำการขอใช้บัญชี ZOOM ตั้งแต่วันที่ '. $time_from .' ถึงวันที่ '. $time_to .'<br>
                        
                        บัญชีผู้ใช้งานของคุณ <br>
                        Zoom Email: '.$zoom->zoom_email.' <br>
                        Password: '.$zoom->password.' <br>
                        Zoom ID: '.$zoom->zoom_number.' <br>
                        ลิงค์เข้าใช้งาน: '.$zoom->description.'<br>
                        <br><br>
                        คณะวิทยาศาสตร์ (หน่วยเทคโนโลยีการศึกษา) <br>
                        โทร: 074-28-8104';
                        $message->to($user->email, $user->fullname) //ดึงมาจากเมล์ของ Psu Passport
                            ->subject('ยืนยันการใช้งานบัญชี Zoom');
                        $message->from('wittaya.kh@psu.ac.th', 'ระบบจองบัญชีใช้งาน');
                        $message->setBody($html,'text/html');
                    }
                }
                }
        });
    }

    // public function html_mail()
    // {
    //     $info = array(
    //         'name' => "Alex"
    //     );
    //     Mail::send('mail', $info, function ($message)
    //     {
    //         $message->to('alex@example.com', 'w3schools')
    //             ->subject('HTML test eMail from W3schools.');
    //         $message->from('karlosray@gmail.com', 'Alex');
    //     });
    //     echo "Successfully sent the email";
    // }

    // public function attached_mail()
    // {

    //     $info = array(
    //         'name' => "Alex"
    //     );
    //     Mail::send('mail', $info, function ($message)
    //     {
    //         $message->to('alex@example.com', 'w3schools')
    //             ->subject('Test eMail with an attachment from W3schools.');
    //         $message->attach('D:\laravel_main\laravel\public\uploads\pic.jpg');
    //         $message->attach('D:\laravel_main\laravel\public\uploads\message_mail.txt');
    //         $message->from('karlosray@gmail.com', 'Alex');
    //     });
    //     echo "Successfully sent the email";
    // }
}
