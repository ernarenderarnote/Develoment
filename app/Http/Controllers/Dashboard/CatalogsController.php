<?php

namespace App\Http\Controllers\Dashboard;

use Illuminate\Http\Request;
use Auth;

use App\Http\Controllers\Controller;

class CatalogsController extends Controller
{

    /*
     *
     * @return \Illuminate\Http\Response
     */
    public function index(){
            return view('dashboard.catalogs');
    }
}

