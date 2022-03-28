<?php


namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Product_images;
use App\Models\Products;
use Yajra\DataTables\DataTables;
use Auth, File;
use Illuminate\Support\Facades\Storage;


class Product_imagesController extends Controller
{
    use \App\Traits\ApiResponseTrait;

    /**
     * @return mixed
     * @throws \Exception
     */
    public function allData(Request $request)
    {
        $data = Product_images::where('product_id',$request->product_id)->get();
        return $this->mainFunction($data);
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request)
    {
        $product_id = $request->product_id;
        return view('Admin.Product_images.index',compact('product_id'));
    }

    /***
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     * @throws \Illuminate\Validation\ValidationException
     */
    public function create(Request $request)
    {
        $this->validate(
            $request,
            [
                'image' => 'required|image|max:2000',
            ],
            [
                'image.required' =>'من فضلك ادخل صوره ',
                'image.image' =>'من فضلك ادخل صورة صالحة'
            ]
        );
        $this->save_Product_images($request,new Product_images);
        return $this->apiResponseMessage(1,'تم اضافة الصوره بنجاح',200);
    }

    /**
     * @param $id
     * @return mixed
     */
    public function edit($id)
    {
        $Product_images = Product_images::find($id);
        return $Product_images;
    }

    /**
     * @param Request $request
     * @return int
     * @throws \Illuminate\Validation\ValidationException
     */
    public function update(Request $request)
    {

        $Product_images = Product_images::find($request->id);
        $this->save_Product_images($request,$Product_images);
        return $this->apiResponseMessage(1,'تم تعديل الصوره بنجاح',200);
    }

    /**
     * @param $request
     * @param $brand
     */
    public function save_Product_images($request,$Product_images){
        $Product_images->product_id = $request->product_id;
        if($request->image) {
            deleteFile('Product_images',$Product_images->image);
            $Product_images->image=saveImage('Product_images',$request->image);
        }
        $Product_images->save();
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
            $Product_images = Product_images::whereIn('id', $ids)->get();
            foreach($Product_images as $row){
                $this->deleteRow($row);
            }
        } else {
            $Product_images = Product_images::find($id);
            $this->deleteRow($Product_images);
        }
        return response()->json(['errors' => false]);
    }

    /**
     * @param $cat
     */
    private function deleteRow($Product_images){
        deleteFile('Product_images',$Product_images->image);
        $Product_images->delete();
    }

    /***
     * @param $data
     * @return mixed
     * @throws \Exception
     */
    private function mainFunction($data)
    {
        return Datatables::of($data)->addColumn('action', function ($data) {
            $options = ' <button type="button" onclick="deleteFunction(' . $data->id . ',1)" class="btn btn-dribbble waves-effect btn-circle waves-light"><i class="sl-icon-trash"></i> </button></td>';
            return $options;
        })->addColumn('checkBox', function ($data) {
            $checkBox = '<td class="sorting_1">' .
                '<div class="custom-control custom-checkbox">' .
                '<input type="checkbox" class="mybox" id="checkBox_' . $data->id . '" onclick="check(' . $data->id . ')">' .
                '</div></td>';
            return $checkBox;
        })->editColumn('image', function ($data) {
            $icon = '<a href="'. getImageUrl('Product_images',$data->image).'" target="_blank">'
                .'<img  src="'. getImageUrl('Product_images',$data->image) . '" width="50px" height="50px"></a>';
            return $icon;

        })->rawColumns(['action' => 'action','image' => 'image','checkBox'=>'checkBox'])->make(true);
    }
}
