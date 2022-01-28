<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Newsletter;
use \DrewM\MailChimp\MailChimp;

class NewsletterController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $d['title'] = "Add Newsletter API";
        $d['newsletter'] = Newsletter::latest()->get();
        return view('admin.newsletter.index', $d);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $i = 0;
        $MailChimp = new MailChimp($request->api);
        $result = $MailChimp->get('lists');
        foreach ($result['lists'] as $res) {
            Newsletter::updateOrCreate(
                [
                    'id' => $request->id
                ],
                [
                    'api' => $request->api,
                    'audience_id' => $res['id']
                ]
            );
        }
        return redirect('dashboard/newsletter');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $d['title'] = "Edit Newsletter API";
        $d['edchimp'] = Newsletter::findOrFail($id);
        return view('admin.newsletter.index', $d);
    }



    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Newsletter::destroy($id);
    }
    public function changeStatus($id, $st)
    {
        $ns = Newsletter::findOrFail($id);
        $ns->status = $st;
        $ns->update();
    }
}
