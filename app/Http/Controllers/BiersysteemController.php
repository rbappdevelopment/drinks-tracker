<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Bierstand;
use App\Models\Mutaties;
class BiersysteemController extends Controller
{
    public function __construct(){
        $this->middleware('auth');

        //Use below to exclude specific views
        //$this->middleware('auth', ['except' => ['index']]);
    }

    public function LoadBierstandData(){

        //Check if Db connection is successful
        try {
            DB::connection()->getPdo();
        } catch (\Exception $ex) {
            die("Could not connect to the database, the following error has occured:" . $ex );
        }

        //Load all data from Bierstand (main) table
        $bierstand = Bierstand::orderBy('TotaalOnzichtbaar', 'desc')->get();

        //Load mutaties for mutaties button in header
        $mutaties = Mutaties::orderBy('created_at', 'desc')->paginate(50);

        return view('biersysteem', compact('bierstand', 'mutaties'));
    }

    public function UpdateBierstand(Request $request){

        $input = $request->collect();
        $requestPersonen = $request->input('Personen.Heren.*');

        $PeopleWithBeerAdded = [];

        foreach($requestPersonen as $name){
                //TODO: Add WhenHas() check to see if name is in the db

                $data = Bierstand::where('Heer', $name["Heer"]);
                $dataBier = $data->value('Bier');
                $dataTotaalOnzichtbaar = $data->value('TotaalOnzichtbaar');
                
                //if user has drink deducted (>0), process the mutation
                if($name["Afgestreept"]>0){

                    //deduct beverage from person
                    $dataBier -= $name["Afgestreept"];
                    $data->Bier = $dataBier;

                    //add deduction to invisible deduction total (for displaying the order) 
                    $dataTotaalOnzichtbaar += $name["Afgestreept"];
                    $data->TotaalOnzichtbaar = $dataTotaalOnzichtbaar;

                    //save to db table Bierstand
                    $data->update([
                        'Bier' => $dataBier,
                        'TotaalOnzichtbaar' => $dataTotaalOnzichtbaar]
                    );

                    //TODO: store whether or not person had drinks added to show on view
                    array_push($PeopleWithBeerAdded, $name);

                    echo "Where dataBier = " . $dataBier . " & "; 
                    
                    //log into db table Mutaties
                    $mutatie = new Mutaties;
                    $mutatie->HeerId = Bierstand::where('Heer', $name["Heer"])->value('id');
                    $mutatie->AantalBier = -1*$name["Afgestreept"];
                    $mutatie->TotaalBierNaMutatie = $dataBier;
                    $mutatie->GemuteerdDoorHeer = auth()->user()->name;
                    $mutatie->save();
                }
    }
    return redirect('/biersysteem')->with('status', 'Er is afgestreept op ... [array van mensen + # drinks hier] !'); // TODO: Send array of people with beverages drank to show on view
}

}