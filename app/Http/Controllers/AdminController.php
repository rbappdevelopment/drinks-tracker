<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Bierstand;
use App\Models\Mutaties;

class AdminController extends Controller
{
    public function __construct(){
        $this->middleware('auth');
    }

    public function LoadAdminPage(){
        //Load mutaties for mutaties button in header
        $mutaties = Mutaties::orderBy('created_at', 'desc')->paginate(50);

        return view('admin.admin', compact('mutaties'));
    }

    public function LoadAdminPage_AddPerson(){
        $mutaties = Mutaties::orderBy('created_at', 'desc')->paginate(50);

        return view('admin.admin-addperson', compact('mutaties'));
    }

    public function AddPerson(Request $req){

        $req->validate([
            'Heer' => 'required|max:50|unique:Bierstand'
        ]);

        $person = new Bierstand;
        $person->Heer = $req->Heer;
        $person->Bier = 0;
        $person->save();

        return redirect()->back()
        ->with('success', 'Persoon is aangemaakt: ')
        ->with('persoonadded', $req->Heer);
    }

}

// user roles (for admin) --> https://www.youtube.com/watch?v=kZOgH3-0Bko