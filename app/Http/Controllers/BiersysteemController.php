<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class BiersysteemController extends Controller
{
    
    public function ShowUsers(){
        echo 'Hi.';
        return view ('biersysteem');
    }

}
