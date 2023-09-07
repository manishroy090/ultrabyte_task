<?php

namespace App\Http\Controllers;

use App\Models\Page;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
// use Intervention\Image\ImageManagerStatic as Image;


class PagesController extends Controller
{
    public function index()
    {
        return view('pages', ['pagesdata' => Page::all()]);
    }
    public function add(Request $request)
    {
        $data =  validator::make($request->all(), [
            'tittle' => 'required',
            'img' => "required|dimensions:width=100,height=100"
        ], [
            'Tittle' => "title is Required",
            'Img.required' => "img is Required",
            'img.dimensions'=>"Img Must be 100px X 100px"

        ])->validate();

        $filename = time() . "product." . $request->file('img')->extension();
        $request->file('img')->storeAs('uploads', $filename, 'public');
        $data['img'] = $filename;
        $data['slug'] = Str::slug($request->tittle, '-', 'e');
        $createdData =   Page::create($data);
        $createdData['img'] = asset('/storage/uploads/' . $createdData['img']);
        $forIndex = count(Page::all());
        return response()->json(['createdData' => $createdData, 'index' => $forIndex, 'action'=>'Added']);
    }
    public function edit($id)
    {
        $data = Page::where('id', $id)->first();
        $data['img'] = asset('/storage/uploads/' . $data['img']);
        return response()->json($data);
    }
    public function update(Request $request, $id)
    {

        $data =  validator::make($request->all(), [
            'tittle' => 'required',
            'img' => "nullable"
        ], [
            'tittle' => "Title is Required",
        ])->validate();
        if ($request['img'] != null) {
            $fileName = time() . "product." . $request->file('img')->extension();;
            $request->file('img')->storeAs('uploads', $fileName, 'public');
            $data['img'] = $fileName;
        }
        Page::where('id', $id)->update($data);
        $data['img'] = asset('/storage/uploads/' . Page::where('id', $id)->value('img'));
        return response()->json(['updatedData' => $data, 'id' => $id, 'action' => 'Updated']);
    }
    public function delete($id)
    {
        Page::where('id', $id)->delete();
        return response()->json(['id'=>$id, 'action' => 'Deleted']);
    }
}
