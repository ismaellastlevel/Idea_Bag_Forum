$(function () {

    /**
     * Génère un select2 pour les communes et adresses
     *
     * @param selector
     * @param type (locality|housenumber)
     * @param placeholder
     */
    function generateSelectAjax(selector, type, placeholder = 'Chercher...') {
        let inputText = '';
        selector.select2({
            placeholder: placeholder,
            language: 'fr',
            width: '100%',
            ajax: {
                url: 'https://api-adresse.data.gouv.fr/search/',
                dataType: 'json',
                delay: 500,
                crossDomain: true,
                data : params => {
                    inputText = params.term;
                    return {q: params.term, type: type, limit: type === 'locality' ? 30 : 10};
                },

                processResults: function (data) {
                    /*if (data.features.length === 0) {
                        return {
                            results: [{
                                text: inputText,
                                id : 1
                            }]
                        };
                    }*/

                    //  Stockage des résultats
                    let tempData = [];
                    //  Reminder pour éviter les doublons lors de l'affichage
                    let cpReminder = [];

                    for (let key in data.features) {
                        let cityAndCp = data.features[key].properties.city.toLowerCase() + ' ' + data.features[key].properties.postcode.toLowerCase();

                        if (type !== 'locality' || (type === 'locality' && cityAndCp.normalize('NFD').replace(/[\u0300-\u036f]/g, "").search(inputText.toLowerCase().normalize('NFD').replace(/[\u0300-\u036f]/g, "") ) >= 0)) {
                            if (!cpReminder.includes(data.features[key].properties.postcode)) {
                                cpReminder.push(data.features[key].properties.postcode);
                                tempData.push({
                                    text: type === 'locality' ? data.features[key].properties.city + ' - ' + data.features[key].properties.postcode : data.features[key].properties.label,
                                    id: key,
                                    data: data.features[key].properties,
                                    category: type === 'locality' ? 'commune' : 'adresse'
                                });
                            }
                        }

                    }

                    //  Trie les resultats de la requête
                    tempData.sort((a,b) => (a.text > b.text) ? 1 : ((b.text > a.text) ? -1 : 0));

                    tempData.push({text: inputText, id : 999999});

                    data.results = tempData;
                    return data;
                }
            },
            minimumInputLength: type === 'locality' ? 3 : 8
        });
    }

    /**
     * On initialise les select2 correspondant selon la configuration donnée
     */
    function generateSelect() {
        $('select[data-address-select]').each(index => {
            const selector = $('select[data-address-select]')[index];

            if (selector.dataset.addressSelect === 'commune')
                generateSelectAjax($(selector), 'locality', 'Chercher une commune');
            else
                generateSelectAjax($(selector), 'housenumber', 'Chercher une adresse');

        })
    }

    /**
     * Ajout du select2 lorsqu'un prototype est créé
     */
    $('.add-prototype').on('click', function() {
        generateSelect();
        $(this).parents('.collection').find('select[data-address-select]').last().each(function(){
            $(this).find('option').each(function() {
                $(this).remove();
            })           
        })
    });

    /**
     * Initialisation des select2
     */
    generateSelect();

    /**
     * Lors du clic sur un élément du select2, on remplit les champs automatiquement
     */
    $(document).on('select2:select', 'select[data-address-select]', e => {
        let data = e.params.data.data;
        const el = $(e.currentTarget).parents('.prototype-parent');

        if (data) {
            if (e.params.data.category === 'commune') {
                el.find('input[name*="cpClo"]').val(data.postcode);
                el.find('input[name*="inseeClo"]').val(data.citycode);
                el.find('select[name*="libelleClo"]').empty().append(`<option value="${data.city}">${data.city}</option>`);
            } else {
                el.find('input[data-input-address="cp"]').val(data.postcode);
                el.find('input[data-input-address="ville"]').val(data.city);
                el.find('select[data-input-address="rue"]').empty().append(`<option value="${data.name}">${data.name}</option>`);
            }
        } else {
            el.find('select[name*="libelleClo"]').empty().append(`<option value="${e.params.data.text}">${e.params.data.text}</option>`);
        }

    });

    /**
     * On récupère le texte du select2 pour le mttre dans l'input du dropdown
     * @TODO
     */
    /*$(document).on('select2:open', 'select[data-address-select]', function() {
        let selected = $(this).find('option:selected');
        let text = selected.length > 0 ? selected.text() : "";
        $(this).next('.select2-container--open').find('input.select2-search__field').first().val(text);
        return;
    });*/

});