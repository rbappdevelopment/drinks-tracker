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
        <td><a href="#" onclick="AddBeerToHeer('{{$heer->Heer}}');return false;">{{$heer->Heer}}</a></td>
        <td><a href="#" onclick="AddBeerToHeer('{{$heer->Heer}}');return false;">{{$heer->Bier}}</a></td>
        <td><a href="#" id="localBierCount{{$heer->Heer}}"></a></td>
        </tr>
        @endforeach
</tbody>
</table>

<br>
<button name="submit" id="submit" onclick="return PostData()">Submit!</button>

<!-- <a href="" type="button" id="submit">Submit!</a> -->
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

function AddBeerToHeer(heer){

    //update value in object array & increment drinkcount
    var persoonBeverageCount = Personen.Heren.find(persoon => persoon.Heer === heer)['Afgestreept'] += 1;

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
                        alert("Succesvol bier afgestreept!");
                    },
        error: function(jqXHR, textStatus, errorThrown) {
           console.log(textStatus, errorThrown);
        }
        });
}
</script>
@endsection