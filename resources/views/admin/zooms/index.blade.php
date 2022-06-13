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
    <h3 class="page-title">รายการบัญชีใช้งาน</h3>
    @can('zoom_create')
    <p>
        <a href="{{ route('admin.zooms.create') }}" class="btn btn-success"><i class="fa fa-plus-square" aria-hidden="true"></i> เพิ่มบัญชี</a>
        
    </p>
    @endcan

    @can('zoom_delete')
    <p>
        <ul class="list-inline">
            <li><a href="{{ route('admin.zooms.index') }}" style="{{ request('show_deleted') == 1 ? '' : 'font-weight: 700' }}">@lang('quickadmin.qa_all')</a></li> |
            <li><a href="{{ route('admin.zooms.index') }}?show_deleted=1" style="{{ request('show_deleted') == 1 ? 'font-weight: 700' : '' }}">@lang('quickadmin.qa_trash')</a></li>
        </ul>
    </p>
    @endcan

    @if(session()->has('message'))
    <div class="alert alert-success">
        {{ session()->get('message') }}
    </div>
    @endif

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
                <table class="table table-bordered table-striped table-hover {{ count($zooms) > 0 ? 'datatable' : '' }} @can('zoom_delete') @if ( request('show_deleted') != 1 ) dt-select @endif @endcan">
                    <thead>
                        <tr>
                            @can('zoom_delete')
                                @if ( request('show_deleted') != 1 )<th style="text-align:center;"><input type="checkbox" id="select-all" /></th>@endif
                            @endcan
                            <th style="text-align: center;">Zoom Email</th>
                            <th style="text-align: center;">Zoom Password</th>
                            <th style="text-align: center;">Zoom ID</th>
                            <th style="text-align: center;">Zoom URL</th>
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
                        @if (count($zooms) > 0)
                            @foreach ($zooms as $zoom)
                                <tr data-entry-id="{{ $zoom->id }}">
                                    @can('zoom_delete')
                                        @if ( request('show_deleted') != 1 )<td></td>@endif
                                    @endcan
                                    <td field-key='zoom_email' style="text-align: center;">{{ $zoom->zoom_email }}</td>
                                    <td field-key='floor' style="text-align: center;">{{ $zoom->password }}</td>
                                    <td field-key='zoom_number' style="text-align: center;">{{ $zoom->zoom_number }}</td>
                                    <td field-key='description'>{!! $zoom->description !!}</td>
                                    @if($zoom->name == 'ว่าง')
                                    <td field-key='category_id ' style="text-align: center;"><small class="label pull-center bg-green">{{ $zoom->name }}</small></td>
                                    @else
                                    <td field-key='category_id ' style="text-align: center;"><small class="label pull-center bg-red">{{ $zoom->name }}</small></td>
                                    @endif
                                    <td field-key='expire_at' style="text-align: center;">{{ $zoom->expire_at }}</td>
                                    @if( request('show_deleted') == 1 )
                                    <td>
                                        @can('zoom_delete')
                                                                            {!! Form::open(array(
                                            'style' => 'display: inline-block;',
                                            'method' => 'POST',
                                            'onsubmit' => "return confirm('".trans("quickadmin.qa_are_you_sure")."');",
                                            'route' => ['admin.zooms.restore', $zoom->id])) !!}
                                        {!! Form::submit(trans('quickadmin.qa_restore'), array('class' => 'btn btn-xs btn-success')) !!}
                                        {!! Form::close() !!}
                                    @endcan
                                        @can('zoom_delete')
                                                                            {!! Form::open(array(
                                            'style' => 'display: inline-block;',
                                            'method' => 'DELETE',
                                            'onsubmit' => "return confirm('".trans("quickadmin.qa_are_you_sure")."');",
                                            'route' => ['admin.zooms.perma_del', $zoom->id])) !!}
                                        {!! Form::submit(trans('quickadmin.qa_permadel'), array('class' => 'btn btn-xs btn-danger')) !!}
                                        {!! Form::close() !!}
                                    @endcan
                                    </td>
                                    @else
                                    <td style="text-align: center;">
                                        {{-- @can('zoom_view')
                                        <a href="{{ route('admin.zooms.show',[$zoom->id]) }}" class="btn btn-xs btn-primary">@lang('quickadmin.qa_view')</a>
                                        @endcan --}}
                                        @can('zoom_edit')
                                        <a href="{{ route('admin.zooms.edit',[$zoom->id]) }}" class="btn btn-xs btn-warning">@lang('quickadmin.qa_edit')</a>
                                        @endcan
                                        @can('zoom_delete')
    {!! Form::open(array(
                                            'style' => 'display: inline-block;',
                                            'method' => 'DELETE',
                                            'onsubmit' => "return confirm('".trans("quickadmin.qa_are_you_sure")."');",
                                            'route' => ['admin.zooms.destroy', $zoom->id])) !!}
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
        
	</div>
@stop

@section('javascript') 
    <script>
        @can('zoom_delete')
            @if ( request('show_deleted') != 1 ) window.route_mass_crud_entries_destroy = '{{ route('admin.zooms.mass_destroy') }}'; @endif
        @endcan

    </script>
@endsection