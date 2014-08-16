var CategoriesModule = '';

jQuery(document).ready(function() {

    CategoriesModule = new KMListModule({
        'module': 'mod_km_categories',
        'view': 'catalog',
        'table': 'categories',
        'sortable': false
    });

    CategoriesModule.list = ProductsList;

    jQuery('.mod_km_categories ul li a.show').on('click', function() {
        jQuery(this).removeClass('show');
        jQuery(this).addClass('hides');
        jQuery(this).parents('li:first').find('ul:first').addClass('opened');
        jQuery(this).parents('li:first').find('ul:first').slideDown(300);
        return false;
    });

    jQuery('.mod_km_categories ul li a.hides').on('click', function() {
        jQuery(this).removeClass('hides');
        jQuery(this).addClass('show');
        jQuery(this).parents('li:first').find('ul:first').removeClass('opened');
        jQuery(this).parents('li:first').find('ul:first').slideUp(300);
        return false;
    });

});