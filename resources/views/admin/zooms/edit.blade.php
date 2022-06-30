@extends('layouts.app')

@section('content')

    <script src="https://unpkg.com/sweetalert@2.1.2/dist/sweetalert.min.js"></script>
    @if (Session::get('saved'))
        <script>
            swal("บันทึกสำเร็จ", "แก้ไขรายการบัญชีใช้งานเรียบร้อยแล้ว", "success");
        </script>
    @endif

    <h3 class="page-title">แก้ไขรายการบัญชีใช้งาน</h3>
    
    {!! Form::model($zoom, ['method' => 'PUT', 'route' => ['admin.zooms.update', $zoom->id]]) !!}

    <div class="panel panel-default">
        <div class="panel-heading">
            แก้ไข
        </div>

        <div class="panel-body">
            <div class="row">
                <div class="col-xs-12 form-group">
                    {!! Form::label('zoom_email', trans('Zoom Email').'*', ['class' => 'control-label']) !!}
                    {{-- {!! Form::text('zoom_email', old('zoom_email'), ['class' => 'form-control', 'placeholder' => '', 'required' => '']) !!} --}}
                    {!! Form::email('zoom_email', old('zoom_email'), ['class' => 'form-control', 'placeholder' => '', 'required' => '']) !!}
                    <p class="help-block"></p>
                    @if($errors->has('zoom_email'))
                        <p class="help-block">
                            {{ $errors->first('zoom_email') }}
                        </p>
                    @endif
                </div>
            </div>

            <div class="row">
                <div class="col-xs-12 form-group">
                    {!! Form::label('password', trans('Zoom Password').'*', ['class' => 'control-label']) !!}
                    {!! Form::text('password', old('password'), ['class' => 'form-control', 'placeholder' => '', 'required' => '']) !!}
                    <p class="help-block"></p>
                    @if($errors->has('password'))
                        <p class="help-block">
                            {{ $errors->first('password') }}
                        </p>
                    @endif
                </div>
            </div>

            <div class="row">
                <div class="col-xs-12 form-group">
                    {!! Form::label('zoom_number', trans('Zoom ID').'*', ['class' => 'control-label']) !!}
                    {!! Form::text('zoom_number', old('zoom_number'), ['class' => 'form-control', 'placeholder' => '', 'required' => '']) !!}
                    <p class="help-block"></p>
                    @if($errors->has('zoom_number'))
                        <p class="help-block">
                            {{ $errors->first('zoom_number') }}
                        </p>
                    @endif
                </div>
            </div>

            <div class="row">
                <div class="col-xs-12 form-group">
                    {!! Form::label('category_id', trans('ประเภทบัญชีผู้ใช้งาน').'', ['class' => 'control-label']) !!}
                    {!! Form::select('category_id', $categories, old('category_id'), ['class' => 'form-control select2']) !!}
                    <p class="help-block"></p>
                    @if($errors->has('category_id'))
                        <p class="help-block">
                            {{ $errors->first('category_id') }}
                        </p>
                    @endif
                </div>
            </div>

            <div class="row">
                <div class="col-xs-12 form-group">
                    {!! Form::label('expire_at', trans('วันหมดอายุ').'*', ['class' => 'control-label']) !!}
                    {{-- {!! Form::text('expire_at', old('expire_at'), ['class' => 'form-control datetimepicker', 'placeholder' => '', 'required' => '']) !!} --}}
                    {!! Form::text('expire_at', old('expire_at'), ['class' => 'form-control datetimepicker', 'placeholder' => '', 'required' => '', 'id' => 'datetimepicker_expire_at']) !!}
                    <p class="help-block"></p>
                    @if($errors->has('expire_at'))
                        <p class="help-block">
                            {{ $errors->first('expire_at') }}
                        </p>
                    @endif
                </div>
            </div>

            <div class="row">
                <div class="col-xs-12 form-group">
                    {!! Form::label('description', trans('ลิงค์เข้าใช้งาน').'*', ['class' => 'control-label']) !!}
                    {!! Form::textarea('description', old('description'), ['class' => 'form-control ', 'placeholder' => '', 'required' => '']) !!}
                    <p class="help-block"></p>
                    @if($errors->has('description'))
                        <p class="help-block">
                            {{ $errors->first('description') }}
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
    {{-- <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/css/bootstrap-datetimepicker.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.20.1/moment.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/js/bootstrap-datetimepicker.min.js"></script> --}}
    <link rel="stylesheet" href="{{ url('adminlte/plugins/datetimepicker/jquery.datetimepicker.min.css') }}">
    <script src="{{ url('adminlte/plugins/datetimepicker/jquery.datetimepicker.full.js') }}"></script>
    <script>
        // $('.datetimepicker').datetimepicker({
        //     format: "YYYY-MM-DD HH:mm"
        // });
        $('#datetimepicker_expire_at').datetimepicker({
            mask:'9999-19-39 29:59',
            format: 'Y-m-d H:i',
            formatTime: 'H:i',
            formatDate: 'Y-m-d',
        });
    </script>
@stop
