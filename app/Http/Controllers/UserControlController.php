<?php

namespace App\Http\Controllers;

use App\Mail\AuthMail;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserControlController extends Controller
{
    
    function index()
    {
        $data = User::all();
        foreach($data as $user){
            // Set role_level berdasarkan role pengguna
            if ($user->role == 'superadmin') {
            $user->role_level = 3;
            } elseif ($user->role == 'admin') {
            $user->role_level = 2;
            } else {
            $user->role_level = 1;
            }
            $user->save();
        }

        $data = User::orderBy('role_level', 'desc')->get();
        return view('user_control.index', ['uc' => $data]);
    }
    function roleLevel(Request $request, $id){
        $user = User::findOrFail($id);

        // Set role_level berdasarkan role pengguna
        if ($user->role == 'superadmin') {
        $user->role_level = 3;
        } elseif ($user->role == 'admin') {
        $user->role_level = 2;
        } else {
        $user->role_level = 1;
        }

        $user->save();

        return redirect()->back()->with('success', 'User role updated successfully!');
    }

    function tambah()
    {
        return view('user_control.tambah');
    }
    function create(Request $request)
    {
        $str = Str::random(100);
        $gambar = '';

        $request->validate([
            'fullname' => 'required|min:4',
            'email' => 'required|email',
            'password' => 'required|min:6',
        ], [
            'fullname.required' => 'Full Name Wajib Di isi',
            'fullname.min' => 'Bidang Full Name minimal harus 4 karakter.',
            'email.required' => 'Email Wajib Di isi',
            'email.email' => 'Format Email Invalid',
            'password.required' => 'Password Wajib Di isi',
            'password.min' => 'Password minimal harus 6 karakter.',
        ]);


        // if ($request->hasFile('gambar')) {

        //     $request->validate(['gambar' => 'mimes:jpeg,jpg,png,gif|image|file|max:1024']);

        //     $gambar_file = $request->file('gambar');
        //     $foto_ekstensi = $gambar_file->extension();
        //     $nama_foto = date('ymdhis') . "." . $foto_ekstensi;
        //     $gambar_file->move(public_path('picture/accounts'), $nama_foto);
        //     $gambar = $nama_foto;
        // } else {
        //     $gambar = "user.jpeg";
        // }

        $accounts = User::create([
            'fullname' => $request->fullname,
            'email' => $request->email,
            'password' => $request->password,
            'verify_key' => $str,
            // 'gambar' => $gambar,
        ]);

        $details = [
            'nama' => $accounts['fullname'],
            'role' => 'user',
            'datetime' => date('Y-m-d H:i:s'),
            'website' => 'Project Laravel Fakhri',
            // 'url' => 'http://' . request()->getHttpHost() . "/" . "verify/" . $accounts->verify_key,
            'url' => url('verify/' . $accounts['verify_key']),
        ];


        Mail::to($request->email)->send(new AuthMail($details));

        Session::flash('success', 'User berhasil ditambahkan , Harap verifikasi akun sebelum di gunakan.');

        return redirect('/usercontrol');
    }

    function edit($id)
    {
        $data = User::where('id', $id)->get();
        return view('user_control.edit', ['uc' => $data]);
    }
    function change(Request $request)
    {
        $request->validate([
            // 'gambar' => 'image|file|max:1024',
            'fullname' => 'required|min:4',
            'password' => 'required',
            'passwordbaru' => 'required|min:7|confirmed',
        ], [
            // 'gambar.image' => 'File Wajib Image',
            // 'gambar.file' => 'Wajib File',
            // 'gambar.max' => 'Bidang gambar tidak boleh lebih besar dari 1024 kilobyte',
            'fullname.required' => 'Nama Wajib Di isi',
            'fullname.min' => 'Bidang nama minimal harus 4 karakter.',
            'password.required' => 'Password lama wajib diisi',
            'passwordbaru.min' => 'Password baru minimal harus 7 karakter',
            'passwordbaru.confirmed' => 'Konfirmasi Password baru tidak cocok',
        ]);


        $user = User::find($request->id);
        if (!Hash::check($request->password, $user->password)) {
        return back()->withErrors(['password' => 'Password lama tidak sesuai']);
        }

        $user->fullname = $request->fullname;
        $user->password = Hash::make($request->passwordbaru);
        $user->save();

        Session::flash('success', 'User berhasil diedit');

        return redirect('/usercontrol');
    }
    function hapus(Request $request)
    {
        User::where('id', $request->id)->delete();

        Session::flash('success', 'Data berhasil dihapus');

        return redirect('/usercontrol');
    }
}
