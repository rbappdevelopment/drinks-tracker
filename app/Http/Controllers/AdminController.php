<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Bierstand;

class AdminController extends Controller
{
    
    public function LoadAdminPage(){
        return view('admin');
    }

    public function LoadAdminPage_AddPerson(){
        return view('admin-addperson');
    }

    public function AddPerson(Request $req){
        $person = new Bierstand;
        $person->Heer = $req->Heer;
        $person->Bier = 0;
        $person->save();

        return redirect()->back()->with('success', 'Persoon is aangemaakt: ' . $req->Heer . ".");
    }

}