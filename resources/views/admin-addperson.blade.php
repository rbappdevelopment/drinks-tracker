@extends('includes.master')

<link href="../../css/app.css" rel="stylesheet" type="text/css">

@section('content')

<br>
<div class="center">
<form method="post" >
  <div class="form-group">
    <label for="exampleInputEmail1">Naam:</label>
    <input type="text" class="form-control" name="Heer" placeholder="Naam Heer">
    <small id="emailHelp" class="form-text text-muted">Let op hoofdletter(s). Elk nieuw persoon begint met 0 afgestreept pils.</small>
  </div>
  <button type="submit" class="btn btn-primary">Submit</button>
</form>
</div>
@endsection