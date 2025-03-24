// Ajouter une nouvelle ligne
document.getElementById('ajouterLigne').addEventListener('click', function() {
    const table = document.getElementById('tableauDynamique').getElementsByTagName('tbody')[0];
    const newRow = table.insertRow();

    // Récupérer les options du select initial pour les réutiliser
    const originalSelect = document.querySelector("select[name='produitsEncaissement[]']");
    const newSelect = originalSelect.cloneNode(true);

    newRow.innerHTML = `
                        <td>
                            ${newSelect.outerHTML}
                        </td>
                        <td><input type="number" name="qte[]"></td>
                        <td><button type="button" class="btn supprimerLigne btn-red">Supprimer</button></td>
                      
        `;

    // Ajouter l'événement de suppression pour la nouvelle ligne
    newRow.querySelector('.supprimerLigne').addEventListener('click', function() {
        newRow.remove();
    });
});

// Suppression d'une ligne
document.querySelectorAll('.supprimerLigne').forEach(button => {
    button.addEventListener('click', function() {
        const row = button.closest('tr');
        row.remove();
    });
});