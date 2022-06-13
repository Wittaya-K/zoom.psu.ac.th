<?php

namespace App\Http\Controllers\Admin;

use App\Booking;
use App\Customer;
use App\zoom;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreBookingsRequest;
use App\Http\Requests\Admin\UpdateBookingsRequest;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Session;

class BooklistsController extends Controller
{
    /**
     * Display a listing of Booking.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if (!Gate::allows('booklists_access')) {
            return abort(401);
        }

        // dd($request->date);
        $date = $request->date; //รับค่าวันที่
        if (request('show_deleted') == 1) {
            if (!Gate::allows('booklists_access')) {
                return abort(401);
            }
            $bookings = Booking::onlyTrashed()->get();
        } else {
            // $bookings = Booking::all();
            if(auth()->user()->name == 'Admin') //เช็คสิทธิ์ Admin
            {
                // $bookings = Booking::all();
                // $date_time_now = Carbon::now();
                // $date = date('Y-m-d', strtotime($date_time_now->toDateTimeString())); //แปลงวันที่เวลาเป็นวันที่อย่างเดียว

                $bookings = DB::table('bookings')
                // ->select('bookings.id','bookings.user_name','bookings.time_from','bookings.time_to','bookings.additional_information','bookings.status_approve','zooms.id','zooms.zoom_number','zooms.zoom_email','zooms.category_id','categories.name')
                // ->leftJoin('zooms', 'zooms.id', '=', 'bookings.id')
                // ->leftJoin('categories', 'categories.id', '=', 'zooms.category_id')
                ->whereDate('bookings.time_from','>=',$date)
                ->whereDate('bookings.time_to','<=',$date)
                ->orderBy('bookings.zoom_email')
                ->get();
            }else
            {
                $bookings = DB::table('bookings')->where('user_name', '=', auth()->user()->username)->get();
            }
            
        }

        return view('admin.booklists.index', compact('bookings'));
    }

    /**
     * Show the form for creating new Booking.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (!Gate::allows('booking_create')) {
            return abort(401);
        }

        $customers = Customer::get()->pluck('full_name', 'id')->prepend(trans('quickadmin.qa_please_select'), '');

        $bookings = DB::table('bookings')->get();
        // $zooms = DB::table('zooms')->where('category_id','=','1')->get();

        foreach ($bookings as $booking) {
            $zooms = DB::table('zooms')->where('zoom_email','!=',$booking->zoom_email)->get(); //เช็ค Email ที่มีการจองแล้ว
        }

        return view('admin.bookings.create', compact('customers', 'zooms'));
    }

    /**
     * Store a newly created Booking in storage.
     *
     * @param  \App\Http\Requests\StoreBookingsRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreBookingsRequest $request)
    {
        if (!Gate::allows('booking_create')) {
            return abort(401);
        }
        
        // $customer_id = $request->input('customer_id');
        $customer_id = 1;
        $zoom_email = $request->input('zoom_email');
        $time_from = $request->input('time_from');
        $time_to = $request->input('time_to');
        $additional_information = $request->input('additional_information');
        $user_name = $request->input('user_name');
        $status_approve = 'ไม่อนุมัติ';

        $bookings = DB::table('bookings')->get();

        if($bookings->isEmpty()) //เช็คข้อมูลใน database
        {
            $zooms = DB::table('zooms')->where('zoom_email', '=', $zoom_email)->get();
            foreach ($zooms as $zoom) 
            {
                $zoom_number = $zoom->zoom_number;
                $zoom_id = $zoom->id;
            }

            DB::insert('insert into bookings(time_from, time_to, additional_information, created_at, updated_at, deleted_at, customer_id, zoom_id, user_name, status_approve, zoom_number, zoom_email) values(?,?,?,?,?,?,?,?,?,?,?,?)',array($time_from,$time_to,$additional_information,now(),null,null,$customer_id,$zoom_id,$user_name,$status_approve,$zoom_number,$zoom_email));
            return redirect()->back()->with('booked', 'บันทึกสำเร็จ'); //แจ้งเตือน sweetalert
        }
        else
        {
            foreach ($bookings as $booking ) 
            {
                $t_f = date_create($time_from);
                $t_t = date_create($time_to);

                $time_f = date_format($t_f, 'Y-m-d H:i:s');
                $time_t = date_format($t_t, 'Y-m-d H:i:s');


                // check a timeperiod is overlapping another time period
                if($booking->time_from > $time_f && $booking->time_to < $time_t)
                {	#-> Check time is in between start and end time
                    // echo "1 Time is in between start and end time";
                    return redirect()->back()->with('duplicated', 'เวลาที่ท่านระบุมีการจองแล้ว');
                    // return redirect()->back()->with('error', 'เวลาที่ท่านระบุมีการจองแล้ว');
                }
                elseif(($booking->time_from > $time_f && $booking->time_from < $time_t) || ($booking->time_to > $time_f && $booking->time_to < $time_t))
                {	#-> Check start or end time is in between start and end time
                    // echo "2 ChK start or end Time is in between start and end time";
                    return redirect()->back()->with('duplicated', 'เวลาที่ท่านระบุมีการจองแล้ว');
                    // return redirect()->back()->with('error', 'เวลาที่ท่านระบุมีการจองแล้ว');
                }
                elseif($booking->time_from==$time_f || $booking->time_to==$time_t)
                {	#-> Check start or end time is at the border of start and end time
                    // echo "3 ChK start or end Time is at the border of start and end time";
                    return redirect()->back()->with('duplicated', 'เวลาที่ท่านระบุมีการจองแล้ว');
                    // return redirect()->back()->with('error', 'เวลาที่ท่านระบุมีการจองแล้ว');
                }
                elseif($time_f > $booking->time_from && $time_t < $booking->time_to)
                {	#-> start and end time is in between  the check start and end time.
                    // echo "4 start and end Time is overlapping  chk start and end time";
                    return redirect()->back()->with('duplicated', 'เวลาที่ท่านระบุมีการจองแล้ว');
                    // return redirect()->back()->with('error', 'เวลาที่ท่านระบุมีการจองแล้ว');
                }
            }
                    $zooms = DB::table('zooms')->where('zoom_email', '=', $zoom_email)->get();
                    foreach ($zooms as $zoom) 
                    {
                        $zoom_number = $zoom->zoom_number;
                        $zoom_id = $zoom->id;
                    }
        
                    DB::insert('insert into bookings(time_from, time_to, additional_information, created_at, updated_at, deleted_at, customer_id, zoom_id, user_name, status_approve, zoom_number, zoom_email) values(?,?,?,?,?,?,?,?,?,?,?,?)',array($time_from,$time_to,$additional_information,now(),null,null,$customer_id,$zoom_id,$user_name,$status_approve,$zoom_number,$zoom_email));
                    // return redirect()->back()->with('success', 'บันทึกสำเร็จ');
                    return redirect()->back()->with('booked', 'บันทึกสำเร็จ'); //แจ้งเตือน sweetalert
        }



        // $booking = Booking::create($request->all());

        return redirect()->route('admin.bookings.index');
    }


    /**
     * Show the form for editing Booking.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if (!Gate::allows('booking_edit')) {
            return abort(401);
        }

        $customers = Customer::get()->pluck('first_name', 'id')->prepend(trans('quickadmin.qa_please_select'), '');
        $zooms = Zoom::get()->pluck('zoom_email', 'zoom_email')->prepend(trans('quickadmin.qa_please_select'), '');

        $booking = Booking::findOrFail($id);

        $selecteds = DB::table('bookings')->where('id',$id)->get();

        return view('admin.bookings.edit', compact('booking', 'customers', 'zooms','selecteds'));
    }

    /**
     * Update Booking in storage.
     *
     * @param  \App\Http\Requests\UpdateBookingsRequest $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateBookingsRequest $request, $id)
    {
        if (!Gate::allows('booking_edit')) {
            return abort(401);
        }
        $booking = Booking::findOrFail($id);
        // $booking->update($request->all());
        // $id = $request->input('id');
        $date_time_now = Carbon::now();

        $time_from = $request->input('time_from');
        $time_to = $request->input('time_to');
        $additional_information = $request->input('additional_information');
        $created_at = $date_time_now->toDateTimeString();
        $updated_at = $date_time_now->toDateTimeString();
        $deleted_at = $request->input('deleted_at');
        $customer_id = $request->input('customer_id');
        $zoom_email = $request->input('zoom_email');
        $user_name = $request->input('user_name');
        $status_approve = $request->input('status_approve');
        $zoom_number = $booking->zoom_number;

        $zooms = DB::table('zooms')->where('zoom_email', '=', $zoom_email)->get();
        foreach ($zooms as $zoom) 
        {
            $zoom_id = $zoom->id;
        }

        if($status_approve == 'อนุมัติ')
        {
            DB::table('bookings')->where('id','=',$id)->update(array(
                'time_from'=>$time_from,
                'time_to'=>$time_to,
                'additional_information'=>$additional_information,
                'created_at'=>$created_at,
                'updated_at'=>$updated_at,
                'deleted_at'=>$deleted_at,
                'customer_id'=>$customer_id,
                'zoom_id'=>$zoom_id,
                'user_name'=>$user_name,
                'status_approve'=>$status_approve,
                'zoom_number'=>$zoom_number,
                'zoom_email'=>$zoom_email,
            ));
            DB::table('zooms')->where('zoom_number','=',$zoom_number)->update(array(
                'category_id'=>2,
            ));

            app('App\Http\Controllers\MailController')->txt_mail($user_name); //เรียก function จาก controller อื่น
        }
        else
        {
            DB::table('bookings')->where('id',$id)->update(array(
                'time_from'=>$time_from,
                'time_to'=>$time_to,
                'additional_information'=>$additional_information,
                'created_at'=>$created_at,
                'updated_at'=>$updated_at,
                'deleted_at'=>$deleted_at,
                'customer_id'=>$customer_id,
                'zoom_id'=>$zoom_id,
                'user_name'=>$user_name,
                'status_approve'=>$status_approve,
                'zoom_number'=>$zoom_number,
                'zoom_email'=>$zoom_email,
            ));
            DB::table('zooms')->where('zoom_number','=',$zoom_number)->update(array(
                'category_id'=>1,
            ));
        }
        return redirect()->back()->with('booked', 'บันทึกสำเร็จ'); //แจ้งเตือน sweetalert
        return redirect()->route('admin.bookings.index');
    }


    /**
     * Display Booking.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        if (!Gate::allows('booking_view')) {
            return abort(401);
        }
        $booking = Booking::findOrFail($id);

        return view('admin.bookings.show', compact('booking'));
    }


    /**
     * Remove Booking from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if (!Gate::allows('booking_delete')) {
            return abort(401);
        }
        $booking = Booking::findOrFail($id);
        $booking->delete();

        return redirect()->route('admin.bookings.index');
    }

    /**
     * Delete all selected Booking at once.
     *
     * @param Request $request
     */
    public function massDestroy(Request $request)
    {
        if (!Gate::allows('booking_delete')) {
            return abort(401);
        }
        if ($request->input('ids')) {
            $entries = Booking::whereIn('id', $request->input('ids'))->get();

            foreach ($entries as $entry) {
                $entry->delete();
            }
        }
    }


    /**
     * Restore Booking from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function restore($id)
    {
        if (!Gate::allows('booking_delete')) {
            return abort(401);
        }
        $booking = Booking::onlyTrashed()->findOrFail($id);
        $booking->restore();

        return redirect()->route('admin.bookings.index');
    }

    /**
     * Permanently delete Booking from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function perma_del($id)
    {
        if (!Gate::allows('booking_delete')) {
            return abort(401);
        }
        $booking = Booking::onlyTrashed()->findOrFail($id);
        $booking->forceDelete();

        return redirect()->route('admin.bookings.index');
    }
}
