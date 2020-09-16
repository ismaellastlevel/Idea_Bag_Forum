    // Initialisation des boutons de la colonne action pour chaque ligne d'une etable
    // Cette fonction nécessite les variable globales showUrl, editUrl, deleteUrl et archUrl déclarée en tant que path avec id = _ID_
    // Il faut de même déclarer les variables globales des titres des bouttons et des alertes pour supp et arch
    function initColumnActionButtons(id, hideShow, hideEdit, hideDelete, hideArch) {
        // Initialisation pour gérer les appels où ces 4 paramètres ne sont pas passés
        hideShow = typeof hideShow !== 'undefined' ? hideShow : false;
        hideEdit = typeof hideEdit !== 'undefined' ? hideEdit : false;
        hideDelete = typeof hideDelete !== 'undefined' ? hideDelete : false;
        hideArch = typeof hideArch !== 'undefined' ? hideArch : false;

        var result = "";
        var url;
        if (!hideShow && typeof showUrl != "undefined" && showUrl != "") {
            url = showUrl.replace("_ID_", id);
            result += '<a class="text-om-primary mr-2" href="' + url + '" title="' + showTitle + '"><i class="fa fa-eye"></i></a>';
        }
        if (!hideEdit && typeof editUrl != "undefined" && editUrl != "") {
            url = editUrl.replace("_ID_", id);
            result += '<a class="text-om-warning mr-2" href="' + url + '" title="' + editTitle + '"><i class="fa fa-edit"></i></a>';
        }
        if (!hideDelete && typeof deleteUrl != "undefined" && deleteUrl != "") {
            url = deleteUrl.replace("_ID_", id);
            result += '<a class="text-om-danger-alt mr-2" \n\
                data-toggle="modal" data-target="#alert-modal" href="#" \n\
                data-text="' + deleteAlert + '" \n\
                data-type="DELETE" data-ajax="' + url + '" \n\
                title="' + deleteTitle + '">\n\
                <i class="fa fa-trash-alt"></i></a>'
            ;
        }
        if (!hideArch && typeof archUrl != "undefined" && archUrl != "") {
            url = archUrl.replace("_ID_", id);
            result += '<a class="text-om-warning-alt mr-2" \n\
                data-toggle="modal" data-target="#alert-modal" href="#" \n\
                data-text="' + archAlert + '" \n\
                data-type="PUT" data-ajax="' + url + '" \n\
                title="' + archTitle + '">\n\
                <i class="fa fa-folder-open"></i></a>'
            ;
        }    
        return result;
    }
    
    // Convertir colonne de type date
    function displayDateColumn (data)
    {
        if (data !== null) {
            var date = new Date(data.date);
            
            return date.convertToString();
        }
        
        return data;
    }
    
    // Affichage boolean en mode icons
    function displayBoolColumn(data)
    {
        return data == 1 ? '<div class="text-center"><i class="fa fa-check-circle text-om-success"></i></div>' : '<div class="text-center"><i class="fa fa-times-circle text-om-danger"></i></div>';
    }
    
    // Affichage boolean en mode icons spécifique archivé
    function displayArchColumn(data)
    {
        return data == 1 ? '<div class="text-center"><i class="fa fa-check-circle text-om-success"></i></div>' : null;
    }
    