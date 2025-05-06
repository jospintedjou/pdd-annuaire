$(function () {
    $('#ordersTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: '{{ route("users.data") }}',
        columns: [
            { data: 'N°', name: 'N°' },
            { data: 'Nom', name: 'Nom' },
            { data: 'Zone', name: 'Zone' },
            { data: 'Groupe', name: 'Groupe' },
            { data: 'Catégorie Sociale', name: 'categorie_sociale' },
            { data: 'Niveau d\'engagement', name: 'Niveau d\'engagement' },
            { data: 'Actions', name: 'Actions' },
        ],
        paging: true
    });
});
