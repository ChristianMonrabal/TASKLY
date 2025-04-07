<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TrabajoController extends Controller
{
    
    public function index(){
        return view('trabajo.index');
    }


}
