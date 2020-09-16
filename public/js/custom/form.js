$(document).ready(function () {
    addRequiredInfo($('body'));
    disabledDoubleClickSubmit();
});

/* Ajouter l'info pour les champs requis  */
function addRequiredInfo(parentSelector)
{
    parentSelector.find('input, select').each(function() {
        let label = $('label[for="'+ $(this).attr('id') +'"].required');
        if (label.find('span.star').length === 0) {
            label.append('<span class="star text-om-danger font-weight-bold">&nbsp;*</span>');
        }
    });
}

/* Désactiver les doubles clic sur les boutons de soumission */
function disabledDoubleClickSubmit() {
    $('form:not(.noCheckDoubleClick)').submit(function () {
         $('input[type=submit], .btn').attr('disabled', 'disabled');
    });
}

/** Initialisation du selec2 ajax*/
function searchInSelect2(selector, url, placeholder, withNoResult, noId) {
    selector.select2({
        placeholder: placeholder,
        language: 'fr',
        width: '100%',
        ajax: {
            url: url,
            dataType: 'json',
            delay: 500,
            processResults: function (data) {
                if (typeof withNoResult !== "undefined" && withNoResult && ata.results.length === 0) {
                    return {
                        results: [{
                            text: 'Aucun élément trouvé',
                            id: -1
                        }]
                    };
                }
                
                if (typeof noId === "undefined" || noId === false) {
                    for (var key in data.results)
                    {
                        data.results[key].text = data.results[key].id + ' - ' + data.results[key].text;
                    } 
                }               

                return data;
            }
        },        
        minimumInputLength: 2
    });            
}

/** Initialisation de date time picker */
function initDateTimePicker(withHours)
{
    $('input[data-picker="datetime"]').datetimepicker({
        format: typeof withHours !== "undefined" && withHours ? 'dd/mm/yyyy hh:ii' : 'dd/mm/yyyy',
        minView: typeof withHours !== "undefined" && withHours ? 0 : 2,
        autoclose: true,
        todayBtn: true,
        language: 'fr',
        minuteStep: 30
    });
    
    $('input[data-picker="datetime"]').on('keypress paste', function (e) {
        e.preventDefault();
        return false;
    });
            
    // Ajout des icons
    $('.table-condensed th.prev').append('<i class="fa fa-angle-left"></i>');
    $('.table-condensed th.next').append('<i class="fa fa-angle-right"></i>');
}        

