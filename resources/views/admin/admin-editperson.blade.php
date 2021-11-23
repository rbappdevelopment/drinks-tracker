@extends('includes.master')

@section('content')

<link href="/css/app.css" rel="stylesheet" type="text/css">

<?php use \App\Http\Controllers\BiersysteemController;
use \App\Http\Controllers\AdminController;
use App\Models\Bierstand;
use App\Models\Mutaties;
?>

@if(DB::table('users')->where('id', auth()->user()->id)->value('is_admin'))
<h1 style="padding-left: 350px">ADMIN</h1>  

{{-- All successful/fail alerts --}}
@if (session('successfulUpdateTitle'))
    <div class="alert alert-success">
       <b> {{ session('successfulUpdateTitle') }} </b>
       <p> {{ session('successfulUpdateBody') }} </p>
       {{ session('successfulUpdateEnd') }}
    </div>
@endif
@if (session('failUpdateTitle'))
    <div class="alert alert-danger">
       <b> {{ session('failUpdateTitle') }} </b>
    </div>
@endif
@if (session('successfulNameTitle'))
    <div class="alert alert-success">
       <b> {{ session('successfulNameTitle') }} </b>
       <p> {{ session('successfulNameBody') }} </p>
    </div>
@endif
@if (session('failNameTitle'))
    <div class="alert alert-danger">
       <b> {{ session('failNameTitle') }} </b>
    </div>
@endif
@if (session('successfulDeleteTitle'))
    <div class="alert alert-success">
       <b> {{ session('successfulDeleteTitle') }} </b>
       {{ session('successfulDeleteBody') }}
    </div>
@endif
@if (session('failDeleteTitle'))
    <div class="alert alert-danger">
       <b> {{ session('failDeleteTitle') }} </b>
       {{ session('failDeleteBody') }}
    </div>
@endif
{{-- //////////// end alerts //////////// --}}

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
                                        <a class="dropdown-item" href="#personalMutationsModal" data-toggle="modal" data-name-id="{{$heer->Heer}}" onclick="return GetPersonalMutations({{$heer->id}})">
                                            Bekijk mutaties <i class="fas fa-table"></i>
                                        </a>
                                        <a class="dropdown-item" href="#editNameModal" data-toggle="modal" data-heer-id="{{$heer->id}}" data-name-id="{{$heer->Heer}}">
                                            Pas naam aan <i class="fas fa-pen"></i>
                                        </a>
                                        <a class="dropdown-item" href="#deletePersonModal" data-toggle="modal" data-heer-id="{{$heer->id}}" data-name-id="{{$heer->Heer}}">
                                            Verwijder persoon <i class="fas fa-user-slash"></i>
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
                        <input type="text" id="changeDrinksAmount" name="changeDrinksAmount" maxlength="4" placeholder="Voer getal in..." value=""/>
                        <small id="help" class="form-text text-muted">Voor aftrekken, voeg een '-' toe voorafgaand het bedrag. Bv: '-50'.</small>
                    </div>
                        <div class="col-md-5"></div>
                        <div class="col-md-5">
                        <br/>   
                        <button type="submit" name="update" class="btn btn-primary right" onclick="this.form.submit(); this.disabled = true;">Update!</button>
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

  <!-- Modal -->
  <div class="modal fade" id="personalMutationsModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="personalMutationsModalTitle" name="nameId" value=""></h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close" >
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div id="loading-spinner" class="spinner-border text-primary" role="status" style="display: block; margin-left: auto; margin-right: auto;">
          <span class="sr-only">Loading...</span>
        </div>
        <div id="modal-body">
          {{-- TODO: Show mutations specifically for this person --}}
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Terug</button>
        </div>
      </div>
    </div>
  </div>

  <!-- Modal -->
  <div class="modal fade" id="editNameModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="editNameModalTitle" name="nameId" value=""></h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
            <div class="container-fluid">
                <div class="row">
                  <div class="col-md-5">Huidige naam:</div>
                  <div class="col-md-5">
                      <form name="updateNameForm" method="post" action="">
                        @csrf
                        <input type='text' name='inputName'
                        placeholder="" value="" disabled/>
                    </div>
                    <div class="row">
                        <div class="col-md-5"></div>
                    </div>
                    <div class="col-md-5"><br>Hernoemen naar:</div>
                    <div class="col-md-5">
                        <br>
                        <input type="text" id="changeName" name="changeName" required onkeydown="return /[a-z,A-Zéá ]/i.test(event.key)" maxlength="40" placeholder="Voer een naam in..." value=""/>
                    </div>
                        <div class="col-md-5"></div>
                        <div class="col-md-5">
                        <br/>   
                        <button type="submit" name="update" class="btn btn-primary right" onclick="this.form.submit(); this.disabled = true;">Update!</button>
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

  <!-- Modal -->
  <div class="modal fade" id="deletePersonModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="deletePersonModalTitle" name="nameId" value=""></h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
            <div class="container-fluid">
                <div class="row">
                      <form name="deletePersonForm" method="post" action="">
                        @csrf
                    </div>
                    <div class="col-md-5">Weet je zeker dat je <input type='text' id='deletePerson' name='deletePerson' placeholder="" value="" disabled/><b></b> wil verwijderen?</div>
                        <div class="col-md-5">Dit kan niet meer ongedaan worden gemaakt!</div>
                        <br/>   
                        <button type="submit" name="update" class="btn btn-primary red" onclick="this.form.submit(); this.disabled = true;">Verwijderen</button>
                    </div>
                      </form>
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-secondary" data-dismiss="modal">Terug</button>
                </div>
              </div>
            </div>
      </div>
    </div>
  </div>

@else
<br>
<br>
<h5 style="text-align: center">Je moet admin zijn om deze pagina te bereiken.</h5>
<hr>
@endif

@endsection

@section('scripts')

<script>
$('#updateValueForm').one('submit', function() {
    $(this).find('input[type="submit"]').attr('disabled','disabled');
});

//trigger when edit modal shows
$(document).on('show.bs.modal','#editModal', function (e) {
    //get data-id attribute of the clicked element
    var heerId = $(e.relatedTarget).data('heer-id');
    var nameId = $(e.relatedTarget).data('name-id');
    var drinksAmount = $(e.relatedTarget).data('drinks-id');
    //remove parameters from url & get correct url to post to
    var postUrl = window.location.href.split(/[?#]/)[0] + "/" + heerId;

    document.updateValueForm.setAttribute("action", postUrl);
    document.getElementById("editModalTitle").innerHTML = nameId;
    $(e.currentTarget).find('input[name="inputDrinks"]').val(drinksAmount); //change id of input to name to have it be value instead
});

$(document).on('hidden.bs.modal','#editModal', function (e) {
    document.getElementById("changeDrinksAmount").value = "";
});

//trigger when personal mutation modal shows
$(document).on('show.bs.modal','#personalMutationsModal', function (e) {
    //get data-id attribute of the clicked element
    var nameId = $(e.relatedTarget).data('name-id');
    document.getElementById("personalMutationsModalTitle").innerHTML = nameId;
});

//trigger when editName modal shows
$(document).on('show.bs.modal','#editNameModal', function (e) {
    //get data-id attribute of the clicked element
    var heerId = $(e.relatedTarget).data('heer-id');
    var nameId = $(e.relatedTarget).data('name-id');
    //remove parameters from url & get correct url to post to
    var postUrl = window.location.href.split(/[?#]/)[0] + "/" + heerId + "/name";

    document.updateNameForm.setAttribute("action", postUrl);
    document.getElementById("editNameModalTitle").innerHTML = nameId;
    $(e.currentTarget).find('input[name="inputName"]').val(nameId); //change id of input to name to have it be value instead
});

$(document).on('hidden.bs.modal','#editNameModal', function (e) {
    document.getElementById("changeName").value = "";
});

//trigger when deletePerson modal shows
$(document).on('show.bs.modal','#deletePersonModal', function (e) {
    //get data-id attribute of the clicked element
    var heerId = $(e.relatedTarget).data('heer-id');
    var nameId = $(e.relatedTarget).data('name-id');
    //remove parameters from url & get correct url to post to
    var postUrl = window.location.href.split(/[?#]/)[0] + "/" + heerId + "/delete";

    document.deletePersonForm.setAttribute("action", postUrl);
    document.getElementById("deletePersonModalTitle").innerHTML = nameId;
    $(e.currentTarget).find('input[name="deletePerson"]').val(nameId);
});

function GetPersonalMutations(PersonId) {
console.log("Submitting data: " + PersonId);

$.ajaxSetup({
      headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });  

    $.ajax({
            type : "GET",
            url  : "/biersysteem/admin/person/" + PersonId + "/mutations",
            cache: false,
            beforeSend: function(){
                $('#loading-spinner').show();
                $('#modal-body').html(null);
            },
            success: function(res){
              $('#modal-body').html(res);
            },
            complete: function(){
                $('#loading-spinner').hide();
            },
        error: function(jqXHR, textStatus, errorThrown) {
           console.log(textStatus, errorThrown);
           $('#modal-body').html("<br>Er is een <b>fout</b> opgetreden, waardoor de mutaties niet konden worden opgehaald.<br>Check je verbinding en probeer het opnieuw.<br>");
        }
        });
  }
</script>
@endsection