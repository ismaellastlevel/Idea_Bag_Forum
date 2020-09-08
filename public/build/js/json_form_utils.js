(function ($) {
    $.fn.serializeFormJSON = function() {
        let o = {};
        let a = this.serializeArray();
        $.each(a, function () {
            let name = this.name;
            let value = this.value || '';
            if (o[name]) {
                if (!Array.isArray(o[name])) {
                    o[name] = [o[name]];
                }
                o[name].push(value);
            } else {
                o[name] = value;
            }
        });

        return o;
    };

    $('.json-form').submit(function(e) {
        e.preventDefault();

        let form = $(this);
        let url  = form.attr('action');
        let data = form.serializeFormJSON();

        $.ajax({
            type: 'POST',
            url: url,
            data: data,
            success: function(data) {
                $('.removeDataMsg').remove();
                let className = (true === data.error) ? 'danger' : 'success';

                if (true === data.error) {
                    $('.removeDataErrorMsgForm').remove();
                    form.find('.modal-body').first().prepend(createALert(className, data.message));

                    for (let name in data.error_message_form) {
                        $('#'+name).after('<span class="text-danger removeDataErrorMsgForm">'+ data.error_message_form[name] +'</span>');
                    }
                } else {
                    $('#message').append(createALert(className, data.message));
                }
            },
        });
    });
})(jQuery);

let xhr = new XMLHttpRequest();
let btnEdits = document.querySelectorAll("#edit");
let btnDeletes = document.querySelectorAll("#delete");

btnEdits.forEach(function (btnEdit) {
    let url = showUrl.replace('__ID__', btnEdit.dataset.id);
    btnEdit.addEventListener('click', function (event) {
        // const roleDescription = document.querySelector('#role_description');
        event.preventDefault();
        xhr.onreadystatechange = function() {
            if (xhr.readyState === 4 && (xhr.status === 200 || xhr.status === 0)) {
                let data = JSON.parse(xhr.response);
                $('#addNewRole').modal('show');
                $('#role_code').val(data.role[0].code);
                $('#role_label').val(data.role[0].label);
                $('#role_description').val(data.role[0].description);
                $('#role_id').val(data.role[0].id);
                // roleDescription.value = data.role[0].description;
            }
        };

        xhr.open("POST", url, true);
        xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');

        xhr.send();
    })
})

btnDeletes.forEach(function (btnDelete) {
    let url = deleteId.replace('__ID__', btnDelete.dataset.id);
    let btnDel = document.querySelector(".btnDelete");
    btnDelete.addEventListener('click', function (event) {
        event.preventDefault();
        $('#deleteRole').modal('show');
        btnDel.addEventListener('click', function (event) {
            xhr.onreadystatechange = function() {
                if (xhr.readyState === 4 && (xhr.status === 200 || xhr.status === 0)) {
                    let data = JSON.parse(xhr.response);
                    let className = (true === data.error) ? 'danger' : 'success';

                    if (true === data.error) {
                        $('#message').append(createALert(className, data.message));
                    } else {
                        $('#message').append(createALert(className, data.message));
                    }
                }
            };

            xhr.open("DELETE", url, true);
            xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');

            xhr.send();
        })
    })
})

function createALert(className, message) {
    let str = '<div class="removeDataMsg alert alert-'+className+'">'+message+
        '<button type="button" class="close" data-dismiss="alert" aria-label="Close">' +
        '<span aria-hidden="true">&times;</span>' +
        '</button></div>';

    return str;
}