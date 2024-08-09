<?php

namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Http\Request;

class DownroleController extends Controller
{
    function index($id)
    {
    $data = User::find($id);
    $data->role = 'user';
    $data->save();
    return redirect('/usercontrol')->with('success', 'Berhasil Mengubah Role.');
    }
}
