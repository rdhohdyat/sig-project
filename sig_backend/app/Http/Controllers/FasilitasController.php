<?php

namespace App\Http\Controllers;

use App\Models\Fasilitas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class FasilitasController extends Controller
{

    public function index() {
        $fasilitas = Fasilitas::all();
        return response()->json($fasilitas);
    }
    public function store(Request $request)
    {
        $data = $request->all();

        $chunks = array_chunk($data, 100);

        try {
            foreach ($data as $item) {
                $this->validateData($item);
            }
            foreach ($chunks as $chunk) {
                Fasilitas::insert($chunk);
            }
            return response()->json(['message' => 'Data berhasil disimpan'], 201);
        } catch (\Exception $e) {
            try {
                foreach ($chunks as $chunk) {
                    DB::table('fasilitas')->insert($chunk);
                }
                return response()->json(['message' => 'Data berhasil disimpan dengan Query Builder'], 201);
            } catch (\Exception $dbException) {
                Log::error($dbException->getMessage());
                return response()->json(['error' => $dbException->getMessage()], 500);
            }
        }
    }

    /**
     * Validasi setiap item di array data dengan nullable.
     *
     * @param array $item
     * @return void
     * @throws \Illuminate\Validation\ValidationException
     */
    private function validateData(array $item)
    {
        $rules = [
            'no' => 'nullable',
            'kategori' => 'nullable',
            'kecamatan' => 'nullable',
            'nama' => 'nullable',
            'alamat' => 'nullable',
            'jam_buka' => 'nullable',
            'jam_tutup' => 'nullable',
            'longitude' => 'nullable',
            'latitude' => 'nullable',
            'foto' => 'nullable',
            'keterangan' => 'nullable',
        ];

        validator($item, $rules)->validate();
    }
}
