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
                console.log(data);
                if (false === data['error']) {
                    location.href = location.href;
                }
            },
        });
    });
})(jQuery);

function msg(message) {
    document.getElementById('message').innerHTML = message;

}