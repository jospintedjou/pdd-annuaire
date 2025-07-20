<table>
    <thead>
        <tr>
            <th>N°</th>
            <th>Nom</th>
            <th>Zone</th>
            <th>Sous-zone</th>
            <th>Pays</th>
            <th>Groupe</th>
            <!--th>Date d'inscr.</th-->
            <th>Profession</th>
            <th>Spécialité</th>
            <th>Catégorie Soc.</th>
            <th>Niveau d'enga.</th>
        </tr>
    </thead>
    <tbody>
        @foreach($users as $user)
            <tr>
                <td>{{ $loop->index+1 }}</td>
                <td>{{ $user->prenom }} {{ $user->nom }}</td>
                <td>{{ $user->activeGroupes()->first()?->sousZone?->zone?->nom }}</td>
                <td>{{ $user->activeGroupes()->first()?->sousZone?->nom }}</td>
                <td>{{ $user->activeGroupes()->first()?->pays?->nom }}</td>
                <td>{{ $user->activeGroupes()->first()?->nom_groupe }}</td>
                <td>{{ $user->profession }}</td>
                <td>{{ $user->specialite }}</td>
                <td>{{ $user->categorie_sociale }}</td>
                <td>{{ $user->niveauEngagement?->nom }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
