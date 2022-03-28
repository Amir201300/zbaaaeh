<?php


namespace App\Http\Controllers\Admin;

use App\Models\Categories;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Products;
use Yajra\DataTables\DataTables;
use Auth, File;
use Illuminate\Support\Facades\Storage;


class ProductController extends Controller
{
    use \App\Traits\ApiResponseTrait;

    /**
     * @return mixed
     * @throws \Exception
     */
    public function allData(Request $request)
    {
        $data = Products::get();
        return $this->mainFunction($data);
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request)
    {
        $cat = Categories::all();
        return view('Admin.Product.index',compact('cat'));
    }

    /**
     * @param Request $request
     * @return int
     * @throws \Illuminate\Validation\ValidationException
     */
    public function create(Request $request)
    {
        $this->validate(
            $request,
            [
                'icon' => 'required|image|max:2000',
            ],
            [
                'icon.required' =>'من فضلك ادخل صوره ',
                'icon.image' =>'من فضلك ادخل صورة صالحة'
            ]
        );
        $this->save_Product($request,new Products);
        return $this->apiResponseMessage(1,'تم اضافة المنتج بنجاح',200);
    }

    /**
     * @param $id
     * @return mixed
     */
    public function edit($id)
    {
        $Product = Products::find($id);
        return $Product;
    }

    /**
     * @param Request $request
     * @return int
     * @throws \Illuminate\Validation\ValidationException
     */
    public function update(Request $request)
    {

        $Product = Products::find($request->id);
        $this->save_Product($request,$Product);
        return $this->apiResponseMessage(1,'تم تعديل المنتج بنجاح',200);
    }

    /**
     * @param $request
     * @param $brand
     */
    public function save_Product($request,$Product){
        $Product->name_ar = $request->name_ar;
        $Product->name_en = $request->name_en;
        $Product->price = $request->price;
        $Product->price = $request->price;
        $Product->weight = $request->weight;
        $Product->age = $request->age;
        $Product->cat_id = $request->cat_id;
        if($request->icon) {
            deleteFile('Product',$Product->icon);
            $Product->icon=saveImage('Product',$request->icon);
        }
        $Product->save();
    }

    /**
     * @param $id
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse|int
     */
    public function destroy($id,Request $request)
    {
        if ($request->type == 2) {
            $ids = explode(',', $id);
            $Product = Products::whereIn('id', $ids)->get();
            foreach($Product as $row){
                $this->deleteRow($row);
            }
        } else {
            $Product = Products::find($id);
            $this->deleteRow($Product);
        }
        return response()->json(['errors' => false]);
    }

    /**
     * @param $cat
     */
    private function deleteRow($Product){
        deleteFile('Product',$Product->image);
        $Product->delete();
    }

    /**
     * @param $data
     * @return mixed
     * @throws \Exception
     */
    private function mainFunction($data)
    {
        return Datatables::of($data)->addColumn('action', function ($data) {
            $options = '<td class="sorting_1"><button  class="btn btn-info waves-effect btn-circle waves-light" onclick="editFunction(' . $data->id . ')" type="button" ><i class="fa fa-spinner fa-spin" id="loadEdit_' . $data->id . '" style="display:none"></i><i class="sl-icon-wrench"></i></button>';
            $options .= ' <button type="button" onclick="deleteFunction(' . $data->id . ',1)" class="btn btn-dribbble waves-effect btn-circle waves-light"><i class="sl-icon-trash"></i> </button></td>';
            return $options;
        })->addColumn('checkBox', function ($data) {
            $checkBox = '<td class="sorting_1">' .
                '<div class="custom-control custom-checkbox">' .
                '<input type="checkbox" class="mybox" id="checkBox_' . $data->id . '" onclick="check(' . $data->id . ')">' .
                '</div></td>';
            return $checkBox;
        })->editColumn('icon', function ($data) {
            $image = '<a href="'. getImageUrl('Product',$data->icon).'" target="_blank">'
                .'<img  src="'. getImageUrl('Product',$data->icon) . '" width="50px" height="50px"></a>';
            return $image;
        })->editColumn('cat_id', function ($data) {
            return $data->cat->name;
        })->rawColumns(['action' => 'action','icon' => 'icon','checkBox'=>'checkBox','cat_id'=>'cat_id'])->make(true);
    }
}
