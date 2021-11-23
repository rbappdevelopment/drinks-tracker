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
        <tr class="tr mutationsbody" style="@if ($mutatie->IsAdminUpdate) background: lightgreen @endif"> {{-- Add data-toggle="modal" data-target="#mutatiesModal" to this tr for edit row entry (TODO admin screen to edit) --}}
          <td id="">@php $subjectName = Bierstand::where('id', $mutatie->HeerId)->value('Heer') @endphp {{ $subjectName }}</td>
          <td id="">{{$mutatie->AantalBier}}</td>
          <td id="">{{$mutatie->TotaalBierNaMutatie}}</td>
          <td id="">{{$mutatie->GemuteerdDoorHeer}}</td>
          <td id="">{{$mutatie->created_at}}</td>
          @if ($mutatie->IsAdminUpdate) <td style="text-align: center"> Admin update </td>@endif
        </tr>
        @endforeach
</tbody>
</table>
<br>
<div class="center">
{{ $mutaties->links() }}
</div>