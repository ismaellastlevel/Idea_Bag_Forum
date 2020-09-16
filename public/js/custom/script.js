/** Convertit une date au format jj/mm/yyyy hh:ii */
Date.prototype.convertToString = function()
{
    var mm = this.getMonth() + 1;
    var dd = this.getDate();
    var hh = this.getHours();
    var ii = this.getMinutes();
    
    return [ 
        (dd > 9 ? '' : '0') + dd + '/', 
        (mm > 9 ? '' : '0') + mm + '/', 
        this.getFullYear() + ' ',
        (hh > 9 ? '' : '0') + hh + ':',
        (ii > 9 ? '' : '0') + ii
    ].join('');
}

/** adapter la hauteur d'un scroller selon l'ecran */
$('.scroll-y').each(function() {
    var scrollerTop = $(this).offset().top;
    var h = window.innerHeigh || document.documentElement.clientHeight || document.body.clientHeight;
    $(this).outerHeight(h - scrollerTop - 48);  
});

