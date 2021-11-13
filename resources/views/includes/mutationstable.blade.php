<?php 
use App\Models\Bierstand;

?>

<table>
    <thead>
        <tr style="font-size: 10pt; background: rgba(255, 255, 255, 0.747); ">
          <td id=""><b>Afgestreept op</b></td>
          <td id=""><b>Aantal afgestreept</b></td>
          <td id=""><b>Totaal over</b></td>
          <td id=""><b>Afgestreept door</b></td>
          <td id=""><b>Datum & tijd</b></td>
        </tr>
    </thead>
<tbody>
        @foreach($mutaties as $mutatie)
        <tr class="tr mutationsbody"> {{-- Add data-toggle="modal" data-target="#exampleModal" to this tr for edit row entry (TODO admin screen to edit) --}}
          <td id="">@php $subjectName = Bierstand::where('id', $mutatie->HeerId)->value('Heer') @endphp {{ $subjectName }}</td>
          <td id="">{{$mutatie->AantalBier}}</td>
          <td id="">{{$mutatie->TotaalBierNaMutatie}}</td>
          <td id="">@php $subjectedByName = Bierstand::where('id', $mutatie->GemuteerdDoorHeer)->value('Heer') @endphp {{ $subjectedByName }}</td>
          <td id="">{{$mutatie->created_at}}</td>
        </tr>
        @endforeach
</tbody>
</table>
<br>
<div class="center">
{{ $mutaties->links() }}
</div>