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
    <h3 class="page-title">สถานะบัญชีผู้ใช้งาน</h3>
    @can('country_create')
    <p>
        <a href="{{ route('admin.categories.create') }}" class="btn btn-success"><i class="fa fa-plus-square" aria-hidden="true"></i> เพิ่ม</a>
        
    </p>
    @endcan

    @can('country_delete')
    <p>
        <ul class="list-inline">
            <li><a href="{{ route('admin.categories.index') }}" style="{{ request('show_deleted') == 1 ? '' : 'font-weight: 700' }}">@lang('quickadmin.qa_all')</a></li> |
            <li><a href="{{ route('admin.categories.index') }}?show_deleted=1" style="{{ request('show_deleted') == 1 ? 'font-weight: 700' : '' }}">@lang('quickadmin.qa_trash')</a></li>
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
                <table class="table table-hover {{ count($categories) > 0 ? 'datatable' : '' }} @can('country_delete') @if ( request('show_deleted') != 1 ) dt-select @endif @endcan">
                    <thead>
                        <tr>
                            @can('country_delete')
                                @if ( request('show_deleted') != 1 )<th style="text-align:center;"><input type="checkbox" id="select-all" /></th>@endif
                            @endcan
    
                            <th>สถานะ</th>
                            @if( request('show_deleted') == 1 )
                            <th>&nbsp;</th>
                            @else
                            <th>&nbsp;</th>
                            @endif
                        </tr>
                    </thead>
                    
                    <tbody>
                        @if (count($categories) > 0)
                            @foreach ($categories as $country)
                                <tr data-entry-id="{{ $country->id }}">
                                    @can('country_delete')
                                        @if ( request('show_deleted') != 1 )<td></td>@endif
                                    @endcan
                                    <td field-key='name'>{{ $country->name }}</td>
                                    @if( request('show_deleted') == 1 )
                                    <td>
                                        @can('country_delete')
                                                                            {!! Form::open(array(
                                            'style' => 'display: inline-block;',
                                            'method' => 'POST',
                                            'onsubmit' => "return confirm('".trans("quickadmin.qa_are_you_sure")."');",
                                            'route' => ['admin.categories.restore', $country->id])) !!}
                                        {!! Form::submit(trans('quickadmin.qa_restore'), array('class' => 'btn btn-xs btn-success')) !!}
                                        {!! Form::close() !!}
                                    @endcan
                                        @can('country_delete')
                                                                            {!! Form::open(array(
                                            'style' => 'display: inline-block;',
                                            'method' => 'DELETE',
                                            'onsubmit' => "return confirm('".trans("quickadmin.qa_are_you_sure")."');",
                                            'route' => ['admin.categories.perma_del', $country->id])) !!}
                                        {!! Form::submit(trans('quickadmin.qa_permadel'), array('class' => 'btn btn-xs btn-danger')) !!}
                                        {!! Form::close() !!}
                                    @endcan
                                    </td>
                                    @else
                                    <td>
                                        {{-- @can('country_view')
                                        <a href="{{ route('admin.categories.show',[$country->id]) }}" class="btn btn-xs btn-primary">@lang('quickadmin.qa_view')</a>
                                        @endcan --}}
                                        @can('country_edit')
                                        <a href="{{ route('admin.categories.edit',[$country->id]) }}" class="btn btn-xs btn-warning">@lang('quickadmin.qa_edit')</a>
                                        @endcan
                                        @can('country_delete')
    {!! Form::open(array(
                                            'style' => 'display: inline-block;',
                                            'method' => 'DELETE',
                                            'onsubmit' => "return confirm('".trans("quickadmin.qa_are_you_sure")."');",
                                            'route' => ['admin.categories.destroy', $country->id])) !!}
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
        @can('country_delete')
            @if ( request('show_deleted') != 1 ) window.route_mass_crud_entries_destroy = '{{ route('admin.categories.mass_destroy') }}'; @endif
        @endcan

    </script>
@endsection