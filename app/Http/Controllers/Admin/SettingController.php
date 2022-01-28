<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;

use App\Setting;

class SettingController extends Controller

{

    /**

     * Display a listing of the resource.

     *

     * @return \Illuminate\Http\Response

     */

    public function index()

    {

        $d['title']="Business Information Setting";

        $d['setting']=Setting::with(['countryName','phoneCode'])->get();

        $d['country']=DB::table('countries')->orderBy("name")->get();

        //  dd($d['setting']);

        return view('admin.master.site-setting',$d);

    }



    /**

     * Store a newly created resource in storage.

     *

     * @param  \Illuminate\Http\Request  $request

     * @return \Illuminate\Http\Response

     */

    public function store(Request $req)
    {

           
          $mailData=['host' => $req->host,

          'port' => $req->port,

          'encrypt' =>$req->encrypt,

          'name'=>$req->sname,

          'email'=>$req->semail,

          'password'=>$req->password];
            for($i=0;$i<17;$i++){
            $name="name_".$i;
            $value="value_".$i;
            $id="id_".$i;
            if($req->has($name) && $req->has($value)){
            if($req->file($value)){
            $setting= Setting::updateOrCreate(['id'=>$req->$id],[
            'name'=>$req->$name,
            'value'=>$req->file($value)->move('logo',uniqid().$req->file($value)->getClientOriginalName())

            ]); 
            }else{
            $setting= Setting::updateOrCreate(['id'=>$req->$id],[
            'name'=>$req->$name,
            'value'=>$req->$value
            ]);   
            } 
            }
            if($i==14){
              $setting= Setting::updateOrCreate(['id'=>$req->$id],[
                'name'=>$req->$name,
                'value'=>json_encode($mailData)
                //json_encode(['mail_type'=>$mailtype,'mail_data'=>$mailData])
    
                ]); 
            } 
        }
        $mailData=null;
        // if($req->mail=="smtp"){

          // $mailtype=$req->mail;

          // }elseif($req->mail=="sendmail"){
          // $mailtype=$req->mail;
          // }
       



       return back();

    }







}

