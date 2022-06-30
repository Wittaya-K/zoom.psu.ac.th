@extends('layouts.app')

@section('content')

    <script src="https://unpkg.com/sweetalert@2.1.2/dist/sweetalert.min.js"></script>
    @if (Session::get('booked'))
        <script>
            swal("บันทึกสำเร็จ", "จองบัญชีใช้งานเรียบร้อยแล้ว", "success");
        </script>
    @endif

    @if (Session::get('duplicated'))
        <script>
            swal("แจ้งเตือน", "เวลาที่ท่านระบุมีการจองแล้ว", "warning");
        </script>
    @endif

    <h3 class="page-title">การจองบัญชีใช้งาน</h3>
    {{-- {!! Form::open(['method' => 'POST', 'route' => ['admin.bookings.store']]) !!} --}}
    {!! Form::open(array('route' => 'admin.bookings.store','method'=>'POST', 'id' => 'form')) !!}

    @if ($message = Session::get('success'))
        <div class="alert alert-success alert-block">
            <button type="button" class="close" data-dismiss="alert">×</button>
            <strong>{{ $message }}</strong>
        </div>
    @endif


    @if ($message = Session::get('error'))
        <div class="alert alert-danger alert-block">
            <button type="button" class="close" data-dismiss="alert">×</button>
            <strong>{{ $message }}</strong>
        </div>
    @endif


    @if ($message = Session::get('warning'))
        <div class="alert alert-warning alert-block">
            <button type="button" class="close" data-dismiss="alert">×</button>
            <strong>{{ $message }}</strong>
        </div>
    @endif


    @if ($message = Session::get('info'))
        <div class="alert alert-info alert-block">
            <button type="button" class="close" data-dismiss="alert">×</button>
            <strong>{{ $message }}</strong>
        </div>
    @endif


    @if ($errors->any())
        <div class="alert alert-danger">
            <button type="button" class="close" data-dismiss="alert">×</button>
            Please check the form below for errors
        </div>
    @endif

    <div class="panel panel-default">
        <div class="panel-heading">
            จองบัญชี
        </div>

        <div class="panel-body">
            <div class="row">
                <div class="col-xs-12 form-group">
                    <label for="user_name" class="control-label">ผู้ใช้งาน</label>
                    <div class="input-group">
                        <span class="input-group-addon"><i class="fa fa-user"></i></span>
                        <input type="text" name="user_name" id="user_name" value="{{ auth()->user()->username }}" class="form-control" readonly>
                    </div>
                    <input type="hidden" name="customer_id" id="customer_id" value="1" class="form-control">
                    <input type="hidden" name="status_approve" id="status_approve" value="ไม่อนุมัติ" class="form-control">
                    <p class="help-block"></p>
                    @if ($errors->has('customer_id'))
                        <p class="help-block">
                            {{ $errors->first('customer_id') }}
                        </p>
                    @endif
                </div>
            </div>

            <div class="row">
                <div class="col-xs-12 form-group">
                    <label for="zoom_email" class="control-label">Zoom Email</label>
                    <div class="input-group">
                        <span class="input-group-addon"><i class="fa fa-envelope"></i></span>
                        <select class="form-control select2" name="zoom_email" id="zoom_email" required>
                            <option>เลือก</option>
                            @foreach ($zooms as $zoom)
                            <option value="{{ $zoom->zoom_email }}">{{ $zoom->zoom_email }}</option>
                            @endforeach
                          </select>
                    </div>
                    <p class="help-block"></p>
                    @if ($errors->has('zoom_email'))
                        <p class="help-block">
                            {{ $errors->first('zoom_email') }}
                        </p>
                    @endif
                </div>
            </div>

            <div class="row">
                <div class="col-xs-12 form-group">
                    <label for="time_from" class="control-label">ตั้งแต่วันที่</label>
                    <div class="input-group">
                        <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                        <input type="text" name="time_from" id="datetimepicker_mask_from" class="form-control" required>
                    </div>
                    <p class="help-block"></p>
                    @if ($errors->has('time_from'))
                        <p class="help-block">
                            {{ $errors->first('time_from') }}
                        </p>
                    @endif
                </div>
            </div>
            <div class="row">
                <div class="col-xs-12 form-group">
                    <label for="time_to" class="control-label">ถึงวันที่</label>
                    <div class="input-group">
                        <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                        <input type="text" name="time_to" id="datetimepicker_mask_to" class="form-control" required>
                    </div>
                    <p class="help-block"></p>
                    @if ($errors->has('time_to'))
                        <p class="help-block">
                            {{ $errors->first('time_to') }}
                        </p>
                    @endif
                </div>
            </div>
            <div class="row">
                <div class="col-xs-12 form-group">
                    <label for="time_to" class="control-label">รายละเอียดเพิ่มเติม</label>
                    <div class="input-group">
                        <span class="input-group-addon"><i class="fa fa-file-text"></i></span>
                        <textarea name="additional_information" id="additional_information" cols="30" rows="10" class="form-control" required></textarea>
                    </div>
                    <p class="help-block"></p>
                    @if ($errors->has('additional_information'))
                        <p class="help-block">
                            {{ $errors->first('additional_information') }}
                        </p>
                    @endif
                </div>
            </div>

        </div>
    </div>

    {{-- {!! Form::submit(trans('บันทึก'), ['class' => 'btn btn-danger']) !!} --}}
    <button type="submit" class="btn btn-danger"><i class="fa fa-floppy-o" aria-hidden="true"></i> บันทึก</button>
    {!! Form::close() !!}
@stop

@section('javascript')
    @parent
    <script src="https://cdn.datatables.net/select/1.2.0/js/dataTables.select.min.js"></script>
    {{-- <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/css/bootstrap-datetimepicker.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.20.1/moment.min.js"></script>
    <script
        src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/js/bootstrap-datetimepicker.min.js">
    </script> --}}
    <link rel="stylesheet" href="{{ url('adminlte/plugins/datetimepicker/jquery.datetimepicker.min.css') }}">
    <script src="{{ url('adminlte/plugins/datetimepicker/jquery.datetimepicker.full.js') }}"></script>
    <script>

        // $('.datetimepicker').datetimepicker({
        //     format: "YYYY-MM-DD HH:mm"
        // });
        // $('#datetimepicker').datetimepicker({
        // // options here
        // format: 'Y-m-d H:i',
        // formatTime: 'H:i',
        // formatDate: 'Y-m-d',
        // });
        $.datetimepicker.setLocale('th');
        $('#datetimepicker_mask_from').datetimepicker({
            mask:'0000-00-00 00:00',
            format: 'Y-m-d H:i',
            formatTime: 'H:i',
            formatDate: 'Y-m-d',
            step:30, //กำหนดค่านาทีของเวลา
            lang:'th' //กำหนดค่าเป็นภาษาไทย
        });
        $('#datetimepicker_mask_to').datetimepicker({
            mask:'0000-00-00 00:00',
            format: 'Y-m-d H:i',
            formatTime: 'H:i',
            formatDate: 'Y-m-d',
            step:30, //กำหนดค่านาทีของเวลา
            lang:'th' //กำหนดค่าเป็นภาษาไทย
        });
    </script>
@stop
