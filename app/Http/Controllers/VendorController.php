<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class VendorController extends Controller
{
    public function show()
    {
 //  echo"hi";exit;
        return view('admin.vendor.vendor');
    }
}
