<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Notifications;
use Illuminate\Support\Facades\Auth;
use DB;

class NotificationsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
       
        $d['title'] = "Manage Notifications";
        $d['notification'] = Notifications::where('type','=','promotional')->orderBy('id', 'desc')->paginate(10)->withQueryString();
        return view('admin.notifications.index', $d);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

        $d['title'] = "Send Notification";
        return view('admin.notifications.add', $d);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //dd($request);


        if ($files    =    $request->file('media')) {
            $name    =    uniqid() . $files->getClientOriginalName();
            $url = url('media/thumbnail/'.$name);
            $files->move('media/thumbnail', $name);
        }

        $Notification = Notifications::updateOrCreate(['id' => $request->id], [
            'title'         => $request->title,
            'user_id'       => 0,
            'body'          => $request->body,
            'type'          => 'promotional',
            'image'         => isset($url) ? $url : $url = null
        ]);

        //Send Notification

        $firebaseToken =DB::table('user_device_token')
                    ->whereNotNull('device_token')
                    ->distinct('device_token')
                    ->pluck('device_token')
                    ->all();

        $SERVER_API_KEY = 'AAAAiAxlOvk:APA91bFd-Ml4T0dZDXv48JePKdP8a6YB6BLKe-QX52d-7MdXOcciJX8mdWcNdjRT60dgOFxieiT0g6AM3mLSZnZMG1waWuajskgsTN-wHgpmV3cM9HXA8bZGVATuDrwMzEoDm3ge2g-b';

        $data = [
            "registration_ids" => $firebaseToken,
            "notification" => [
                "title" => $Notification->title,
                "body" => $Notification->body,
                "image" =>$url,
               // "image" =>"https://ps.w.org/wp-notification-bell/assets/icon-256x256.png",
            ]
        ];
        $dataString = json_encode($data);
    

        $headers = [
            'Authorization: key=' . $SERVER_API_KEY,
            'Content-Type: application/json',
        ];

        $ch = curl_init();
      
        curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $dataString);

        $response = curl_exec($ch);
          

      
        return redirect('dashboard/notifications/')->with('msg', 'Notifications added or updated successfully');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $d['title'] = "Edit Notifications";
        $d['notification'] = Notifications::findOrFail($id);
        return view('admin.notifications.edit', $d);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {

        Notifications::destroy($id);
    }

    public function upload(Request $request){
        $fileName= uniqid() .$request->file('file')->getClientOriginalName();
        $path=$request->file('file')->move('upload-blog-image', $fileName);
        return response()->json(['location'=>url($path)]); 
        
     

    }
}
