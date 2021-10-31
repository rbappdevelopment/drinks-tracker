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

    
}
// Was bij deze tut (begin part 3: 'Laravel for Beginners - Part 3 (Models and Migrations)'): https://www.youtube.com/watch?v=mQGJ9QIJ-3U