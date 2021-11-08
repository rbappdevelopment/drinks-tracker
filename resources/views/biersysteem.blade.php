@extends('includes.master')

@section('content')

<?php use \App\Http\Controllers\BiersysteemController;
use App\Models\Bierstand;
?>

<br>

<table>
    <thead>
        <tr>
            <td>Heer</td>
            <td>Bierstand</td>
            <td>#</td>
        </tr>
    </thead>
<tbody>
        @foreach($bierstand as $heer)
        <tr class="tr body">
        <td><a href="#" onclick="addBeerToHeer('{{$heer->Heer}}');return false;">{{$heer->Heer}}</a></td>
        <td><a href="#" onclick="addBeerToHeer('{{$heer->Heer}}');return false;">{{$heer->Bier}}</a></td>
        <td><a href="#" id="localBierCount{{$heer->Heer}}"></a></td>
        </tr>
        @endforeach
</tbody>
</table>

<a href="" id="submit">Submit!</a>

<!-- The result of the search will be rendered inside this div -->
<div id="result"></div>

<pre>{{ $user = Bierstand::find(1); }}</pre>

@endsection

@section('scripts')
<script>

//Load personen from Db table Bierstand, start count with 0.

    //@foreach($bierstand as $heer)
    //{{ $heer->Heer }}:0,
    //@endforeach

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

function addBeerToHeer(heer){
    //Find and refer person in object array
    var persoonBeverageCount = Personen.Heren.find(persoon => persoon.Heer === heer)['Afgestreept'];

    //increment drink count
    persoonBeverageCount.Value = persoonBeverageCount++;

    //update value in object array
    Personen.Heren.find(persoon => persoon.Heer === heer)['Afgestreept'] += 1;

    //update view
    document.getElementById('localBierCount'+heer).innerHTML = persoonBeverageCount;
    console.log("Tapped: " + heer + ", added on " + 'localBierCount'+heer+". Total bier voor deze heer: " + persoonBeverageCount);
    console.log("Personen array inhoud:" + JSON.stringify(Personen));

    $.ajaxSetup({
      headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });  

    //send data
    $.ajax({
            type : "POST",  //type of method
            url  : "biersysteem/update",  //your page
            data : { Personen },// passing the values
            success: function(res){
                        document.getElementById('result').innerHTML = "Updated Db via POST!";
                        setTimeout(DisappearText, 1250);
                    },
        error: function(jqXHR, textStatus, errorThrown) {
           console.log(textStatus, errorThrown);
        }
        });
}

function DisappearText(){
    document.getElementById('result').innerHTML = "";
}

$("#submit").click(function(){
$.ajaxSetup({
      headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });  

    //send data
    $.ajax({
            type : "POST",  //type of method
            url  : "biersysteem/update",  //your page
            data : { Personen },// passing the values
            success: function(res){
                        document.getElementById('result').innerHTML = "Updated Db via POST!";
                        setTimeout(DisappearText, 1250);
                    },
        error: function(jqXHR, textStatus, errorThrown) {
           console.log(textStatus, errorThrown);
        }
        });
    });
</script>
@endsection