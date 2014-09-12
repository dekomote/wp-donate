<?php 
/* 	
	Plugin Name: WP Donate Fork
	Plugin URI: https://github.com/dekomote/wp-donate
	Description: Integration of the payment system donate using to AuthorizeNet - Fork
	Author: Ketan Ajani, Dejan Noveski
	Version: 1.8
*/
session_start();

@define ( 'WP_DONATE_VERSION', '1.8' );
@define ( 'WP_DONATE_PATH',  WP_PLUGIN_URL . '/' . end( explode( DIRECTORY_SEPARATOR, dirname( __FILE__ ) ) ) );
include_once('includes/donate-function.php');
include_once('includes/donate-display.php');
include_once('includes/donate-options.php');

add_action('wp_print_styles', 'load_wp_donate_css');
add_action('wp_print_scripts', 'load_wp_donate_js');
add_action('admin_print_styles', 'load_wp_donate_admin_css');
add_action('admin_print_scripts', 'load_wp_donate_admin_js');

function load_wp_donate_js() 
{

}

function load_wp_donate_admin_js() 
{
    wp_enqueue_script('jquery-ui-core');
    wp_enqueue_script('jquery-ui-tabs');
}

function load_wp_donate_css() 
{
    $options = get_option('wp_donate_options');
    if ( $options['donate_css_switch'] ) {
        if ( $options['donate_css_switch'] == 'Yes') {
            wp_enqueue_style('donate-payment-css', WP_DONATE_PATH . '/css/wp-donate-display.css');
        }
    }
    wp_enqueue_style('donate-widget-css', WP_DONATE_PATH . '/css/wp-donate-widget.css');
}

function load_wp_donate_admin_css() {
    wp_enqueue_style('donate-css', WP_DONATE_PATH . '/css/wp-donate-admin.css');
}

function my_add_menu_items()
{
    add_menu_page( 'WP Donate', 'WP Donate', 'activate_plugins', 'wp_donate', 'my_render_list_page' );
    add_options_page( 'WP Donate', 'WP Donate', 'manage_options', 'wp_donate', 'wp_donate_options_page' );
}
add_action( 'admin_menu', 'my_add_menu_items' );

function my_render_list_page()
{

}

add_shortcode('donation_form', 'wp_donate_form');


function wp_donate_response()
{
    return $_SESSION["donate_msg"];
}

add_shortcode('donate_response', 'wp_donate_response');

if(isset($_POST['setting']))
{
  if($_POST['setting']==1)
  {
    $table_name = $wpdb->prefix . "donate_settings";
    $wpdb -> insert($table_name, array(
        "id" => 1,
        "mod" => "",
        "api_login" => "",
        "key" => "",
        "success_url" => "",
        "fail_url" => "",
      ));
    $wpdb -> update($table_name,
      array(
          'mod' => $_REQUEST['authnet_mode'],
          'api_login' => $_REQUEST['x_login'],
          'key' => $_REQUEST['x_tran_key'],
          'success_url' => $_REQUEST['success_url'],
          'fail_url' => $_REQUEST['fail_url'],
        ),
      array(
          'id' => 1,
        )
      );
  }
}

register_activation_hook( __FILE__, 'donate_install' );

global $donate_db_version;
$donate_db_version = "1.1";

function donate_install() {
   global $wpdb;
   global $donate_db_version;

   $table_name = $wpdb->prefix . "donate";
   $sql = "CREATE TABLE IF NOT EXISTS `$table_name` (
      `id` int(11) NOT NULL AUTO_INCREMENT,
      `first_name` varchar(255) CHARACTER SET utf8 NOT NULL,
      `last_name` varchar(255) CHARACTER SET utf8 NOT NULL,
      `email` varchar(255) NOT NULL,
      `organization` varchar(255) CHARACTER SET utf8 NULL,
      `address` varchar(255) CHARACTER SET utf8 NULL,
      `city` varchar(255) CHARACTER SET utf8 NULL,
      `country` varchar(255) CHARACTER SET utf8 NULL,
      `state` varchar(255) CHARACTER SET utf8 NULL,
      `zip` varchar(255) CHARACTER SET utf8 NULL,
      `phone` varchar(255) NULL,
      `amount` varchar(255) NOT NULL,
      `comment` text NULL,
      `form_id` varchar(255) NOT NULL,
      `donation_type` varchar(255) NOT NULL DEFAULT 'Free Ammount',
      `status` varchar(255) NOT NULL,
      `date` datetime NOT NULL,
      PRIMARY KEY (`id`),
      UNIQUE KEY `id` (`id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1";

  $donate_setting = $wpdb->prefix . "donate_settings";
  $donate_setting_sql = "CREATE TABLE IF NOT EXISTS `$donate_setting` (
      `id` int(11) NOT NULL AUTO_INCREMENT,
      `mod` varchar(255) NOT NULL,
      `api_login` varchar(255) NOT NULL,
      `key` varchar(255) NOT NULL,
      `success_url` varchar(255) NULL,
      `fail_url` varchar(255) NULL,
      PRIMARY KEY (`id`),
      UNIQUE KEY `id` (`id`)
    ) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2";

  require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
  dbDelta( $sql );
  dbDelta( $donate_setting_sql );
 
  add_option( "donate_db_version", $donate_db_version );
}

function donate_install_data() {
   global $wpdb;
   $welcome_name = "Mr. WordPress";
   $welcome_text = "Congratulations, you just completed the installation!";
   $rows_affected = $wpdb->insert( $table_name, array( 'time' => current_time('mysql'), 'name' => $welcome_name, 'text' => $welcome_text ) );
}

function create_donate_type() {
    $labels = array(
        'name'           => __('Donation Types'),
        'singular_name'  => __('Donation Type'),
        'add_new' => _x('Add New', 'Donation Type'),
        'add_new_item' => __('Add New Donation Type'),
        'edit_item' => __('Edit Donation Type Item'),
        'new_item' => __('New Donation Type Item'),
        'view_item' => __('View Donation Type Item'),
        'search_items' => __('Search Donation Types'),
        'not_found' =>  __('Nothing found'),
        'not_found_in_trash' => __('Nothing found in Trash'),
        'parent_item_colon' => ''
    );
    $args = array(
        'labels'         => $labels,
        'public'         => true,
        'has_archive'    => false,
        'menu_position'  => 55,
        'description'    => 'Donation Types / Levels for Donation forms',
        'rewrite'        =>
            array('slug' => 'reviews'),
        'supports'       =>
            array( 'title', ),
    );
 
    register_post_type('Donation Type', $args);
}

add_action('init', 'create_donate_type');


function donate_admin_init(){
  add_meta_box("donate-type-settings-meta", "Donation Type Settings", "donate_type_settings", "donationtype", "normal", "low");
}

function donate_type_settings(){
  global $post;
  $custom = get_post_custom($post -> ID);
  $amount = $custom["amount"][0];
  $form_id = $custom["form_id"][0];
  ?>
  <label><strong>Amount: </strong>
  <input type="text" name="amount" value="<?php echo $amount; ?>" /></label><br/>
  <label><strong>Form ID: </strong>
  <input type="text" name="form_id" value="<?php echo $form_id; ?>" /></label><br/>
  <?php
}

function save_donate_types(){
  global $post;
  if($post->post_type == "donationtype"){
    update_post_meta($post-> ID, "amount", $_POST["amount"]);
    update_post_meta($post-> ID, "form_id", $_POST["form_id"]);
  }
}

add_action("admin_init", "donate_admin_init");
add_action('save_post', 'save_donate_types');
?>
