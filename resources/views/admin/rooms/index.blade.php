@inject('request', 'Illuminate\Http\Request')
@extends('layouts.app')

@section('content')
    <h3 class="page-title">รายการบัญชีใช้งาน</h3>
    @can('room_create')
    <p>
        <a href="{{ route('admin.rooms.create') }}" class="btn btn-success"><i class="fa fa-plus-square" aria-hidden="true"></i> เพิ่มบัญชี</a>
        
    </p>
    @endcan

    @can('room_delete')
    <p>
        <ul class="list-inline">
            <li><a href="{{ route('admin.rooms.index') }}" style="{{ request('show_deleted') == 1 ? '' : 'font-weight: 700' }}">@lang('quickadmin.qa_all')</a></li> |
            <li><a href="{{ route('admin.rooms.index') }}?show_deleted=1" style="{{ request('show_deleted') == 1 ? 'font-weight: 700' : '' }}">@lang('quickadmin.qa_trash')</a></li>
        </ul>
    </p>
    @endcan

    @if(session()->has('message'))
    <div class="alert alert-success">
        {{ session()->get('message') }}
    </div>
    @endif

    <div class="panel panel-default">
        <div class="panel-heading">
            รายการ
        </div>

        <div class="panel-body table-responsive">
            <table class="table table-hover {{ count($rooms) > 0 ? 'datatable' : '' }} @can('room_delete') @if ( request('show_deleted') != 1 ) dt-select @endif @endcan">
                <thead>
                    <tr>
                        @can('room_delete')
                            @if ( request('show_deleted') != 1 )<th style="text-align:center;"><input type="checkbox" id="select-all" /></th>@endif
                        @endcan

                        <th style="text-align: center;">Zoom ID</th>
                        <th style="text-align: center;">Zoom Password</th>
                        <th>รายละเอียดเพิ่มเติม</th>
                        <th style="text-align: center;">สถานะ</th>
                        <th style="text-align: center;">วันหมดอายุ</th>
                        @if( request('show_deleted') == 1 )
                        <th>&nbsp;</th>
                        @else
                        <th>&nbsp;</th>
                        @endif
                    </tr>
                </thead>
                
                <tbody>
                    @if (count($rooms) > 0)
                        @foreach ($rooms as $room)
                            <tr data-entry-id="{{ $room->id }}">
                                @can('room_delete')
                                    @if ( request('show_deleted') != 1 )<td></td>@endif
                                @endcan

                                <td field-key='room_number' style="text-align: center;">{{ $room->room_number }}</td>
                                <td field-key='floor' style="text-align: center;">{{ $room->floor }}</td>
                                <td field-key='description'>{!! $room->description !!}</td>
                                @if($room->name == 'ว่าง')
                                <td field-key='category_id ' style="text-align: center;"><small class="label pull-center bg-green">{{ $room->name }}</small></td>
                                @else
                                <td field-key='category_id ' style="text-align: center;"><small class="label pull-center bg-red">{{ $room->name }}</small></td>
                                @endif
                                <td field-key='expire_at' style="text-align: center;">{{ $room->expire_at }}</td>
                                @if( request('show_deleted') == 1 )
                                <td>
                                    @can('room_delete')
                                                                        {!! Form::open(array(
                                        'style' => 'display: inline-block;',
                                        'method' => 'POST',
                                        'onsubmit' => "return confirm('".trans("quickadmin.qa_are_you_sure")."');",
                                        'route' => ['admin.rooms.restore', $room->id])) !!}
                                    {!! Form::submit(trans('quickadmin.qa_restore'), array('class' => 'btn btn-xs btn-success')) !!}
                                    {!! Form::close() !!}
                                @endcan
                                    @can('room_delete')
                                                                        {!! Form::open(array(
                                        'style' => 'display: inline-block;',
                                        'method' => 'DELETE',
                                        'onsubmit' => "return confirm('".trans("quickadmin.qa_are_you_sure")."');",
                                        'route' => ['admin.rooms.perma_del', $room->id])) !!}
                                    {!! Form::submit(trans('quickadmin.qa_permadel'), array('class' => 'btn btn-xs btn-danger')) !!}
                                    {!! Form::close() !!}
                                @endcan
                                </td>
                                @else
                                <td style="text-align: center;">
                                    @can('room_view')
                                    <a href="{{ route('admin.rooms.show',[$room->id]) }}" class="btn btn-xs btn-primary">@lang('quickadmin.qa_view')</a>
                                    @endcan
                                    @can('room_edit')
                                    <a href="{{ route('admin.rooms.edit',[$room->id]) }}" class="btn btn-xs btn-warning">@lang('quickadmin.qa_edit')</a>
                                    @endcan
                                    @can('room_delete')
{!! Form::open(array(
                                        'style' => 'display: inline-block;',
                                        'method' => 'DELETE',
                                        'onsubmit' => "return confirm('".trans("quickadmin.qa_are_you_sure")."');",
                                        'route' => ['admin.rooms.destroy', $room->id])) !!}
                                    {!! Form::submit(trans('quickadmin.qa_delete'), array('class' => 'btn btn-xs btn-danger')) !!}
                                    {!! Form::close() !!}
                                    @endcan
                                </td>
                                @endif
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="8">@lang('quickadmin.qa_no_entries_in_table')</td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
@stop

@section('javascript') 
    <script>
        @can('room_delete')
            @if ( request('show_deleted') != 1 ) window.route_mass_crud_entries_destroy = '{{ route('admin.rooms.mass_destroy') }}'; @endif
        @endcan

    </script>
@endsection