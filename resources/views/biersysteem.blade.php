@extends('includes.master')

@section('content')

<?php use \App\Http\Controllers\BiersysteemController; ?>

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

@endsection

@section('scripts')
<script>
var Personen = {"Bersee":0, "Verburg":0, "Pont":0};
let firstTap = new Boolean(true);

function addBeerToHeer(heer){
    Personen[heer]++;
    //Personen.push(heer);
    document.getElementById('localBierCount'+heer).innerHTML = Personen[heer];
    console.log("Tapped: " + heer + ", added on " + 'localBierCount'+heer+". Total bier voor deze heer: " + Personen[heer]);
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