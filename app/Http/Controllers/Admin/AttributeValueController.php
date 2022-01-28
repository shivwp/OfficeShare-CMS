<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\AttributeValue;
use App\Attribute;
use App\Category;

class AttributeValueController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $d['title'] = "Add Amenities";
        $d['attributeVal'] = AttributeValue::with(['attributeName'])->get();
        $d['attribute'] = Attribute::where('id','=','2')->orderBy('name', 'asc')->get();
        // $d['categ']=Category::orderBy("name","desc")->where('cid',"No Parent")->orderBy('name','asc')->get();
        //dd($d['attributeVal']);
        return view('admin.master.attribute.attribute-value', $d);
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $att = AttributeValue::updateOrCreate(
            ['id' => $request->id],
            [
                'value' => $request->name,
                'attribute_id' => $request->attr_id,
            ]
        );
        if ($request->has('icon')) {
            $name = $request->file('icon')->move(
                'attribute_icon',
                uniqid() . $request->file('icon')->getClientOriginalExtension()
            );
            $att->icon = $name;
            $att->save();
        }
        if ($request->has('icon2')) {
            $name = $request->file('icon2')->move(
                'attribute_icon',
                uniqid() . $request->file('icon2')->getClientOriginalExtension()
            );
            $att->active_icon = $name;
            $att->save();
        }
        return redirect('dashboard/attribute-value')
            ->with('msg', 'Attribute Value added or Updated successfully');
    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {

        $d['title'] = "Edit Attribute Value";
        $d['EditAttr'] = AttributeValue::with(['attributeName'])
            ->findOrFail($id);
        // $d['categ']=Category::orderBy("name","desc")->where('cid',"No Parent")->orderBy('name','asc')->get();
        $d['attribute'] = Attribute::orderBy('name', 'asc')->where('id','=','2')->get();
        return view('admin.master.attribute.attribute-value', $d);
    }
    public function getSubAttribute($id)
    {
        $attr = Attribute::where('attribute_id', $id)->get();
        return $attr;
    }
    public function destroy($id)
    {
        AttributeValue::destroy($id);
    }
    public function fetchAttributeValue(Request $req)
    {
        if (request()->ajax()) {
            $attCollect = [];
            $valCollect = [];
            $i = 0;
            $j = 0;
            $attr = Attribute::with('attributeValue')->whereIn('id', $req->attribute)
                ->where('status', 1)->get();
            if (isset($attr)) {
                foreach ($attr as $value) {
                    if (isset($value->attributeValue)) {
                        foreach ($value->attributeValue as $value2) {
                            $valCollect[$j++] = ['id' => $value2->id, 'name' => $value2->value];
                        }
                        $attCollect[$i++] = ['id' => $value->id, 'name' => $value->display_name, 'attribute' => $valCollect];
                        $valCollect = [];
                        $j = 0;
                    }
                }
            }
            return $attCollect;
        }
    }
    //   public function fetchAttributeValue(Request $req)
    // {
    //     if(request()->ajax()){
    //         $attCollect=[];
    //         $valCollect=[];
    //         $i=0;
    //         $j=0;
    //         $attr= Attribute::with('attributeValue')->where('id',$req->attribute)
    //         ->where('status',1)->get();
    //         if(isset($attr)){
    //             foreach ($attr as $value) {
    //               if(isset($value->attributeValue)){
    //                 foreach ($value->attributeValue as $value2) {
    //                     $valCollect[$j++]=['id'=>$value2->id,'name'=>$value2->atrr_value];
    //                 }
    //                 $attCollect[$i++]=['id'=>$value->id,'name'=>$value->name,'attribute'=>$valCollect];
    //                 $valCollect=[];
    //                 $j=0;
    //               }
    //             }
    //         }
    //         return $attCollect;
    //     }

    // }
    public function getAttributeData(Request $request)
    {
        $total_value = AttributeValue::count();

        $columns = array(
            0 => 'id',

            1 => 'attribute',
            2 => 'value',
            3 => 'icon',
            4 => 'active_icon',
            5 => 'status',
            6 => 'options',
        );

        $totalData = AttributeValue::count();

        $totalFiltered = $totalData;
        // return $request->input('order');
        $limit = $request->input('length');
        $start = $request->input('start');


        if (empty($request->input('search.value'))) {
            // $order = $columns[$request->input('order[0]column')];
            // $dir = $request->input('order[0]dir');         
            $attributeValue = AttributeValue::offset($start)
                ->limit($limit)
                ->orderBy('value', 'asc')
                ->get();
        } else {
            $search = $request->input('search.value');

            $attributeValue =  AttributeValue::with('attributeName')->where('id', 'LIKE', "%{$search}%")
                ->orWhere('value', 'LIKE', "%{$search}%")
                ->offset($start)
                ->limit($limit)
                ->orderBy($order, $dir)
                ->get();

            $totalFiltered = AttributeValue::where('id', 'LIKE', "%{$search}%")
                ->orWhere('value', 'LIKE', "%{$search}%")
                ->count();
        }

        $data = array();
        if (!empty($attributeValue)) {
            foreach ($attributeValue as $item) {

                $edit =  route('dashboard.attribute-value.edit', $item->id);
                $del =  route('dashboard.attribute-value.destroy', $item->id);
                ob_start();
?>

                <a href='<?php echo $edit; ?>' class="btn btn-info btn-sm clr-theme" title='EDIT'><i class="far fa-edit"></i></a>
                <a href='<?php echo $del; ?>' class="btn btn-danger btn-sm clr-theme" onclick="return confirm('Are you sure?')" title='Delete'><i class="far fa-trash-alt"></i></a>

<?php
                $st = "";
                if ($item->status == 1) {
                    $st = '<button class="btn btn-success btn-xs edit btn-rounded">Active</button>';
                } else {
                    $st = '<button class="btn btn-danger btn-xs edit btn-rounded">De-active</button>';
                }
                $option = ob_get_clean();
                $nestedData['id'] = $item->id;
                $nestedData['attribute'] = $item->attributeName['name'];
                $nestedData['value'] = $item->value;
                if (!empty($item->icon)) {
                    $nestedData['icon'] = '<img src="' . url('') . '/' . $item->icon . '" alt="img" style="width:50px;height:50px;">';
                    $nestedData['active_icon'] = '<img src="' . url('') . '/' . $item->active_icon . '" alt="img" style="width:50px;height:50px;">';
                } else {
                    $nestedData['icon'] = "";
                    $nestedData['active_icon'] = "";
                }


                $nestedData['status'] = $st;
                $nestedData['options'] = $option;
                $data[] = $nestedData;
            }
        }

        $json_data = array(
            "draw"            => intval($request->input('draw')),
            "recordsTotal"    => intval($totalData),
            "recordsFiltered" => intval($totalFiltered),
            "data"            => $data
        );

        echo json_encode($json_data);
    }
}
