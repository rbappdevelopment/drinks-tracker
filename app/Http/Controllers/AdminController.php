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

    public function LoadAdminPage_AddPerson(){
        $mutaties = Mutaties::orderBy('created_at', 'desc')->paginate(50);

        return view('admin.admin-addperson', compact('mutaties'));
    }

    public function GetMutationsForUser($id){
        $mutaties = Mutaties::where('HeerId', $id)->orderBy('created_at', 'desc')->paginate(50);
        return view('includes.mutationstable', compact('mutaties'));
    }

    public function UpdateValue($id, Request $req){
        try {
            $Bierstand = Bierstand::find($id);
            $oldValue = $Bierstand->Bier;
            $Bierstand->Bier += $req->changeDrinksAmount;
            $Bierstand->save();

            //log into db table Mutaties
            $mutatie = new Mutaties;
            $mutatie->HeerId = $id;
            if($req->changeDrinksAmount<0){
                $mutatie->AantalBier = $req->changeDrinksAmount;
            }else{
                $mutatie->AantalBier = $req->changeDrinksAmount;
            }
            $mutatie->TotaalBierNaMutatie = $Bierstand->Bier;
            $mutatie->GemuteerdDoorHeer = auth()->user()->name;
            $mutatie->IsAdminUpdate = true;
            $mutatie->save();
        }
        catch (\Exception $ex){
            report($ex);

            return redirect('biersysteem/admin/editperson')
            ->with('failUpdateTitle', 'Er is iets fout gegaan!' . $ex . 'Bierstand is niet geüpdatet! Check je verbinding en probeer het opnieuw! Bij twijfel, check de mutaties rechtsbovenin!');
        }

        return redirect('biersysteem/admin/editperson')
        ->with('successfulUpdateTitle', 'Bierstand geüpdatet!')
        ->with('successfulUpdateBody', 'Bierstand aangepast voor  ' . $Bierstand->where('id', $id)->value('Heer') . ':  ' . $req->changeDrinksAmount . " is bij " . $oldValue . " opgeteld.")
        ->with('successfulUpdateEnd', "Totaal is nu: " . $Bierstand->Bier);
    }
    
    public function UpdateName($id, Request $req){
        try {
            $Bierstand = Bierstand::find($id);
            $oldValue = $Bierstand->Heer;
            $Bierstand->Heer = $req->changeName;
            $Bierstand->save();
        }
        catch (\Exception $ex){
            report($ex);

            return redirect('biersysteem/admin/editperson')
            ->with('failNameTitle', 'Er is iets fout gegaan:')
            ->with('failNameBody', 'Naam is niet geüpdatet! Check je verbinding en probeer het opnieuw.');
        }

        return redirect('biersysteem/admin/editperson')
        ->with('successfulNameTitle', 'Naam geüpdatet!')
        ->with('successfulNameBody', 'Naam is aangepast van  ' . $oldValue . ' naar  ' . $Bierstand->Heer . '.');
    }

    public function DeletePerson($id, Request $req){
        try {
            $oldName = Bierstand::where('id', $id)->value('Heer');
            $Bierstand = Bierstand::where('id', $id)->delete();
        }
        catch (\Exception $ex){
            report($ex);

            return redirect('biersysteem/admin/editperson')
            ->with('failDeleteTitle', 'Er is iets fout gegaan: ')
            ->with('failDeleteBody', 'Persoon is niet verwijderd! Check je verbinding en probeer het opnieuw.');
        }

        return redirect('biersysteem/admin/editperson')
        ->with('successfulDeleteTitle', 'Succesvol persoon verwijderd: ')
        ->with('successfulDeleteBody', $oldName . '.');
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

    public function LoadAdminPage_EditPerson(){
        $bierstand = Bierstand::orderBy('TotaalOnzichtbaar', 'desc')->get();
        $mutaties = Mutaties::orderBy('created_at', 'desc')->paginate(50);

        return view('admin.admin-editperson', compact('mutaties', 'bierstand'));
    }    
}