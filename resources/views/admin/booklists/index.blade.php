@inject('request', 'Illuminate\Http\Request')
@extends('layouts.app')

@section('content')

    <style type="text/css">
        .back-link a {
            color: #4ca340;
            text-decoration: none;
            border-bottom: 1px #4ca340 solid;
        }

        .back-link a:hover,
        .back-link a:focus {
            color: #408536;
            text-decoration: none;
            border-bottom: 1px #408536 solid;
        }

        h1 {
            height: 100%;
            /* The html and body elements cannot have any padding or margin. */
            margin: 0;
            font-size: 14px;
            font-family: 'Open Sans', sans-serif;
            font-size: 32px;
            margin-bottom: 3px;
        }

        .entry-header {
            text-align: left;
            margin: 0 auto 50px auto;
            width: 80%;
            max-width: 978px;
            position: relative;
            z-index: 10001;
        }

        #demo-content {
            /* padding-top: 100px; */
        }

    </style>

    @if ($message = Session::get('success'))
        <div class="alert alert-success alert-block">
            <button type="button" class="close" data-dismiss="alert">×</button>
            <strong>{{ $message }}</strong>
        </div>
    @endif
    
    <h3 class="page-title">การจองบัญชีใช้งาน</h3>
    @can('booking_create')
        <p>
            <a href="{{ route('admin.bookings.create') }}" class="btn btn-success"><i class="fa fa-plus-square"
                    aria-hidden="true"></i> จองบัญชี</a>

        </p>
    @endcan

    @can('booking_delete')
        <p>
        <ul class="list-inline">
            <li><a href="{{ route('admin.bookings.index') }}"
                    style="{{ request('show_deleted') == 1 ? '' : 'font-weight: 700' }}">@lang('quickadmin.qa_all')</a></li> |
            <li><a href="{{ route('admin.bookings.index') }}?show_deleted=1"
                    style="{{ request('show_deleted') == 1 ? 'font-weight: 700' : '' }}">@lang('quickadmin.qa_trash')</a></li>
        </ul>
        </p>
    @endcan

    <!-- Demo content -->
    <div id="demo-content">

        <div id="loader-wrapper">
            <div id="loader"></div>

            <div class="loader-section section-left"></div>
            <div class="loader-section section-right"></div>

        </div>

        <div class="panel panel-default">
            <div class="panel-heading">
                รายการ
            </div>
    
            <div class="panel-body table-responsive">
                <table
                    class="table table-hover  @can('booking_delete') @if (request('show_deleted') != 1) dt-select @endif @endcan">
                    <thead class="bg-info">
                        <tr>
                            {{-- @can('booking_delete')
                                @if (request('show_deleted') != 1)
                                    <th style="text-align:center;"><input type="checkbox" id="select-all" /></th>
                                @endif
                            @endcan --}}
    
                            <th style="text-align: center;">ผู้ใช้งาน</th>
                            <th style="text-align: center;">Zoom Email</th>
                            <th style="text-align: center;">Zoom ID</th>
                            <th style="text-align: center;">รายละเอียดเพิ่มเติม</th>
                            <th style="text-align: center;">ตั้งแต่วันที่</th>
                            <th style="text-align: center;">ถึงวันที่</th>
                            <th style="text-align: center;">คำขอ</th>
                            @if (request('show_deleted') == 1)
                                <th>&nbsp;</th>
                            @else
                                <th>&nbsp;</th>
                            @endif
                        </tr>
                    </thead>
    
                    <tbody>
                        @if (count($bookings) > 0)
                            @foreach ($bookings as $booking)
                                <tr data-entry-id="{{ $booking->id }}">
                                    {{-- @can('booking_delete')
                                        @if (request('show_deleted') != 1)
                                            <td></td>
                                        @endif
                                    @endcan --}}
    
                                    
                                    <td field-key='customer' style="text-align: center;">{{ $booking->user_name }}</td>
                                    <td field-key='email' style="text-align: center;">{{ $booking->zoom_email }}</td>
                                    <td field-key='zoom' style="text-align: center;">{{ $booking->zoom_number }}</td>
                                    <td field-key='additional_information'>{!! $booking->additional_information !!}</td>
                                    <td field-key='time_from' style="text-align: center;" class="bg-success">{{ $booking->time_from }}</td>
                                    <td field-key='time_to' style="text-align: center;" class="bg-danger">{{ $booking->time_to }}</td>
                                    
                                    @if ($booking->status_approve == 'อนุมัติ')
                                        <td field-key='name' style="text-align: center;"><small
                                                class="label pull-center bg-green">{{ $booking->status_approve }}</small>
                                        </td>
                                    @else
                                        <td field-key='name' style="text-align: center;"><small
                                                class="label pull-center bg-red">{{ $booking->status_approve }}</small></td>
                                    @endif
                                    @if (request('show_deleted') == 1)
                                        <td>
                                            @can('booking_delete')
                                                {!! Form::open([
                                                    'style' => 'display: inline-block;',
                                                    'method' => 'POST',
                                                    'onsubmit' => "return confirm('" . trans('quickadmin.qa_are_you_sure') . "');",
                                                    'route' => ['admin.bookings.restore', $booking->id],
                                                ]) !!}
                                                {!! Form::submit(trans('quickadmin.qa_restore'), ['class' => 'btn btn-xs btn-success']) !!}
                                                {!! Form::close() !!}
                                            @endcan
                                            @can('booking_delete')
                                                {!! Form::open([
                                                    'style' => 'display: inline-block;',
                                                    'method' => 'DELETE',
                                                    'onsubmit' => "return confirm('" . trans('quickadmin.qa_are_you_sure') . "');",
                                                    'route' => ['admin.bookings.perma_del', $booking->id],
                                                ]) !!}
                                                {!! Form::submit(trans('quickadmin.qa_permadel'), ['class' => 'btn btn-xs btn-danger']) !!}
                                                {!! Form::close() !!}
                                            @endcan
                                        </td>
                                    @else
                                        @if (auth()->user()->name == 'Admin')
                                            {{-- เช็คสิทธ์ Admin --}}
                                            <td style="text-align: center;">
                                                {{-- @can('booking_edit')
                                                    <a href="{{ route('admin.bookings.edit', [$booking->id]) }}"
                                                        class="btn btn-xs btn-warning">@lang('quickadmin.qa_edit')</a>
                                                @endcan
                                                @can('booking_delete')
                                                    {!! Form::open([
                                                        'style' => 'display: inline-block;',
                                                        'method' => 'DELETE',
                                                        'onsubmit' => "return confirm('" . trans('quickadmin.qa_are_you_sure') . "');",
                                                        'route' => ['admin.bookings.destroy', $booking->id],
                                                    ]) !!}
                                                    {!! Form::submit(trans('quickadmin.qa_delete'), ['class' => 'btn btn-xs btn-danger']) !!}
                                                    {!! Form::close() !!}
                                                @endcan --}}
                                            </td>
                                        @else
                                            <td></td>
                                        @endif
                                    @endif
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="10">@lang('quickadmin.qa_no_entries_in_table')</td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>

    </div>
@stop

@section('javascript')
    <script>
        @can('booking_delete')
            @if (request('show_deleted') != 1)
                window.route_mass_crud_entries_destroy =
                '{{ route('admin.bookings.mass_destroy') }}';
            @endif
        @endcan
        setTimeout(function(){  $('#table').DataTable().ajax.reload();  },2000);
        
        setTimeout(function() {
            location.reload();
        }, 60 * 1000);
    </script>
@endsection
