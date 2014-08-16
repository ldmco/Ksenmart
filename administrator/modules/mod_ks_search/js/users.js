jQuery(document).ready(function() {

    jQuery('.mod_km_search .inputbox').on('keypress', function(e) {
        if (e.keyCode == 13) {
            UsersList.loadListPage(1);
            return false;
        }
    });

    jQuery('.mod_km_search .button').on('click', function() {
        UsersList.loadListPage(1);
    });

});