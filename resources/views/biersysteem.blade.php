@extends('includes.master')

@section('content')

<br>

<table>
    <thead>
        <tr>
            <td>Heer</td>
            <td>Bierstand</td>
            <td>#</td>
        </tr>
    </thead>
<tbody>
        @foreach($bierstand as $heer)
        <tr class="tr body">
        <td><a href="#" onclick="addBeerToHeer('{{$heer->Heer}}');return false;">{{$heer->Heer}}</a></td>
        <td><a href="#" onclick="addBeerToHeer('{{$heer->Heer}}');return false;">{{$heer->Bier}}</a></td>
        <td><a href="#"><p id="localBierCount{{$heer->Heer}}"></p></a></td>
        </tr>
        @endforeach
</tbody>
</table>

@endsection

@section('scripts')
<script>
var initialBierCount = 0;

function addBeerToHeer(heer){
    initialBierCount++;
    document.getElementById('localBierCount{{$heer->Heer}}').innerHTML = initialBierCount;
    console.log("Tapped: " + heer + ", added on " + 'localBierCount{{$heer->Heer}}');
}

</script>
@endsection