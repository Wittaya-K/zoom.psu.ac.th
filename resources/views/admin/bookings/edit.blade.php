@extends('layouts.app')

@section('content')
    <script src="https://unpkg.com/sweetalert@2.1.2/dist/sweetalert.min.js"></script>
    @if (Session::get('booked'))
        <script>
            swal("บันทึกสำเร็จ", "จองบัญชีใช้งานเรียบร้อยแล้ว", "success");
        </script>
    @endif

    <h3 class="page-title">แก้ไขรายการจองบัญชีใช้งาน</h3>
    
    {!! Form::model($booking, ['method' => 'PUT', 'route' => ['admin.bookings.update', $booking->id]]) !!}

    <div class="panel panel-default">
        <div class="panel-heading">
            แก้ไข
        </div>

        <div class="panel-body">
            <div class="row">
                <div class="col-xs-12 form-group">
                    <label for="user_name" class="control-label">ผู้ใช้งาน</label>
                    <div class="input-group">
                        <span class="input-group-addon"><i class="fa fa-user"></i></span>
                        <input type="text" name="user_name" id="user_name" value="{{ $booking->user_name }}" class="form-control" readonly>
                    </div>
                    <input type="hidden" name="customer_id" id="customer_id" value="1" class="form-control">
                    <p class="help-block"></p>
                    @if($errors->has('user_name'))
                        <p class="help-block">
                            {{ $errors->first('user_name') }}
                        </p>
                    @endif
                </div>
            </div>

            <div class="row">
                <div class="col-xs-12 form-group">
                    <label for="zoom_email" class="control-label">Zoom Email</label>
                    <div class="input-group">
                        <span class="input-group-addon"><i class="fa fa-envelope"></i></span>
                        {!! Form::select('zoom_email', $zooms, old('zoom_email'), ['class' => 'form-control select2']) !!}
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
                        {!! Form::text('time_from', old('time_from'), ['class' => 'form-control', 'placeholder' => '', 'required' => '', 'id' => 'datetimepicker_mask_from']) !!}
                    </div>
                    <p class="help-block"></p>
                    @if($errors->has('time_from'))
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
                        {!! Form::text('time_to', old('time_to'), ['class' => 'form-control datetimepicker', 'placeholder' => '', 'required' => '', 'id' => 'datetimepicker_mask_to']) !!}
                    </div>
                    <p class="help-block"></p>
                    @if($errors->has('time_to'))
                        <p class="help-block">
                            {{ $errors->first('time_to') }}
                        </p>
                    @endif
                </div>
            </div>

            <div class="row">
                <div class="col-xs-12 form-group">
                    <label for="">คำขอ</label>
                    <div class="input-group">
                        <span class="input-group-addon"><i class="fa fa-check-square"></i></span>
                    <select name="status_approve" id="status_approve" class="form-control select2">
                        <option value="">เลือก</option>
                        @foreach ($selecteds as $selected)
                            @if ($selected->status_approve == 'อนุมัติ')
                                <option value="อนุมัติ" selected>อนุมัติ</option>
                                <option value="ไม่อนุมัติ">ไม่อนุมัติ</option>
                            @else
                                <option value="อนุมัติ">อนุมัติ</option>
                                <option value="ไม่อนุมัติ" selected>ไม่อนุมัติ</option>
                            @endif
                        @endforeach
                    </select>
                    </div>
                    <p class="help-block"></p>
                    @if($errors->has('status_approve'))
                        <p class="help-block">
                            {{ $errors->first('status_approve') }}
                        </p>
                    @endif
                </div>
            </div>

            <div class="row">
                <div class="col-xs-12 form-group">
                    {{-- {!! Form::label('additional_information', trans('รายละเอียดเพิ่มเติม').'*', ['class' => 'control-label']) !!} --}}
                    <label for="time_to" class="control-label">รายละเอียดเพิ่มเติม</label>
                    <div class="input-group">
                        <span class="input-group-addon"><i class="fa fa-file-text"></i></span>
                        {!! Form::textarea('additional_information', old('additional_information'), ['class' => 'form-control ', 'placeholder' => '', 'required' => '']) !!}
                    </div>
                    <p class="help-block"></p>
                    @if($errors->has('additional_information'))
                        <p class="help-block">
                            {{ $errors->first('additional_information') }}
                        </p>
                    @endif
                </div>
            </div>

        </div>
    </div>
    <button type="submit" class="btn btn-danger"><i class="fa fa-floppy-o" aria-hidden="true"></i> บันทึก</button>
    {{-- {!! Form::submit(trans('บันทึก'), ['class' => 'btn btn-danger']) !!} --}}
    {!! Form::close() !!}
@stop

@section('javascript')
    @parent
    <script src="https://cdn.datatables.net/select/1.2.0/js/dataTables.select.min.js"></script>
    {{-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/css/bootstrap-datetimepicker.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.20.1/moment.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/js/bootstrap-datetimepicker.min.js"></script> --}}
    <link rel="stylesheet" href="{{ url('adminlte/plugins/datetimepicker/jquery.datetimepicker.min.css') }}">
    <script src="{{ url('adminlte/plugins/datetimepicker/jquery.datetimepicker.full.js') }}"></script>
    <script>
        // $('.datetimepicker').datetimepicker({
        //     format: "YYYY-MM-DD HH:mm"
        // });
        $.datetimepicker.setLocale('th');
        $('#datetimepicker_mask_from').datetimepicker({
            // mask:'0000-00-00 00:00',
            format: 'Y-m-d H:i',
            formatTime: 'H:i',
            formatDate: 'Y-m-d',
            step:30, //กำหนดค่านาทีของเวลา
            lang:'th' //กำหนดค่าเป็นภาษาไทย
        });
        $('#datetimepicker_mask_to').datetimepicker({
            // mask:'0000-00-00 00:00',
            format: 'Y-m-d H:i',
            formatTime: 'H:i',
            formatDate: 'Y-m-d',
            step:30, //กำหนดค่านาทีของเวลา
            lang:'th' //กำหนดค่าเป็นภาษาไทย
        });
    </script>
@stop