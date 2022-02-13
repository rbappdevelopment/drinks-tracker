@extends('includes.master')

@section('content')

<link href="/css/app.css" rel="stylesheet" type="text/css">

<?php use \App\Http\Controllers\BiersysteemController;
use App\Models\houseparticipantmap;
use App\Models\Mutaties;
?>

@php
  $currentLoggedInUserId = DB::table('users')->where('id', auth()->user()->id)->value('id');
  $getHousesForParticipant = DB::table('participant')->where('user_id', auth()->user()->id)->value('house_id');
@endphp
<br>
<h1 style="text-align: center">Welcome user {{$currentLoggedInUserId}}.</h1>
<br>
<hr>

@if($getHousesForParticipant == null)
<div style="text-align: center">
    <h1>Je zit (nog) niet in een huislijst.</h1>

    <a href="#explainModal" data-toggle="modal"><u>Klik hier om een huislijst aan te maken.</u></a><br>
    Heb je al een huislijst? Vraag dan degene die daarvoor beheerdersrechten heeft om jou hiervoor uit te nodigen.
</div>

@else

<div style="text-align: center">
    <h1>Jouw lijst(en)</h1>
    <p>{{$participant->display_name}}</p>
    <a></a>
</div>

@endif

@endsection