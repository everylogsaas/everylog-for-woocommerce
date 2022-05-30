<?php
/*
Plugin Name: EveryLog For WooCommerce
Description: EveryLog For WooCommerce
Version: 1.0.2
Author: DevInterface S.R.L
Author URI: https://www.everylog.io/
*/

if (!defined('ABSPATH')) exit; // Exit if accessed directly

/**
 * Basic plugin definitions 
 * 
 * @package EveryLog
 * @since 1.0.0
 */
if (!defined('EFW_DIR')) {
  define('EFW_DIR', dirname(__FILE__));      // Plugin dir
}
if (!defined('EFW_VERSION')) {
  define('EFW_VERSION', '1.0.2');      // Plugin Version
}
if (!defined('EFW_NAME')) {
  define('EFW_NAME', 'EveryLog WooCommerce');      // Plugin Name
}
if (!defined('EFW_INC_DIR')) {
  define('EFW_INC_DIR', EFW_DIR . '/includes');   // Plugin include dir
}
if (!defined('EFW_ADMIN_DIR')) {
  define('EFW_ADMIN_DIR', EFW_INC_DIR . '/admin');  // Plugin admin dir
}

/**
 * Activation Hook
 *
 * Register plugin activation hook.
 *
 * @package EFW_NAME
 * @since EFW_VERSION
 */
register_activation_hook(__FILE__, 'efw_install');
function efw_install()
{
}

add_action('admin_init', 'efw_check_if_woocommerce_installed');
function efw_check_if_woocommerce_installed()
{
  if (is_admin() && current_user_can('activate_plugins') && !is_plugin_active('woocommerce/woocommerce.php')) {
    add_action('admin_notices', 'efw_woocommerce_check_notice');
    deactivate_plugins(plugin_basename(__FILE__));
    if (isset($_GET['activate'])) {
      unset($_GET['activate']);
    }
  } elseif (is_admin() && is_plugin_active('woocommerce/woocommerce.php')) {
    add_option('efw_do_activation_redirect', true);
    add_action('admin_init', 'efw_redirect');
  }
}

// Show dismissible error notice if WooCommerce is not present
function efw_woocommerce_check_notice()
{
?>
  <div class="alert alert-danger notice is-dismissible">
    <p>Sorry, but this plugin requires WooCommerce in order to work.
      So please ensure that WooCommerce is both installed and activated.
    </p>
  </div>
<?php
}

/**
 * Redirection on Activation
 *
 * @package EFW_NAME
 * @since EFW_VERSION
 */
function efw_redirect()
{
  if (get_option('efw_do_activation_redirect', false)) {
    delete_option('efw_do_activation_redirect');
    wp_redirect("admin.php?page=api-license");
    exit;
  }
}


add_action('woocommerce_register_form_start', 'efw_woocommerce_add_register_form_field');
function efw_woocommerce_add_register_form_field()
{
  woocommerce_form_field(
    'first_name',
    array(
      'type'        => 'text',
      'required'    => true,
      'label'       => 'First name'
    ),
    (isset($_POST['first_name']) ? $_POST['first_name'] : '')
  );
  woocommerce_form_field(
    'last_name',
    array(
      'type'        => 'text',
      'required'    => true,
      'label'       => 'Last name'
    ),
    (isset($_POST['last_name']) ? $_POST['last_name'] : '')
  );
}

add_action('woocommerce_register_post', 'efw_register_validate_fields', 10, 3);
function efw_register_validate_fields($username, $email, $errors)
{
  if (empty($_POST['first_name'])) {
    $errors->add('first_name_error', 'First Name is Required');
  }
  if (empty($_POST['last_name'])) {
    $errors->add('last_name_error', 'Last Name is Required');
  }
}


/**
 * Deactivation Hook
 *
 * Register plugin deactivation hook.
 *
 * @package EFW_NAME
 * @since EFW_VERSION
 */
register_deactivation_hook(__FILE__, 'efw_uninstall');
function efw_uninstall()
{
}

// Admin class handles most of admin panel functionalities of plugin
include_once(EFW_ADMIN_DIR . '/class-every-log-admin.php');
$efw_admin = new EFWAdmin();
$efw_admin->add_hooks();
