<?php

namespace App\Http\Controllers;

use App\Models\Fasilitas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class FasilitasControllerAdmin extends Controller
{
    public function index()
    {
        $fasilitas = Fasilitas::all();
        return view('index', compact('fasilitas'));
    }

    public function create()
    {

    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'kategori' => 'required|string|max:255',
            'kecamatan' => 'required|string|max:255',
            'alamat' => 'required|string',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'foto' => 'nullable',
            'keterangan' => 'nullable|string',
            'jam_buka' => 'required',
            'jam_tutup' => 'required',
        ]);

        $lastNo = Fasilitas::max('no') ?? 0;

        Fasilitas::create([
            'no' => $lastNo + 1,
            'nama' => $request->nama,
            'kategori' => $request->kategori,
            'kecamatan' => $request->kecamatan,
            'alamat' => $request->alamat,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'foto' => $request->foto,
            'keterangan' => $request->keterangan,
            'jam_buka' => $request->jam_buka,
            'jam_tutup' => $request->jam_tutup,
        ]);

        return redirect()->back()->with('success', 'Fasilitas berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'kategori' => 'required|string|max:255',
            'kecamatan' => 'required|string|max:255',
            'alamat' => 'required|string',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'foto' => 'nullable',
            'keterangan' => 'nullable|string',
            'jam_buka' => 'required',
            'jam_tutup' => 'required',
        ]);

        $fasilitas = Fasilitas::findOrFail($id);
        $fasilitas->update($request->all());

        return redirect()->back()->with('success', 'Fasilitas berhasil diperbarui.');
    }


    public function destroy($id)
    {
        $fasilitas = Fasilitas::findOrFail($id);
        $fasilitas->delete();

        return redirect()->back()->with('success', 'Fasilitas berhasil dihapus.');
    }
}
