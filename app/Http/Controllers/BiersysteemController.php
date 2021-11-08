<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Bierstand;
class BiersysteemController extends Controller
{
    
    public function LoadBierstandData(){

        //Check if Db connection is successful
        try {
            DB::connection()->getPdo();
        } catch (\Exception $ex) {
            die("Could not connect to the database, the following error has occured:" . $ex );
        }

        //Load all data from Bierstand (main) table
        $bierstand = Bierstand::get();

        return view ('biersysteem', compact('bierstand'));
    }

    public function UpdateBierstand(Request $request){
        //echo print_r($request->Personen);


        //return redirect('/');
        //TODO: Update Bierstand table in db via the array content

        //$personen = $request->Personen;

        //return redirect('/');

    }
    
}