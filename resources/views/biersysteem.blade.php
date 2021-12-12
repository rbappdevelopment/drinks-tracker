@extends('includes.master')

@section('content')

<link href="/css/app.css" rel="stylesheet" type="text/css">

<?php use \App\Http\Controllers\BiersysteemController;
use App\Models\Bierstand;
use App\Models\Mutaties;
?>

{{-- Add conditional to check whether or not Read Only mode is on --}}
@php
    $isReadOnly = DB::table('users')->where('id', auth()->user()->id)->value('is_readonly')
@endphp

@if($isReadOnly)
<div style="text-align: center">
    <h1>- Leesmodus -</h1>
    <a href="#explainModal" data-toggle="modal"><u>Wat is dit?</u></a>
</div>
@endif

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
            @if(!$isReadOnly)
            <td id="addpadding">#</td>
            <td id="addpadding"></td>
            @endif
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
                  <td><a href="#" @if(!$isReadOnly)onclick="AddBeerToHeer('{{$heer->Heer}}', 1);return false;"@endif>{{$heer->Bier}}</a></td>
                  @if(!$isReadOnly)
                  <td><b><a href="#" onclick="AddBeerToHeer('{{$heer->Heer}}', 1);return false;" id="localBierCount{{$heer->Heer}}"></b></a></td>
                  <td><a href="#" onclick="AddBeerToHeer('{{$heer->Heer}}', 12);return false;" id="localBierCount{{$heer->Heer}}" class="addTwelve"><i class="fas fa-beer"></i>x12</a></td>
                  @endif
          </tr>
        @endforeach
</tbody>
</table>

@if(!$isReadOnly)
<div class="footer">
    <b id="showTotalStatic" style="display: none;">Totaal:</b>
    <br>
    <span id="EditList"></span>
    <button name="submit" class="btn btn-primary" style="float: right; margin: 0px 15px 15px 0px;" onclick="return PostData()">Afstrepen!</button>
</div>
@endif

<br>
<br>
<br>
<div class="enlargePage"></div>

  <!-- Modal -->
  <div class="modal fade" id="explainModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="explainModalTitle">Toelichting leesmodus</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body" style="text-align: center">
            Alle aangemaakte accounts beginnen standaard in <b>leesmodus</b>. Hiermee kan je de bierstand <u>wél</u> inzien, maar <u>niet</u> afstrepen.
            <br>
            Mogelijkheid om te kunnen afstrepen vereist nog een handmatige actie in je account door Rian.
            <br><br>
            Dit is expres zo opgezet, zodat in het geval een buitenstaander het mocht lukken om een account aan te maken, degene niet gelijk de cijfers overhoop kan gooien. 
            <br><br>
            Wil je kunnen afstrepen of heb je andere vragen omtrent het Biersysteem? Contacteer dan Rian.
            <br><br>
            <img src="{{ URL::to('/images\/') . "ramones.jpeg" }}" style="border-radius: 65%; max-width: 40%; max-height: 40%">
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