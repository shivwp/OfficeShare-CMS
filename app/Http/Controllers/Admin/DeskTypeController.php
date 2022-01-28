<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\DeskType;
use Illuminate\Http\Request;
use Gate;
use Symfony\Component\HttpFoundation\Response;

class DeskTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        abort_if(Gate::denies('desktype_access'), Response::HTTP_FORBIDDEN, "403 FORBIDDEN");
        $d['title'] = "Add Desk Type";
        $d['desk'] = DeskType::get();
        return view('admin.master.desk.index', $d);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        DeskType::updateOrCreate(['id' => $request->id], [
            'types' => $request->name
        ]);
        return redirect('dashboard/desk-type/')->with('msg', 'Desk type added or updated successfully');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        abort_if(Gate::denies('desktype_edit'), Response::HTTP_FORBIDDEN, "403 FORBIDDEN");
        $d['title'] = "Edit Desk Type";
        $d['eddesk'] = DeskType::findOrFail($id);
        return view('admin.master.desk.index', $d);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        abort_if(Gate::denies('desktype_delete'), Response::HTTP_FORBIDDEN, "403 FORBIDDEN");
        if (request()->ajax()) {
            DeskType::destroy($id);
        }
    }
    public function changeStatus($id, $st)
    {
        DeskType::where('id', $id)->update(['status' => $st]);
        return back();
    }
}
