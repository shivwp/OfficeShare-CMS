<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyUserRequest;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Role;
use App\User;
use App\Plans;
use App\UserPlan;
use DB;
use App\UserVerifyToken;
use Illuminate\Support\Facades\Hash;
use Gate;
use Str;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Redirect;

class UsersController extends Controller
{
    public function index(Request $request)
    {
       
        abort_if(Gate::denies('user_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        //$users = User::all();
        $query = User::with('plan')->orderBy('id', 'DESC');

        // if(isset($_REQUEST['']))
        if($request->filter != Null) {
            // 
            $query->join('role_user', 'role_user.user_id', '=', 'users.id')->where('role_user.role_id', '=', $request->filter);
        }

        $users = $query->paginate(10)->withQueryString();

        foreach($users as $key => $val){
            // dd($val->plan);
            foreach($val->plan as $key1 => $val1){

                if(!empty($val1) && $val1->status){

                    $plan = Plans::where('id','=',$val1->plan_id)->first();

                }
                $users[$key]['plan_title'] =  !empty($plan->title)? $plan->title : "";
                $users[$key]['validity'] =  !empty($plan->validity) ? $plan->validity : "";
                $users[$key]['price'] =  !empty($plan->price) ? $plan->price : "";
            }

            
           
        }


        $role = Role::all();

        return view('admin.users.index', compact('users','role'));
    }

    public function create()
    {
        abort_if(Gate::denies('user_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $roles = Role::all()->pluck('title', 'id');

        $plan = Plans::all();

        return view('admin.users.create', compact('roles','plan'));
    }

    public function store(Request $request)
    {
        $mobileexist = User::where('phone','=',$request->code.''.$request->mobile)->first();
        $emailexist = User::where('email','=',$request->email)->first();
        if(!empty($mobileexist)){
            // 
            return Redirect::back()->withErrors(['msg' => 'Mobile number already exits']);
        }

        if(!empty($emailexist)){
            // 
            return Redirect::back()->withErrors(['msg' => 'Email already exits']);
        }

        $user = User::create([
            'first_name'        => $request->name,
            'last_name'         => $request->last_name,
            'name'              => $request->name.' '.$request->last_name,
            'email'             => $request->email,
            'dob'               => $request->dob,
            'domestic_postcode' => $request->zip,
            'password'          => Hash::make($request->password),
            'phone'             => $request->code.''.$request->mobile,
            'remember_token'    => uniqid(),
            'parent_id'         => isset($request->parent_id)?$request->parent_id:'0',
        ]);

        $insertid = $user->id;
        $roleid = 3;

        $checkrole = in_array($roleid, $request->roles);

        if($checkrole == true){

            $plan = Plans::where('id','=',$request->plan)->first();
            $current_date = Carbon::now();
            switch ($plan->type) {
                case 'Day':
                     // code...
                     $future_data = $current_date->addDays($plan->number);
                     break;

                case 'Month':
                     // code...
                     $future_dataa  = $current_date->addMonths($plan->number);
                     $future_data  = $future_dataa->subDays(1);
                     break;

                default:
                     // code...
                     $future_dataa = $current_date->addYears($plan->number);
                     $future_data  = $future_dataa->subDays(1);
                     break;
            }

            $user_plan = UserPlan::updateOrCreate(['user_id' => $insertid],[
                'plan_id'                => $plan->id,
                'description'            => $plan->description,
                'validity'               => $plan->validity, 
                'price'                  => $plan->price, 
                'from_date'              => Carbon::now(),
                'to_date'                => $future_data, 
                'status'                 => true,
            ]);

        }

        $user->roles()->sync($request->input('roles', []));
        if ($files = $request->file('image')) {
             $name    =    uniqid() . $files->getClientOriginalName();
             $files->move('media', $name);
             $namesave = url('/media').'/'.$name;

            $user->profile_pic = $namesave;
            $user->save();
        }

        // if($request->roles == 4) {
        //     $user_plan = UserPlan::updateOrCreate(['user_id' => $insertid],[
        //         'status'                 => false,
        //     ]);
        // }

        return redirect()->route('dashboard.users.index');

    }

    public function edit(User $user)
    {
        abort_if(Gate::denies('user_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $roles = Role::all()->pluck('title', 'id');

        $user->load('roles');

        $plan = Plans::all();
        
        $singlePlan = UserPlan::where('user_id','=',$user->id)->first();

        return view('admin.users.edit', compact('roles', 'user','plan','singlePlan'));
    }

    public function update(Request $request, User $user)
    {
        $string = $request->code.''.$request->mobile;
        
        $user->where('id','=',$request->id)->update([
            'first_name'        => $request->first_name,
            'last_name'         => $request->last_name,
            'name'              => $request->first_name.' '.$request->last_name,
            'email'             => $request->email,
            'dob'               => $request->dob,
            'domestic_postcode' => $request->zip,
            'password'          => Hash::make($request->password),
            'phone'             => str_replace(' ', '', $string),
        ]);

        $insertid = $user->id;
        $roleid = 3;



        $checkrole = in_array($roleid, $request->roles);

        if($checkrole == true){

            $plan = Plans::where('id','=',$request->plan)->first();
                $current_date = Carbon::now();
            switch ($plan->type) {
                case 'Day':
                        // code...
                        $future_data = $current_date->addDays($plan->number);
                        break;

                case 'Month':
                        // code...
                        $future_dataa  = $current_date->addMonths($plan->number);
                        $future_data  = $future_dataa->subDays(1);
                        break;

                default:
                        // code...
                        $future_dataa = $current_date->addYears($plan->number);
                        $future_data  = $future_dataa->subDays(1);
                        break;
            }

            $user_plan = UserPlan::updateOrCreate(['user_id' => $insertid],[
                'plan_id'                => $plan->id,
                'description'            => $plan->description,
                'validity'               => $plan->validity, 
                'price'                  => $plan->price, 
                'from_date'              => Carbon::now(),
                'to_date'                => $future_data, 
                'status'                 => true,
                
            ]);

        }

        if($request->roles == 4) {
            $user_plan = UserPlan::updateOrCreate(['user_id' => $insertid],[
                'status'                 => false,
            ]);
        }

        $user->roles()->sync($request->input('roles', []));

        if ($files = $request->file('image')) {
            // 
            $name    =    uniqid() . $files->getClientOriginalName();
            $files->move('media', $name);
            $namesave = url('/media').'/'.$name;

            $user->profile_pic = $namesave;
            $user->save();
        }

        if($request->roles[0] == 4) {
            $user_plan = UserPlan::updateOrCreate(['user_id' => $insertid],[
                'status'                 => false,
            ]);
        }

        return redirect()->route('dashboard.users.index');

    }

    public function show(User $user)
    {
        abort_if(Gate::denies('user_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $user->load('roles');

        return view('admin.users.show', compact('user'));
    }

    public function verifyUser($token)
    {

        User::where('id', $token)->update([

            'remember_token' =>Str::random(40)
        ]);

        $verifyUser = User::where('id', $token)->first();


        if(!empty($verifyUser->remember_token)) {
            echo "<style>
   body {font-family: 'Poppins', sans-serif;}
</style>";
             echo '<img src="http://officeshare-cms.ewtlive.in/media/imageverify.png" alt = "verify img" style="margin-bottom:20px;">';
             echo "<body style='text-align: center;margin-top:250px;color:#76828A'><h1>Your email has been successfully verified</h1></body>";
             header( "refresh:5;url=http://officeshare.eoxysitsolution.com/#/" );
         }
        else{
                echo "<body style='text-align: center;margin-top:250px;font-size:21px;'><h1>You Email Not Verified</h1></body>";
                header( "refresh:5;url=http://officeshare.eoxysitsolution.com/#/" );
        }
    }

    public function changepasswoord($userId){


        return view('admin.change-pass.add', compact('userId'));

    }

    public function updatepasswoord(Request $req){


                User::where('id', '=', $req->user_id)
                   ->update(['password'=> $req->password]);

                return view('admin.change-pass.msg');
       

    }

    public function destroy($id)
    {
        //abort_if(Gate::denies('user_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

         User::destroy($id);

        // $user->destroy();

        // return back();

    }

    public function massDestroy(MassDestroyUserRequest $request)
    {
        User::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);

    }
    
    public function upload(Request $request)
    {
        # code...
        if($request->hasFile('upload')) {
            $originName = $request->file('upload')->getClientOriginalName();
            $fileName = pathinfo($originName, PATHINFO_FILENAME);
            $extension = $request->file('upload')->getClientOriginalExtension();
            $fileName = $fileName.'_'.time().'.'.$extension;
        
            $request->file('upload')->move('images', $fileName);
   
            $CKEditorFuncNum = $request->input('CKEditorFuncNum');
            $url = asset('images/'.$fileName); 
            $msg = 'Image uploaded successfully'; 
            $response = "<script>window.parent.CKEDITOR.tools.callFunction($CKEditorFuncNum, '$url', '$msg')</script>";
               
            @header('Content-type: text/html; charset=utf-8'); 
            echo $response;
        }
    }

}
