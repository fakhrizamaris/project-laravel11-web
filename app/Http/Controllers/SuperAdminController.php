<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SuperAdminController extends Controller
{
    function index(){
    return view('pointakses/superadmin/index');
    }
}
