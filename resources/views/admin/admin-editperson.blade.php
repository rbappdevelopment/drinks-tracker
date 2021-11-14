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

<h1 style="padding-left: 350px">ADMIN</h1>    
<table>
    <thead>
        <tr>
            <td id="addpadding">Heer</td>
            <td id="addpadding">Bierstand</td>
            <td id="addpadding">#</td>
        </tr>
    </thead>
<tbody>
        @foreach($bierstand as $heer)
          <tr class="tr body"> {{-- Add data-toggle="modal" data-target="#mutatiesModal" to this tr for edit row entry (TODO admin screen to edit) --}}
                  <td>
                    @if ($loop->first)
                      <i class="fas fa-crown"></i>
                    @endif
                    {{$heer->Heer}}
                  </td>
                  <td>{{$heer->Bier}}</td>
                  <td>
                    <div class="navbar show" id="navbarSupportedContent">
                        <ul class="navbar-nav">
                        <li class="nav-item dropdown">
                            <!-- Authentication Links -->
                                    <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                        <i class="fas fa-pen"></i>
                                    </a>
                                    
                                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                        <a class="dropdown-item" href="#editModal" data-toggle="modal" data-heer-id="{{$heer->id}}" data-name-id="{{$heer->Heer}}" data-drinks-id="{{$heer->Bier}}">
                                            Pas bierstand aan <i class="fas fa-beer"></i>
                                        </a>
                                        <a class="dropdown-item" href="/biersysteem/admin/addperson">
                                            Bekijk mutaties <i class="fas fa-table"></i>
                                        </a>
                                        <a class="dropdown-item" href="/biersysteem/admin/addperson">
                                            Pas naam aan <i class="fas fa-pen"></i>
                                        </a>
                                        <a class="dropdown-item" href="/biersysteem/admin/editperson">
                                            Verwijder persoon <i class="fas fa-cross"></i>
                                        </a>
                                    </div>
                                </li>
                            </ul>
                    </div>
                    </td>
          </tr>
        @endforeach
</tbody>
</table>

  <!-- Modal -->
  <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="editModalTitle" name="nameId" value=""></h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
            <div class="container-fluid">
                <div class="row">
                  <div class="col-md-5">Huidig aantal:</div>
                  <div class="col-md-5">
                      <form name="updateValueForm" method="post" action="">
                        @csrf
                        <input type='text' name='inputDrinks'
                        placeholder="" value="" disabled/>
                    </div>
                    <div class="row">
                        <div class="col-md-5"></div>
                    </div>
                    <div class="col-md-5"><br>Aantal toevoegen of aftrekken:</div>
                    <div class="col-md-5">
                        <br>
                        <input type="text" name="changeDrinksAmount" maxlength="4" placeholder="Voer getal in..." value=""/>
                        <small id="help" class="form-text text-muted">Voor aftrekken, voeg een '-' toe voorafgaand het bedrag. Bv: '-50'.</small>
                    </div>
                        <br/>
                        <br/>
                        <div class="col-md-5"></div>
                        <div class="col-md-5">
                        <button type="submit" name="update" class="btn btn-primary right" onclick="">Update!</button>
                    </div>
                      </form>
                </div>
            </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Terug</button>
        </div>
      </div>
    </div>
  </div>

<br>
<button name="submit" class="btn btn-primary" onclick="return PostData()">Submit!</button>

@endsection

@section('scripts')

<script>
//trigger when Edit modal shows
$(document).on('show.bs.modal','#editModal', function (e) {
    //get data-id attribute of the clicked element
    var heerId = $(e.relatedTarget).data('heer-id');
    var nameId = $(e.relatedTarget).data('name-id');
    var drinksAmount = $(e.relatedTarget).data('drinks-id');
    var postUrl = window.location.href + "/" + heerId;

    document.updateValueForm.setAttribute("action", postUrl);
    document.getElementById("editModalTitle").innerHTML = nameId;
    $(e.currentTarget).find('input[name="inputDrinks"]').val(drinksAmount); //change id of input to name to have it be value instead
});

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