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

<div id="showUpdateSuccess" style="display: none;">
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <strong>Succes!</strong> Er is afgestreept!
    </div>
</div>

<div id="showUpdateFail" style="display: none;">
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <strong>Fout: </strong> er is iets misgegaan, het afstrepen is <b>niet</b> opgeslagen!
        <br>Check je verbinding en probeer het opnieuw!
    </div>
</div>

<div id="sendRequestOverlay" style="display: none;">
    <div id="sendRequestOverlaySpinner" style="display: none;">
        <div id="loading-spinner" class="spinner-border text-secondary" role="status" style="display: block; margin-left: auto; margin-right: auto;">
        </div>
    </div>
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
          <tr class="tr body">
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

<div class="footer">
    <b id="showTotalStatic" style="display: none;">Totaal:</b>
    <br>
    <span id="EditList"></span>
    <button name="submit" class="btn btn-primary" style="float: right; margin: 0px 15px 15px 0px;" onclick="return PostData()">Afstrepen!</button>
</div>

<br>
<br>
<br>
<div class="enlargePage"></div>
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

$(document).ready(function() {
    if (window.location.href.indexOf("?success") > -1) {
        $('#showUpdateSuccess').show();
    }
});

console.log("Gelade data uit Db: " + JSON.stringify(Personen));

function AddBeerToHeer(heer, amount){
    //update value in object array & increment drinkcount
    var persoonBeverageCount = Personen.Heren.find(persoon => persoon.Heer === heer)['Afgestreept'] += amount;

    //update view
    document.getElementById('localBierCount'+heer).innerHTML = persoonBeverageCount;
    console.log("Tapped: " + heer + ", added on " + 'localBierCount'+heer+". Total bier voor deze heer: " + persoonBeverageCount);
    console.log("Personen array inhoud:" + JSON.stringify(Personen));
    
    //add to overview on footer;
    heerNoSpace = heer.replace(/ /g,'');
    if($("#" + heerNoSpace).length){
        //update text to footer
        $("#" + heerNoSpace).text(heer + ": " + persoonBeverageCount);
    }else{
        //add text to footer
        $("p").add("<span id=" + heerNoSpace + ">" + heer + ": " + amount + "</span><br>").appendTo("#EditList");
        $(".enlargePage").add("<br>").appendTo(".enlargePage");
    }
    $('#showTotalStatic').show();
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
            beforeSend: function(){
                window.scrollTo(0, 0);
                $('#sendRequestOverlay').show();
                $('#sendRequestOverlaySpinner').show();
                $('.footer').hide();
                $('#showUpdateFail').hide();
            },
            success: function(res){
                window.location = "/biersysteem?success";
            },
            error: function(jqXHR, textStatus, errorThrown) {
                $('#sendRequestOverlay').hide();
                $('#sendRequestOverlaySpinner').hide();
                $('.footer').show();
                $('#showUpdateFail').show();
                window.scrollTo(0, 0);
                console.log(textStatus, errorThrown);
            }
        });
}
</script>
@endsection