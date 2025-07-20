<table id="datatables"
        class="table table-striped table-no-bordered table-hover dataTable dtr-inline"
        style="width: 100%;" width="100%" cellspacing="0">
    <thead>
    <tr>
        <th>N°</th>
        <th>Activité</th>
        <th>Concern&eacute;s</th>
        <th>Date Debut</th>
        <th>Lieu</th>
        <th>Heure debut</th>
        <th class="disabled-sorting text-right sorting">Actions</th>
    </tr>
    </thead>
    <tbody>
    @foreach($activites as $activite)
    <tr>
        <td>{{$loop->index + 1}}</td>
        <td class="">{{$activite->nom}}</td>
        <!--td class="">{{--$activite->categorieActivite->nom--}}</td-->
        <td class="">
            @if ($activite?->type_activite == App\Constantes::ACTIVITE_REGIONALE)
                Région
            @elseif ($activite?->type_activite == App\Constantes::ACTIVITE_ZONALE)
                {{$activite?->zone?->nom}}
            @elseif ($activite?->type_activite == App\Constantes::ACTIVITE_SOUS_ZONALE)
                {{$activite?->sousZone?->nom}}
            @else
                Groupe de {{$activite?->groupe?->nom_groupe}}
            @endif
        </td>
        <td class="">{{$activite->date_debut}}</td>
        <td class="">{{$activite->lieu}}</td>
        <td class="">{{$activite->heure_debut}}</td>
        <td class="td-actions text-right">
            <form action="{{ route('activites.destroy',$activite->id) }}" method="Post">
                @csrf
                @method('DELETE')
                <a href="{{route('activites.edit', ['activite' =>$activite->id])}}" type="button" rel="tooltip"
                    class="btn btn-success btn-round" data-original-title="" title="modifier">
                    <i class="material-icons">edit</i>
                    <div class="ripple-container"></div>
                </a>
                <!-- Button trigger modal -->
                <button type="button" class="btn btn-danger btn-round text-white"
                        data-id="{{ $activite->id }}"
                        data-href="{{ route('activites.destroy',$activite->id) }}"
                        data-toggle="modal" data-target="#confirm-delete">
                    <i class="material-icons">close</i>
                    <div class="ripple-container"></div>
                </button>

            </form>
        </td>
    </tr>
    @endforeach
    </tbody>
</table>
