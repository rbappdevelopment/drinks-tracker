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
        <td><a href="#" id="localBierCount{{$heer->Heer}}"></a></td>
        </tr>
        @endforeach
</tbody>
</table>

@endsection

@section('scripts')
<script>
var Personen = {"Bersee":0, "Verburg":0, "Pont":0};
let firstTap = new Boolean(true);

function addBeerToHeer(heer){
    Personen[heer]++;
    //Personen.push(heer);
    document.getElementById('localBierCount'+heer).innerHTML = Personen[heer];
    console.log("Tapped: " + heer + ", added on " + 'localBierCount'+heer+". Total bier voor deze heer: " + Personen[heer]);
    console.log("Personen array inhoud:" + JSON.stringify(Personen));
}

</script>
@endsection