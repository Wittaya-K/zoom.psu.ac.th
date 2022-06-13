<?php

namespace App\Http\Controllers\Admin;

use App\Zoom;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreZoomsRequest;
use App\Http\Requests\Admin\UpdateZoomsRequest;
use App\Category;
use Illuminate\Support\Facades\DB;

class ZoomsController extends Controller
{
    /**
     * Display a listing of zoom.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (! Gate::allows('zoom_access')) {
            return abort(401);
        }


        if (request('show_deleted') == 1) {
            if (! Gate::allows('zoom_delete')) {
                return abort(401);
            }
            $zooms = Zoom::onlyTrashed()->get();
        } else {
            // $zooms = zoom::all();
            // join ข้อมูล zooms และ categories
            $zooms = DB::table('zooms')
            ->select('zooms.id','zooms.zoom_email','zooms.zoom_number','zooms.password','zooms.description','zooms.expire_at','categories.name')
            ->leftJoin('categories', 'zooms.category_id', '=', 'categories.id')
            ->orderBy('zooms.zoom_number')
            ->get();
        }

        return view('admin.zooms.index', compact('zooms'));
    }

    /**
     * Show the form for creating new zoom.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (! Gate::allows('zoom_create')) {
            return abort(401);
        }
        
        $categories = Category::get()->pluck('name', 'id')->prepend(trans('quickadmin.qa_please_select'), '');
        return view('admin.zooms.create',compact('categories'));
    }

    /**
     * Store a newly created zoom in storage.
     *
     * @param  \App\Http\Requests\StorezoomsRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StorezoomsRequest $request)
    {
        if (! Gate::allows('zoom_create')) {
            return abort(401);
        }
        $zoom = Zoom::create($request->all());


        return redirect()->back()->with('saved', 'บันทึกสำเร็จ'); //แจ้งเตือน sweetalert
        return redirect()->route('admin.zooms.index');
    }


    /**
     * Show the form for editing zoom.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if (! Gate::allows('zoom_edit')) {
            return abort(401);
        }
        $zoom = Zoom::findOrFail($id);
        $categories = Category::get()->pluck('name', 'id')->prepend(trans('quickadmin.qa_please_select'), '');

        return view('admin.zooms.edit', compact('zoom','categories'));
    }

    /**
     * Update zoom in storage.
     *
     * @param  \App\Http\Requests\UpdatezoomsRequest  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdatezoomsRequest $request, $id)
    {
        if (! Gate::allows('zoom_edit')) {
            return abort(401);
        }
        $zoom = Zoom::findOrFail($id);
        $zoom->update($request->all());

        
        return redirect()->back()->with('saved', 'บันทึกสำเร็จ'); //แจ้งเตือน sweetalert
        return redirect()->route('admin.zooms.index');
    }


    /**
     * Display zoom.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        if (! Gate::allows('zoom_view')) {
            return abort(401);
        }
        $bookings = \App\Booking::where('zoom_id', $id)->get();

        $zoom = Zoom::findOrFail($id);

        return view('admin.zooms.show', compact('zoom', 'bookings'));
    }


    /**
     * Remove zoom from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if (! Gate::allows('zoom_delete')) {
            return abort(401);
        }
        $zoom = Zoom::findOrFail($id);
        $zoom->delete();

        return redirect()->route('admin.zooms.index');
    }

    /**
     * Delete all selected zoom at once.
     *
     * @param Request $request
     */
    public function massDestroy(Request $request)
    {
        if (! Gate::allows('zoom_delete')) {
            return abort(401);
        }
        if ($request->input('ids')) {
            $entries = Zoom::whereIn('id', $request->input('ids'))->get();

            foreach ($entries as $entry) {
                $entry->delete();
            }
        }
    }


    /**
     * Restore zoom from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function restore($id)
    {
        if (! Gate::allows('zoom_delete')) {
            return abort(401);
        }
        $zoom = Zoom::onlyTrashed()->findOrFail($id);
        $zoom->restore();

        return redirect()->route('admin.zooms.index');
    }

    /**
     * Permanently delete zoom from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function perma_del($id)
    {
        if (! Gate::allows('zoom_delete')) {
            return abort(401);
        }
        $zoom = Zoom::onlyTrashed()->findOrFail($id);
        $zoom->forceDelete();

        return redirect()->route('admin.zooms.index');
    }
}
