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

        return view('biersysteem', compact('bierstand'));
    }

    public function UpdateBierstand(Request $request){

        $input = $request->collect();
        $requestPersonen = $request->input('Personen.Heren.*');

        foreach($requestPersonen as $name){
            //TODO: Add WhenHas() check to see if name is in the db

            $data = Bierstand::where('Heer', $name["Heer"]);
            $dataBier = $data->value('Bier');
            
            //deduct beverage from person
            $dataBier -= $name["Afgestreept"];
            $data->Bier = $dataBier;

            //save to db table Bierstand
            $data->update(['Bier' => $dataBier]);

            echo "Where dataBier = " . $dataBier . " & "; 

            //TODO: Add third value of whoever is currently logged in (for the mutaties table)
            //TODO: Something like getCurrentlyLoggedInUser->name
        }
        return redirect('/biersysteem');
    }
    
    public function LoadAdminPage(){
        return view('admin');
    }

    public function LoadAdminPage_AddPerson(){
        return view('admin.addperson');
    }

}