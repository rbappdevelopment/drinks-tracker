@extends('includes.master')

@section('content')

<link href="/css/app.css" rel="stylesheet" type="text/css">

<?php use \App\Http\Controllers\BiersysteemController;
use App\Models\Bierstand;
use App\Models\Mutaties;
?>

{{-- Add conditional to check whether or not Read Only mode is on --}}
@php
  $currentLoggedInUserId = DB::table('users')->where('id', auth()->user()->id)->value('id');
  $getHousesForParticipant = DB::table('participant')->where('user_id', auth()->user()->id)->value('name');
  $isReadOnly = null;
@endphp

@if($isReadOnly)
<div style="text-align: center">
    <h1>Er is nog geen huislijst aangemaakt.</h1>
    <a href="#explainModal" data-toggle="modal"><u>Klik hier om een huislijst aan te maken.</u></a>
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

<input type="text" id="searchInput" onkeyup="searchUsers()" style="font-family:Arial, FontAwesome" placeholder="&#xF002; Zoeken naar naam...">

<table id="usersTable">
    <thead>
        <tr>
            <td id="addpadding">Huis</td>
        </tr>
    </thead>
<tbody>
        @foreach($bierstand as $heer)
          <tr class="tr body">
                  <td><a href="#" onclick="AddBeerToHeer('{{$heer->Heer}}', 1);return false;">
                  {{$heer->Name}}
                  </a></td>
          </tr>
        @endforeach
</tbody>
</table>

@foreach($houses as $house)
<p>$house->house_id</p>
@endforeach

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
            <img src="{{ URL::to('/images/priv\/') . "ramones.jpeg" }}" style="border-radius: 65%; max-width: 40%; max-height: 40%">
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

function searchUsers() {
  // Declare variables
  var input, filter, table, tr, td, i, txtValue;
  input = document.getElementById("searchInput");
  filter = input.value.toUpperCase();
  table = document.getElementById("usersTable");
  tr = table.getElementsByTagName("tr");

  // Loop through all table rows, and hide those who don't match the search query
  for (i = 0; i < tr.length; i++) {
    td = tr[i].getElementsByTagName("td")[0];
    if (td) {
      txtValue = td.textContent || td.innerText;
      if (txtValue.toUpperCase().indexOf(filter) > -1) {
        tr[i].style.display = "";
      } else {
        tr[i].style.display = "none";
      }
    }
  }
}
</script>
@endsection