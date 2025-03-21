function yaMetrika(target) {
    console.log("yaMetrika: " + target);
    ym(46739388, 'reachGoal', target);
}

$(document).ready(function () {
    $(".to_cart").on('click', function () {
        yaMetrika('basket_add_click');
    });

    $("[data-action=compare]").on('click', function () {
        yaMetrika('compare_click');
    });

    $(".banners-big__buttons-item a[download]").on('click', function () {
        yaMetrika('catalog_click');
    });

    $('a[href^="tel:"]').on('click', function (event) {
        yaMetrika('phone_click');
    });

    $('a[href^="mailto:"]').on('click', function (event) {
        yaMetrika('mail_click');
    });
});
console.log('metrika.js');