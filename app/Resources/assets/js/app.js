// loads the Bootstrap jQuery plugins
import 'bootstrap-sass/assets/javascripts/bootstrap/dropdown.js';
import 'bootstrap-sass/assets/javascripts/bootstrap/modal.js';
import 'bootstrap-sass/assets/javascripts/bootstrap/transition.js';

// loads the code syntax highlighting library
import './highlight.js';

var edit_commande = $('div#edit_commande');
//attribut « data-prototype » qui nous intéresse.
var $container = $('div#appbundle_commande_commande_produits');
// On ajoute un lien pour ajouter une nouvelle produit
var $lienAjout = $('<a href="#" id="ajout_commande" class="btn btn-primary">Ajouter un produit</a>');

//ne pas afficher si edit
if(edit_commande.length == 0)
   $container.append($lienAjout);
// On ajoute un nouveau champ à chaque clic sur le lien d'ajout.
$lienAjout.click(function(e) {
    ajouterProduit($container);
    e.preventDefault(); // évite qu'un # apparaisse dans l'URL
    return false;
});

var index = $container.find(':input').length;

// On ajoute un premier champ directement s'il n'en existe pas déjà un (cas d'un nouvel article par exemple).
if (index == 0 && $container.length == 1) {
    ajouterProduit($container);
} else {
// Pour chaque produit déjà existante, on ajoute un lien de suppression
    $container.children('div').each(function() {
        if(edit_commande.length == 0)
            ajouterLienSuppression($(this));
    });
}

// La fonction qui ajoute un formulaire ajout produit
function ajouterProduit($container) {
// Dans le contenu de l'attribut « data-prototype », on
// - le texte "__name__label__" qu'il contient par le label du champ
// - le texte "__name__" qu'il contient par le numéro du champ
   var $prototype = $($container.attr('data-prototype').replace(/__name__label__/g, 'Produit n°' + (index+1)) .replace(/__name__/g, index));
    // On ajoute au prototype un lien pour pouvoir supprimer le produit
    ajouterLienSuppression($prototype);
// On ajoute le prototype modifié à la fin de la balise <div>
    $container.append($prototype);
// Enfin, on incrémente le compteur pour que le prochain ajout se fasse avec un autre numéro
    index++;
}

function ajouterLienSuppression($prototype) {
// Création du lien
    var $lienSuppression = $('<a href="#" class="btn btn-danger">Supprimer</a>');
// Ajout du lien
    $prototype.append($lienSuppression);
// Ajout du listener sur le clic du lien
    $lienSuppression.click(function(e) {
        $prototype.remove();
        e.preventDefault(); // évite qu'un # apparaisse dans l'URL
        return false;
    });
}

// Handling the modal confirmation message.
$(document).on('submit', 'form[data-confirmation]', function (event) {
    var $form = $(this),
        $confirm = $('#confirmationModal');

    if ($confirm.data('result') !== 'yes') {
        //cancel submit event
        event.preventDefault();

        $confirm
            .off('click', '#btnYes')
            .on('click', '#btnYes', function () {
                $confirm.data('result', 'yes');
                $form.find('input[type="submit"]').attr('disabled', 'disabled');
                $form.submit();
            })
            .modal('show');
    }
});