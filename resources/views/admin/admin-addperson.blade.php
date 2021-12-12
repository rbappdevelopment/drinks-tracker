@extends('includes.master')

<link href="../../css/app.css" rel="stylesheet" type="text/css">

@section('content')

@php
    $isReadOnly = DB::table('users')->where('id', auth()->user()->id)->value('is_readonly')
@endphp

<br>
<div class="center">
@if($isReadOnly)
<br>
<div style="text-align: center">
  <h5>Je kan geen persoon toevoegen in leesmodus.</h5>
  <a href="#explainModal" data-toggle="modal"><u>Wat is dit?</u></a>
<div>
<hr>

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
@else
  <!--Success message-->
  @if(session()->has('success'))
  {{ session()->get('success') }}
  <b>{{ session()->get('persoonadded') }}</b>
  <hr>
  @endif

  @if($errors->any())

    @foreach($errors->all() as $error)
    <span style="color: red; font-weight: bold">Fout: </span><u>{{ $error }}</u>
    @endforeach
    <hr>
  @endif

<form method="post" action="{{ route('addperson') }}">
  @csrf
  <div class="form-group">
    <label for="exampleInputEmail1">Naam:</label>
    <input type="text" class="form-control" name="Heer" placeholder="Naam">
    <small id="help" class="form-text text-muted">Let op hoofdletter(s). Vergeet bij een Heer niet er voorafgaand 'Heer' aan toe te voegen.<br>Elk nieuw persoon begint met 0 afgestreept pils.</small>
  </div>
  <button type="submit" class="btn btn-primary">Toevoegen</button>
</form>
@endif
</div>
@endsection