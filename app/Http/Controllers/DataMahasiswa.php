<?php

namespace App\Http\Controllers;

use App\Models\DataMahasiswa as ModelDataMahasiswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;


class DataMahasiswa extends Controller
{
    function index(){
        $data = ModelDataMahasiswa::all();
        return view('data_mahasiswa.index', ['data' => $data]);
    }
    function tambah(){
        return view('data_mahasiswa.tambah');
    }
    function edit($id){
        $data = ModelDataMahasiswa::find($id);
        return view('data_mahasiswa.edit', ['data' => $data]);
    }
    function hapus(Request $request){
        ModelDataMahasiswa::where('id', $request->id)->delete();

        Session::flash('success', 'Berhasil hapus Data');

        return redirect('/datamahasiswa');
    }
    // new
    function create(Request $request)
    {
    $request->validate([
    'nopts' => 'required|numeric|min:6',
    'namapts' => 'required',
    'fakultas' => 'required',
    'prodi' => 'required',
    'jurusan' => 'required',
    ], [
    'nopts.required' => 'Name Wajib Di isi',
    'nopts.min' => 'Bidang name minimal harus 6 karakter.',
    'fakultas.required' => 'Email Wajib Di isi',
    // 'email.email' => 'Format Email Invalid',
    'prodi.required' => 'Nim Wajib Di isi',
    // 'nim.max' => 'NIM max 11 Digit',
    'jurusan.required' => 'Jurusan Wajib Di isi',
    // 'angkatan.min' => 'Masukan 2 angka Akhir dari Tahun misal: 2022 (22)',
    // 'angkatan.max' => 'Masukan 2 angka Akhir dari Tahun misal: 2022 (22)',
    // 'jurusan.required' => 'Jurusan Wajib Di isi',
    ]);

    ModelDataMahasiswa::insert([
    'nopts' => $request->nopts,
    'namapts' => $request->namapts,
    'fakultas' => $request->fakultas,
    'prodi' => $request->prodi,
    'jurusan' => $request->jurusan,
    ]);

    Session::flash('success', 'Data berhasil ditambahkan');

    return redirect('/datamahasiswa')->with('success', 'Berhasil Menambahkan Data');
    }
    function change(Request $request)
    {
    $request->validate([
    'nopts' => 'required|numeric|min:6',
    'namapts' => 'required',
    'fakultas' => 'required',
    'prodi' => 'required',
    'jurusan' => 'required',
    ], [
    'nopts.required' => 'Name Wajib Di isi',
    'nopts.min' => 'Bidang name minimal harus 6 karakter.',
    'fakultas.required' => 'Email Wajib Di isi',
    // 'email.email' => 'Format Email Invalid',
    'prodi.required' => 'Nim Wajib Di isi',
    // 'nim.max' => 'NIM max 11 Digit',
    'jurusan.required' => 'Jurusan Wajib Di isi',
    // 'angkatan.min' => 'Masukan 2 angka Akhir dari Tahun misal: 2022 (22)',
    // 'angkatan.max' => 'Masukan 2 angka Akhir dari Tahun misal: 2022 (22)',
    // 'jurusan.required' => 'Jurusan Wajib Di isi',
    ]);

    $datamahasiswa = ModelDataMahasiswa::find($request->id);

    $datamahasiswa->nopts = $request->nopts;
    $datamahasiswa->namapts = $request->namapts;
    $datamahasiswa->fakultas = $request->fakultas;
    $datamahasiswa->prodi = $request->prodi;
    $datamahasiswa->jurusan = $request->jurusan;
    $datamahasiswa->save();

    Session::flash('success', 'Berhasil Mengubah Data');

    return redirect('/datamahasiswa');
    }
}
