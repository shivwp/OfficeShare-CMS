<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Question;
use App\AnswerOption;
use Illuminate\Support\Facades\Auth;

class QuestionApiController extends Controller
{

      public function __construct(Request $request)
    {

        //dd($request->api_key);

        $apitoken = $request->header('api_key');

        if (empty($apitoken)) {
            $response = json_encode(array(
                'status' => false,
                'message' => 'Please Provide Api Token',
            ));
            header("Content-Type: application/json");
            echo $response;
            exit;
        }
        if ($apitoken != env("api_key")) {
            $response = json_encode(array(
                'status' => false,
                'message' => 'Api Token Not valid',
            ));
            header("Content-Type: application/json");
            echo $response;
            exit;
        }
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $questionAnswerList = Question::select('id','questions')->get();

        if(!empty($questionAnswerList)){
            foreach($questionAnswerList as $key => $val){

            $Answer = AnswerOption::where('question_id','=',$val->id)->get();

                if(!empty($Answer)){
              
                      $questionAnswerList[$key]['answer_options'] = $Answer;

                }

            }
            return response()->json(['status' => true, 'message' => "success", 'questions' => $questionAnswerList], 200);
        }
        else{

            return response()->json(['status' => false, 'message' => "Data not found", 'questions' => null], 200);
        }
       
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

     
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
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
       
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
      
    }
}
