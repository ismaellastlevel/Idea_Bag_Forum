/** Ajouter un prototype au click sur un btn */
function addPrototype(container, parent, beforeButton) {
    let index = container.data('index');
    container.data('index', index + 1);
    let prototype = container.data('prototype').replace(/__name__/g, index);
    
    if (typeof beforeButton !== "undefined" && beforeButton.length > 0) {
        $(prototype).insertBefore(beforeButton)
    } else {
        parent.append(prototype);
    }
    
    addRequiredInfo(parent);
}

function removePrototype(element) {
    /** Si le clic correspond à l'espace des parents... */
    if ($(element).parents('[data-collection="parent"]').length > 0) {
        /** On sélectionne la liste contenant les parents à choisir... */
        let list = $(element).parents('[data-collection="parent"]').find('ul').first();
        /** S'il n'y a qu'un seul parent dans la liste, on ne le supprime pas */
        if (list.find('li').length < 2)
            return;
    }

    let parent = $(element).parents('.prototype-parent').first();
    parent.remove();
}

/** Met à jour l'adresse à afficher dans le map */
/* Pas besoin d'afficher le map en mode edit (on le garde au besoin)
function updateAdrresseBtn(parent)
{
    var mapBtn = parent.find('.targetAddress').first();
    if (mapBtn.length > 0) {
        var adresse = "";
        parent.find('input[type="text"]').each(function() {
            adresse += $(this).val() + " ";
            mapBtn.data('address', adresse);
        });
    }
} */

/** Spécifique aux prototypes des fiches organisations */ 
$('.add-prototype').click(function() {
    let container = $(this).next('.prototype-container').first();
    let parent = $(this).parents('.collection').find('ul').first();
    addPrototype(container, parent);
});

/** Spécifique aux prototypes des sac et ssa de la fiche fed */ 
$(document).on('click', '.add-ssa-sac-prototype', function() {
    let container = $('.prototype-container-' + $(this).data('container')).first();
    let parent = $(this).parents('.collection');
    addPrototype(container, parent, $(this));
    
    // Mettre à jour les collapses
    let nextPrototype = container.data('prototype').replace(/ssas-\d+/g, 'ssas-' + container.data('index')).replace(/idccs-\d+/g, 'idccs-' + container.data('index'))
    container.data('prototype', nextPrototype);
});

/** Pas besoin d'afficher le map en mode edit (on le garde au besoin)
$(document).on('blur', 'input[type="text"]', function() {
    let parent = $(this).parents('.prototype-parent');
    updateAdrresseBtn(parent); 
});
*/

$(document).on('click', '.deleteButton', function() {
    removePrototype(this);
});

/**
 * Génère automatiquement un select2 lors de la création d'une entité
 */
$('.noParent').each(index => {
    let el = $('.noParent')[index];
    let container = $(el).find('.prototype-container').first();
    let parent = $(el).find('ul').first();
    addPrototype(container, parent);
});

$('select[data-parent-archive="1"]').each(index => {
    let element = $('select[data-parent-archive="1"]')[index];
    removePrototype(element);
})

/* $('.prototype-parent').each(function() {
    updateAdrresseBtn($(this));            
}); Pas besoin d'afficher le map en mode edit (on le garde au besoin) */ 
        

