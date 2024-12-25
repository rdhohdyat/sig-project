<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreFasilitasRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true; // Ubah menjadi true jika user diizinkan menggunakan request ini
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'no' => 'required',
            'kategori' => 'required',
            'kecamatan' => 'required',
            'nama' => 'required',
            'alamat' => 'required',
            'jam_buka' => 'required',
            'jam_tutup' => 'required',
            'longitude' => 'required',
            'latitude' => 'required',
            'foto' => 'nullable',
            'keterangan' => 'nullable',
        ];
    }

    /**
     * Custom messages for validation errors.
     *
     * @return array<string, mixed>
     */
    public function messages()
    {
        return [
            'no.required' => 'Nomor fasilitas harus diisi.',
            'kategori.required' => 'Kategori harus diisi.',
            'kecamatan.required' => 'Kecamatan harus diisi.',
            'nama.required' => 'Nama fasilitas harus diisi.',
            'alamat.required' => 'Alamat harus diisi.',
            'jam_buka.required' => 'Jam buka harus diisi.',
            'jam_buka.date_format' => 'Format jam buka harus HH:mm.',
            'jam_tutup.required' => 'Jam tutup harus diisi.',
            'jam_tutup.date_format' => 'Format jam tutup harus HH:mm.',
            'jam_tutup.after' => 'Jam tutup harus setelah jam buka.',
            'longitude.required' => 'Longitude harus diisi.',
            'longitude.numeric' => 'Longitude harus berupa angka.',
            'latitude.required' => 'Latitude harus diisi.',
            'latitude.numeric' => 'Latitude harus berupa angka.',
            'foto.image' => 'File foto harus berupa gambar.',
            'foto.mimes' => 'Foto harus berformat jpeg, png, jpg, atau gif.',
            'foto.max' => 'Ukuran foto tidak boleh lebih dari 2MB.',
        ];
    }
}
