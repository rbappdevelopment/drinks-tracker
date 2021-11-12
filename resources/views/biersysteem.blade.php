@extends('includes.master')

@section('content')

<link href="/css/app.css" rel="stylesheet" type="text/css">

<?php use \App\Http\Controllers\BiersysteemController;
use App\Models\Bierstand;
use App\Models\Mutaties;
?>


{{-- TODO: Deze session werkend krijgen -> laten zien op wie is afgestreept. --}}
@if (session('status'))
    <div class="alert alert-success">
       <p> {{ session('status') }} </p>
    </div>
@endif

<div class="center">
  <button type="button" class="btn btn-secondary" data-toggle="modal" data-target="#exampleModal">
    Check mutaties <i class="fas fa-table"></i>
  </button>
</div>

<table>
    <thead>
        <tr>
            <td id="addpadding">Heer</td>
            <td id="addpadding">Bierstand</td>
            <td id="addpadding">#</td>
            <td id="addpadding"></td>
        </tr>
    </thead>
<tbody>
        @foreach($bierstand as $heer)
        <tr class="tr body"> {{-- Add data-toggle="modal" data-target="#exampleModal" to this tr for edit row entry (TODO admin screen to edit) --}}
                <td><a href="#" onclick="AddBeerToHeer('{{$heer->Heer}}', 1);return false;">{{$heer->Heer}}</a></td>
                <td><a href="#" onclick="AddBeerToHeer('{{$heer->Heer}}', 1);return false;">{{$heer->Bier}}</a></td>
                <td><b><a href="#" onclick="AddBeerToHeer('{{$heer->Heer}}', 1);return false;" id="localBierCount{{$heer->Heer}}"></b></a></td>
                <td><a href="#" onclick="AddBeerToHeer('{{$heer->Heer}}', 12);return false;" id="localBierCount{{$heer->Heer}}" class="addTwelve"><i class="fas fa-beer"></i>x12</a></td>
        </tr>
        @endforeach
</tbody>
</table>

<div id="result"></div> 

<br>
<button name="submit" class="btn btn-primary" onclick="return PostData()">Submit!</button>
  
  <!-- Modal -->
  <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Mutaties</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          {{-- TODO: Hier de mutaties tabel laden, order by date desc --}}
          <table>
            <thead>
                <tr style="font-size: 10pt; background: rgba(255, 221, 182, 0.747); ">
                  <td id=""><b>Afgestreept op</b></td>
                  <td id=""><b>Aantal afgestreept</b></td>
                  <td id=""><b>Totaal over</b></td>
                  <td id=""><b>Afgestreept door</b></td>
                  <td id=""><b>Datum & tijd</b></td>
                </tr>
            </thead>
        <tbody>
                @foreach($mutaties as $mutatie)
                <tr class="tr mutationsbody"> {{-- Add data-toggle="modal" data-target="#exampleModal" to this tr for edit row entry (TODO admin screen to edit) --}}
                  <td id="">@php $subjectName = Bierstand::where('id', $mutatie->HeerId)->value('Heer') @endphp {{ $subjectName }}</td>
                  <td id="">{{$mutatie->AantalBier}}</td>
                  <td id="">{{$mutatie->TotaalBierNaMutatie}}</td>
                  <td id="">@php $subjectedByName = Bierstand::where('id', $mutatie->GemuteerdDoorHeer)->value('Heer') @endphp {{ $subjectedByName }}</td>
                  <td id="">{{$mutatie->created_at}}</td>
                </tr>
                @endforeach
        </tbody>
        </table>

        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Terug</button>
        </div>
      </div>
    </div>
  </div>

@endsection

@section('scripts')
<script>

//Load personen from Db table Bierstand, start count with 0.
    var Personen = {
        Heren:[]
    };

@foreach($bierstand as $heer)  
    Personen.Heren.push({ 
        "Heer" : "{{ $heer->Heer }}",
        "Afgestreept"  : 0
    });
@endforeach

console.log("Gelade data uit Db: " + JSON.stringify(Personen));

let firstTap = new Boolean(true);

function AddBeerToHeer(heer, amount){

    //update value in object array & increment drinkcount
    var persoonBeverageCount = Personen.Heren.find(persoon => persoon.Heer === heer)['Afgestreept'] += amount;

    //update view
    document.getElementById('localBierCount'+heer).innerHTML = persoonBeverageCount;
    console.log("Tapped: " + heer + ", added on " + 'localBierCount'+heer+". Total bier voor deze heer: " + persoonBeverageCount);
    console.log("Personen array inhoud:" + JSON.stringify(Personen));     
}

function DisappearText(){
    document.getElementById('result').innerHTML = "";
}

function PostData()
{
console.log("Submitting data: " + Personen);

$.ajaxSetup({
      headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });  

    //send data
    $.ajax({
            type : "POST",
            url  : "biersysteem/update",
            data : { Personen }, //passing new bierstand values
            success: function(res){
                        document.getElementById('result').innerHTML = "Updated Db via POST!";
                        setTimeout(DisappearText, 1250);
                        location.reload();
                    },
        error: function(jqXHR, textStatus, errorThrown) {
           console.log(textStatus, errorThrown);
        }
        });
}
</script>
@endsection