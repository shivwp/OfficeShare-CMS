<?php



namespace App\Http\Controllers\Admin;



use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;

use App\Category;

use Symfony\Component\HttpFoundation\Response;

use Gate;

class CategoriesController extends Controller

{

    /**

     * Display a listing of the resource.

     *

     * @return \Illuminate\Http\Response

     */

    public function index()
    {

        $d['cat']=Category::orderBy("id","desc")->get();

        $d['categ']=Category::orderBy("name","desc")->get();

        $d['title']='Product Categories';

        return view('admin.categories.index',$d);

    }



    /**

     * Show the form for creating a new resource.

     *

     * @return \Illuminate\Http\Response

     */

    public function create()
    {

        //

    }



    /**

     * Store a newly created resource in storage.

     *

     * @param  \Illuminate\Http\Request  $request

     * @return \Illuminate\Http\Response

     */

    public function store(Request $request)
    {

          if($request->id!=""){

                  $sts =Category::find($request->id);

                   DB::table('categories')

                ->where('cid', $sts->name)

                ->update(['cid' =>$request->name]);

          }

          $categ=Category::updateOrCreate(['id'=>$request->id],[

           'name'=>$request->input('name'),

           'cid'=>$request->input('pname','No Parent')

          ]);

          return json_encode($categ);

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

      abort_if(Gate::denies('category_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

      $sts=Category::find($id);

      return json_encode($sts);

    }



    /**

     * Update the specified resource in storage.

     *

     * @param  \Illuminate\Http\Request  $request

     * @param  int  $id

     * @return \Illuminate\Http\Response

     */

    public function update(Request $request,$id)
    {

        $sts =Category::find($request->id);
        $sts->name = $request->name;

        $sts->cid = $request->pname;

        $sts->update();
        //Category::where("cid", $cid)->update(['cid'=>$request->name]);
        return redirect('categories');

    }



    /**

     * Remove the specified resource from storage.

     *

     * @param  int  $id

     * @return \Illuminate\Http\Response

     */

    public function destroy($id)

    {

        abort_if(Gate::denies('category_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $cat=Category::findOrFail($id);

        Category::where('cid',$cat->name)->delete();

        $cat->delete();

        

    }

       public function changeStatus($id){

       $cat= Category::findOrFail($id);

       if($cat->status==1){

       $cat->status=0;

       $cat->update();

       }else{

       $cat->status=1;

       $cat->update();

       }

       return back();

        

    }

}

