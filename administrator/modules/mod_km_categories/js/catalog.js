var CategoriesModule = '';

jQuery(document).ready(function() {

    CategoriesModule = new KMListModule({
        'module': 'mod_km_categories',
        'view': 'catalog',
        'table': 'categories',
        'sortable': true
    });

    CategoriesModule.list = ProductsList;

    jQuery('body').on('click', '.mod_km_categories ul li a.show', function() {
        jQuery(this).removeClass('show');
        jQuery(this).addClass('hides');
        jQuery(this).parents('li:first').find('ul:first').addClass('opened');
        jQuery(this).parents('li:first').find('ul:first').slideDown(300);
        return false;
    });

    jQuery('body').on('click', '.mod_km_categories ul li a.hides', function() {
        jQuery(this).removeClass('hides');
        jQuery(this).addClass('show');
        jQuery(this).parents('li:first').find('ul:first').removeClass('opened');
        jQuery(this).parents('li:first').find('ul:first').slideUp(300);
        return false;
    });

});