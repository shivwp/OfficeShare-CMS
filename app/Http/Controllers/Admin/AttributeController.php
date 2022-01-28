<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Attribute;
use App\Category;
use App\AttributeValue;
use App\AttributeOnCategory;
use Illuminate\Support\Facades\DB;

class AttributeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $d['title'] = "Add Attribute";
        $d['attribute'] = Attribute::where('id','=','2')->get();
        // $d['categ']=Category::orderBy("name","desc")->where('cid',"No Parent")->orderBy('name','asc')->get();
        return view('admin.master.attribute.attribute', $d);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function verify()
    {
       
         return view('admin.master.attribute.verify');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // if($request->id!=""){
        // //     $sts =Attribute::find($request->id);
        // //      DB::table('attributes')
        // //   ->where('attribute_id', $sts->name)
        // //   ->update(['attribute_id' =>$request->name,
        // //   'single_page' =>$request->single]);

        //     //   if(!empty($request->catry)){
        //     //     AttributeOnCategory::where('attribute_id',$request->id)->delete();
        //     //     foreach ($request->catry as $value){
        //     //         $cat=new AttributeOnCategory;
        //     //         // $cat->attribute_id=$sts->id;
        //     //         //$cat->attribute_value_id=$att->id;
        //     //         $cat->category_id=$value;
        //     //         $cat->save();
        //     //  }
        //     // }
        //    }
        $att = Attribute::updateOrCreate(
            ['id' => $request->id],
            [
                'name' => $request->name,
                'display_name' => $request->display_name,
                //  'attribute_id'=>$request->parentattr,
                //  'single_page'=>$request->single==1?1:0,
                //  'optional_status'=>$request->opt
            ]
        );
        //   if(!empty($request->catry)){
        //   foreach ($request->catry as $value){
        //             $cat=new AttributeOnCategory;
        //             $cat->attribute_id=$att->id;
        //             //$cat->attribute_value_id=$att->id;
        //             $cat->category_id=$value;
        //             $cat->save();
        //     }
        //   }


        return redirect('dashboard/attribute')
            ->with('msg', 'Attribute added or Updated successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $d['title'] = "Edit Attribute";
        $d['attribute'] = Attribute::get();
        $d['EditAttr'] = Attribute::findOrFail($id);
        // $d['categ']=Category::orderBy("name","desc")->where('cid',"No Parent")->orderBy('name','asc')->get();
        return view('admin.master.attribute.attribute', $d);
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
        $attr = Attribute::findOrFail($id);
        AttributeValue::where('sub_attribute_id', $id)->delete();
        Attribute::where('attribute_id', $attr->name)->delete();
        AttributeOnCategory::where('attribute_id', $id)->delete();
        $attr->delete();
    }
}
