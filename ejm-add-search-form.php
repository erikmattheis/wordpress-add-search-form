<?php
/**
 * @package ejm_add_search_form
 * @version 0.1
 */

/* Header modifications
* - Make sure JQuery is loaded */

function ejm_add_search_form_enqueue_scripts ()
{
  if(!is_admin()) {
    wp_deregister_script("jquery");
    wp_register_script("jquery", ("https://code.jquery.com/jquery-2.1.0.min.js"), FALSE, "2.1.0", TRUE);
    wp_enqueue_script("jquery");
  }
}
add_action("wp_enqueue_scripts", "ejm_add_search_form_enqueue_scripts");

/* Footer modifications
* - output the search form and move
* it to the appropriate location within the DOM */

function ejm_add_search_form_js ()
{

  $prepend_or_append = (get_option("is_first_child") == 1) ? "prepend" : "append";
  $str = '<ul id="ejm-add-search-form-ul"><li id="ejm-add-search-form-li"><form id="ejm-add-search-form" action="' . bloginfo('home') . '/" method="GET" class="search-form">'
    . '<input id="ejm-add-search-form-search-box" name="s" size="20" type="text" value="" placeholder="Search directory" onfocus="this.placeholder = \'\'" onblur="this.placeholder = \'Search directory\'"></li></ul></form>'
    . '<script>jQuery(document).ready(function() {' . PHP_EOL
    . 'parentElem = jQuery("#' . get_option("parent_element_id") . ', .' . get_option("parent_element_id") . '").first();' . PHP_EOL
    . 'if (parentElem.length != 0) {' . PHP_EOL
    . 'if (parentElem.is("ul")) { parentElem.prepend(jQuery("#ejm-add-search-form-li")); }' . PHP_EOL
    . 'else { parentElem.' . $prepend_or_append . ' (jQuery("#ejm-add-search-form")); }' . PHP_EOL
    . 'jQuery("#ejm-add-search-form-ul").remove();' . PHP_EOL
    . '}' . PHP_EOL
    . '});'
    . '</script>';
    echo $str;
}

add_action("wp_footer", "ejm_add_search_form_js");

/* Admin modifications */

function ejm_add_search_form_menu()
{

  add_menu_page('Add Search Form Settings',
    'Add Search Form Settings',
    'administrator',
    __FILE__,
    'ejm_add_search_form_settings_page',
    plugins_url('/images/search-hat.png', __FILE__));

  add_action( 'admin_init', 'ejm_add_search_form_register_mysettings' );

}
add_action("admin_menu", "ejm_add_search_form_menu");

function ejm_add_search_form_register_mysettings()
{
  register_setting( 'ejm-add-search-form', 'parent_element_id' );
  register_setting( 'ejm-add-search-form', 'is_first_child' );
}

function ejm_add_search_form_settings_page() {
?>
<div class="wrap">
<h2>Add Search Menu Options</h2>

<form method="post" action="options.php">

    <?php /* Note to other developers. Wordpress admin styling
    expects tables? In <blink>2014</blink>? */ ?>
    <table class="form-table">
        <?php settings_fields('ejm-add-search-form' ); ?>
        <?php do_settings_sections('ejm-add-search-form'); ?>
        <tr valign="top">
        <th scope="row">ID (or unique class name) of element to add search form as child</th>
        <td><input type="text" name="parent_element_id" value="<?php echo get_option('parent_element_id'); ?>" /></td>
        </tr>
        <tr valign="top">
        <th scope="row">Add form as the first or last child of the parent element</th>
        <td><label for="ejm-first-or-last-first">First</label> <input id="ejm-first-or-last-first" type="radio" name="is_first_child" value="1" <?php checked("1", get_option('is_first_child')); ?> /><br>
        <label for="ejm-first-or-last-last">Last</label> <input id="ejm-first-or-last-last" type="radio" name="is_first_child" value="0" <?php checked("0", get_option('is_first_child')); ?> /></td>
        </tr>
    </table>
    
    <?php submit_button(); ?>

</form>
</div>
<?php
}



