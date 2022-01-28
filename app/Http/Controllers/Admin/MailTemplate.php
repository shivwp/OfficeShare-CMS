<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\MailTemplate as Template;
use App\User;
use Mail;
use App\Mail\propertyAddMail;
class MailTemplate extends Controller
{
    public function index(Request $request)
    {
        $msg = Template::orderBy('id', 'DESC'); 

            if($request->search){

              $msg->where('status', 'like', "%$request->search%");  


            }
             if($request->filter != null){

              $msg->where('active_status', '=',$request->filter);  


            }

        $d['msg'] = $msg->paginate(10)->withQueryString();
        $d['title'] = "Manage Mail Template";
        return view("admin.master.mail-template", $d);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $d['title'] = "Add New Template";
        return view('admin.master.add-mail-template', $d);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    function sendMessageToAdmin()
    {
        try {
            $st = User::where('user_type','=','company')->first();
            $sign = [
                // '{name}' => $request->name,
                // '{email}' => $request->email,
                // '{token}' => url('email/verify/'.$request->id),
                'email'=>'rahul.sevta@expertwebtechnologies.com',
                'name'=>'admin'
            ];
        }
         catch (Exception $e) {
        }
    }
    public function store(Request $request)
    {


        Template::updateOrCreate(
            [
                'id' => $request->mid
            ],
            [
                'status' => $request->status,
                'name' => $request->name,
                'subject' => $request->subject,
                'message' => $request->content,
                //'message' => str_replace('../../..',url('/'),$request->message),
                'from_email' => $request->fromemail,
                'reply_email' => $request->replyemail,
                'msg_cat' => $request->msg_cat
            ]
        );
       
          $projects = User::where("user_type", "=", 'company')->get();


         $sign = [
                // '{name}' => $request->name,
                // '{email}' => $request->email,
                // '{token}' => url('email/verify/'.$request->id),
                'email'=>'rahul.sevta@expertwebtechnologies.com',
                'name'=>'admin'
            ];
            $email = 'rahul.sevta@expertwebtechnologies.com';
         Mail::to($email)->send(new propertyAddMail($sign));
         
        return redirect('dashboard/mail-template');
    }
    


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $d['title'] = "Update Message";
        $d['msg'] = Template::findOrfail($id);
        return view('admin.master.add-mail-template', $d);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if (request()->ajax()) {
            $mg = Template::findOrFail($id);
            $mg->delete();
            return response()->json(['msg' => "Removed successfully"], 200);
        }
    }
}
