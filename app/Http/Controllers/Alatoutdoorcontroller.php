<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Alatoutdoor;
use App\Models\Kategori;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class Alatoutdoorcontroller extends Controller
{
    //get data alat outdoor
    public function index(){
    $alatoutdoor = Alatoutdoor::latest()->simplePaginate(5);

    //render view with data alat outdoor
    return view('dashboard.alatoutdoor.index', compact('alatoutdoor'));
}

    public function create(){

        $kategori = Kategori::get();

        $alatoutdoor = Alatoutdoor::all();

            $q = DB::table('alatoutdoors')->select(DB::raw('MAX(RIGHT(id_alatoutdoor,2)) as kode'));
            $kd ="";
            if($q->count()>0)
            {
                foreach($q->get() as $k)
                {
                    $tmp = ((int)$k->kode)+1;
                    $kd = sprintf("%02s", $tmp);
                }
            }
            else
            {
                $kd = "01";
            }

        return view('dashboard.alatoutdoor.create', ['kategori' => $kategori], compact('alatoutdoor','kd'));
    }

    public function store(Request $request)
    {
        //validate form
        $this->validate($request, [
            'id_alatoutdoor'=>'required',
            'nama_alat'=>'required',
            'id_kategori'=>'required',
            'spesifikasi'=>'required',
            'deskripsi'=>'required',
            'stok'=>'required',
            'harga_sewa'=>'required',
            'merk'=>'required',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048'
        ]);

        //upload image
        $image = $request->file('image');
        $image->storeAs('public/alatoutdoor1', $image->hashName());

        // $id_alatoutdoor=Alatoutdoor::orderBy('id_alatoutdoor', 'DESC')->first();
        // $id_alatoutdoorbaru=(int)substr($id_alatoutdoor->id_alatoutdoor,2)+(int)1;
        //create post
        Alatoutdoor::create([
            'id_alatoutdoor'=>$request->id_alatoutdoor,
            'nama_alat'=>$request->nama_alat,
            'id_kategori'=>$request->id_kategori,
            'spesifikasi'=>$request->spesifikasi,
            'deskripsi'=>$request->deskripsi,
            'stok'=>$request->stok,
            'harga_sewa'=>$request->harga_sewa,
            'merk'=>$request->merk,
            'image' => $image->hashName(),
        ]);
    

        //redirect to index
        return redirect()->route('alatoutdoor.index')->with(['success' => 'Data Berhasil Disimpan!']);
    }

    public function edit(Alatoutdoor $alatoutdoor)
    {
        $kategori = Kategori::get();
        return view('dashboard.alatoutdoor.edit', compact('alatoutdoor'), ['kategori'=>$kategori]);
    }

    public function update(Request $request, Alatoutdoor $alatoutdoor)
    {
        //validate form
        $this->validate($request, [
            'id_alatoutdoor'=>'required',
            'nama_alat'=>'required',
            'id_kategori'=>'required',
            'spesifikasi'=>'required',
            'deskripsi'=>'required',
            'stok'=>'required',
            'harga_sewa'=>'required',
            'merk'=>'required',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048'
        ]);

        //check if image is uploaded
        if ($request->hasFile('image')) {

            //upload new image
            $image = $request->file('image');
            $image->storeAs('public/alatoutdoor1', $image->hashName());

            //delete old image
            Storage::delete('public/alatoutdoor1/'.$alatoutdoor->image);

            //update post with new image
            $alatoutdoor->update([
                'nama_alat'=>$request->nama_alat,
                'id_kategori'=>$request->id_kategori,
                'spesifikasi'=>$request->spesifikasi,
                'deskripsi'=>$request->deskripsi,
                'stok'=>$request->stok,
                'harga_sewa'=>$request->harga_sewa,
                'merk'=>$request->merk,
                'image' => $image->hashName(),
            ]);

        } else {

            //update post without image
            $alatoutdoor->update([
                'nama_alat'=>$request->nama_alat,
                'id_kategori'=>$request->id_kategori,
                'spesifikasi'=>$request->spesifikasi,
                'deskripsi'=>$request->deskripsi,
                'stok'=>$request->stok,
                'harga_sewa'=>$request->harga_sewa,
                'merk'=>$request->merk,
            ]);
        }

        //redirect to index
        return redirect()->route('alatoutdoor.index')->with(['success' => 'Data Berhasil Diubah!']);
    }

    public function destroy(Alatoutdoor $alatoutdoor)
    {
        //delete image
        Storage::delete('public/alatoutdoor1/'. $alatoutdoor->image);

        //delete post
        $alatoutdoor->delete();

        //redirect to index
        return redirect()->route('alatoutdoor.index')->with(['success' => 'Data Berhasil Dihapus!']);
    }

    public function show(Alatoutdoor $alatoutdoor)
    {
        return view('details', [
            'alatoutdoor' => $alatoutdoor
        ]);
    }
};