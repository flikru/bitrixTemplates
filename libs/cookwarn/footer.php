<script type="text/javascript">
    "use strict";
    (function($) {
        if (!window['localStorage'] || window.localStorage.getItem('up-advert-cookwarn')) return;
        $.get('/include/cookwarn.php').done(function($widget) {
            $widget = $($widget).on('click', '.up-cookwarn__btn', function() {
                window.localStorage.setItem('up-advert-cookwarn', 1);
                $widget.fadeOut(600);
            }).appendTo(document.body);
        }).fail(function(jqXHR, status, error) {
            console.error(error);
        });
    })(jQuery);
</script>