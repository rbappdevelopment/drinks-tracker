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
          <tr class="tr body"> {{-- Add data-toggle="modal" data-target="#mutatiesModal" to this tr for edit row entry (TODO admin screen to edit) --}}
                  <td><a href="#" onclick="AddBeerToHeer('{{$heer->Heer}}', 1);return false;">
                    @if ($loop->first)
                      <i class="fas fa-crown"></i>
                    @endif
                    {{$heer->Heer}}
                  </a></td>
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

function AddBeerToHeer(heer, amount){

    //update value in object array & increment drinkcount
    var persoonBeverageCount = Personen.Heren.find(persoon => persoon.Heer === heer)['Afgestreept'] += amount;

    //update view
    document.getElementById('localBierCount'+heer).innerHTML = persoonBeverageCount;
    console.log("Tapped: " + heer + ", added on " + 'localBierCount'+heer+". Total bier voor deze heer: " + persoonBeverageCount);
    console.log("Personen array inhoud:" + JSON.stringify(Personen));     
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
                        window.location = "/biersysteem";
                    },
        error: function(jqXHR, textStatus, errorThrown) {
           console.log(textStatus, errorThrown);
        }
        });
}
</script>
@endsection