// Affichage produits avec filtre

$(document).ready(function(){

    let $card = $('.instru');

    $card.click(function (e){

        let selector = $(e.target).attr('data-filter');
        $('.grid').isotope({
            filter : selector
        }).css({ margin : '20px' });

        return false;
    })
});