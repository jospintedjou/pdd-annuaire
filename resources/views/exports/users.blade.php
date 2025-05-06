<table>
    <thead>
        <tr>
            <th>Nom</th>
            <th>Prénom</th>
            <th>Zone</th>
            <th>Groupe</th>
            <th>Catégorie Sociale</th>
            <th>Niveau d'engagement</th>
        </tr>
    </thead>
    <tbody>
        @foreach($users as $user)
            <tr>
                <td>{{ $user->nom }}</td>
                <td>{{ $user->prenom }}</td>
                <td>{{ $user->zone()?->nom }}</td>
                <td>{{ $user->groupes()->where('actif', \App\Constantes::ETAT_ACTIF)->first()?->nom_groupe }}</td>
                <td>{{ $user->categorie_sociale }}</td>
                <td>{{ $user->niveauEngagement?->nom }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
