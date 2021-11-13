@extends('includes.master')

<link href="../../css/app.css" rel="stylesheet" type="text/css">

@section('content')

<br>
<div class="center">

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
</div>
@endsection