<?php
// Exit if accessed directly
if (!defined('ABSPATH')) exit;

/**
 * Admin Class
 *
 * Manage Admin Panel Class
 *
 * @package EFW_NAME
 * @since EFW_VERSION
 */

class EFWAdmin
{

  /**
   * Create menu page
   *
   * Adding required menu pages and submenu pages
   * to manage the plugin functionality
   * 
   * @package EFW_NAME
   * @since EFW_VERSION
   */
  public function efw_add_menu_page()
  {
    add_menu_page(__('Every Log', EFW_NAME), __('Every Log', EFW_NAME), 'manage_options', 'every_log_menu', '', 'dashicons-media-spreadsheet');
    add_submenu_page('every_log_menu', __('License', EFW_NAME), __('License', EFW_NAME), 'manage_options', 'every-log-license', array($this, 'efw_plugin_settings'));
    add_action('admin_init', array($this, 'efw_register_settings'));

    remove_submenu_page('every_log_menu', 'every_log_menu');
  }

  /**
   * Every Log Settings
   * 
   * @package EFW_NAME
   * @since EFW_VERSION
   */
  public function efw_plugin_settings()
  {
?>
    <div class="el_api_col-md-8 el_api_main">
      <form action="options.php" method="post">
        <?php
        settings_fields('every_log_plugin_options');
        do_settings_sections('every_log_plugin_setting'); ?>
        <input name="submit" id="efw_submit" class="button button-primary" type="submit" value="<?php esc_attr_e('Save'); ?>" />
      </form>
    </div>
  <?php
  }

  public function efw_register_settings()
  {
    register_setting('every_log_plugin_options', 'every_log_plugin_options');
    add_settings_section('every_log_settings', ' ', array($this, 'efw_plugin_section_text'), 'every_log_plugin_setting');

    add_settings_field('efw_plugin_setting_api_key', ' ', array($this, 'efw_plugin_setting_api_key'), 'every_log_plugin_setting', 'every_log_settings');
    add_settings_field('efw_plugin_setting_store_name', ' ', array($this, 'efw_plugin_setting_store_name'), 'every_log_plugin_setting', 'every_log_settings');
    add_settings_field('efw_plugin_setting_new_registration', ' ', array($this, 'efw_plugin_setting_new_registration'), 'every_log_plugin_setting', 'every_log_settings');
    add_settings_field('efw_plugin_setting_new_order_received', ' ', array($this, 'efw_plugin_setting_new_order_received'), 'every_log_plugin_setting', 'every_log_settings');
    add_settings_field('efw_plugin_setting_product_out_stock', ' ', array($this, 'efw_plugin_setting_product_out_stock'), 'every_log_plugin_setting', 'every_log_settings');
    add_settings_field('efw_plugin_settings_return_order', ' ', array($this, 'efw_plugin_settings_return_order'), 'every_log_plugin_setting', 'every_log_settings');
  }

  public function efw_plugin_section_text()
  { ?>
    <h2 class="el_api_title"><?php _e('EveryLog Settings', EFW_NAME); ?></h2>
  <?php
  }

  public function efw_plugin_setting_api_key()
  {
    $options = get_option('every_log_plugin_options');
    if ($options != '') {
      $api_key = $options['api_key'];
    } else {
      $api_key = ' ';
    }
    $val = ($api_key != ' ') ? $api_key : ' ';
  ?>
    <label><?php _e('API KEY', EFW_NAME); ?></label>
    <input id='efw_plugin_setting_api_key' name='every_log_plugin_options[api_key]' class='custom-control  setting_control' type='text' value='<?php _e($val, EFW_NAME); ?>' />
  <?php
  }

  public function efw_plugin_setting_store_name()
  {
    $options = get_option('every_log_plugin_options');
    if ($options != '') {
      $store_name = $options['store_name'];
    } else {
      $store_name = ' ';
    }
    $site_title = get_bloginfo('name');
    $val = ($store_name != ' ') ? $store_name : $site_title;
  ?>
    <label><?php _e('Project Name', EFW_NAME); ?></label>
    <input id='efw_plugin_setting_store_name' name='every_log_plugin_options[store_name]' class='custom-control setting_control' type='text' value='<?php _e($val, EFW_NAME); ?>' />
    <p id="warning-message" style="color: red;"></p>
  <?php
  }

  public function efw_plugin_setting_new_registration()
  {
    $options = get_option('every_log_plugin_options');
    if (!empty($options)) {
      $registration_event_notify = (array_key_exists('event_notify', $options)) ? $options['event_notify'] : '';
      $registration_push_notify = (array_key_exists('push_notify', $options)) ? $options['push_notify'] : '';
      $register_customTags = (!empty($options['custom_tags'])) ? $options['custom_tags'] : "";
    } else {
      $registration_event_notify = '';
      $registration_push_notify = '';
      $register_customTags = "";
    }
  ?>
    <div class="el_api_card">
      <div class="el_api_row">
        <div class="el_api_col-md-8">
          <div class="left-sec">
            <h2><?php esc_attr_e('New Registration', EFW_NAME); ?></h2>
            <div class="register-content">
              <div class="el_api_custom_switch">
                <input type="checkbox" class="el_api_custom_input" id="customSwitch1" name="every_log_plugin_options[event_notify]" value="yes" <?php ($registration_event_notify == 'yes') ? _e('checked', EFW_NAME) : ''; ?>>
                <label class="el_api_custom_label" for="customSwitch1"><?php esc_attr_e('Event Notification', EFW_NAME); ?></label>
              </div>
              <div class="el_api_custom_switch">
                <input type="checkbox" class="el_api_custom_input" id="customSwitch2" name="every_log_plugin_options[push_notify]" value="yes" <?php ($registration_push_notify == 'yes') ? _e('checked', EFW_NAME) : ''; ?>>
                <label class="el_api_custom_label" for="customSwitch2"><?php esc_attr_e('Push Notification', EFW_NAME); ?></label>
              </div>
              <div class="el_api_custom_field">
                <label for="validationServerUsername" class="el_api_normal_label"><?php esc_attr_e('Create Tags', EFW_NAME); ?></label>
                <div class="el_api_input_group">
                  <img src="<?php _e(plugin_dir_url(__FILE__) . 'images/tag.png', EFW_NAME); ?>">
                  <input type="text" class="el_api_normal_input" name="every_log_plugin_options[custom_tags]" placeholder="Write tags separated by comma" value="<?php _e($register_customTags, EFW_NAME); ?>">
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="el_api_col-md-4">
          <div class="right-sec">
            <h2><?php esc_attr_e('Notification Example', EFW_NAME); ?></h2>
            <div class="inner-info">
              <h4><?php esc_attr_e('Title and Summary', EFW_NAME); ?></h4>
              <div class="el_api_example">
                <h6><?php esc_attr_e('New User Subscription', EFW_NAME); ?></h6>
                <p><?php esc_attr_e('New subscription on "e-commerce name": Mario Rossi', EFW_NAME); ?></p>
              </div>
            </div>

            <div class="inner-info">
              <h4><?php esc_attr_e('Body', EFW_NAME); ?></h4>
              <div class="el_api_example">
                <h6><?php esc_attr_e('New subscription on "e-commerce name"', EFW_NAME); ?></h6>
                <p><?php esc_attr_e('Name: Mario', EFW_NAME); ?></p>
                <p><?php esc_attr_e('Surname: Rossi', EFW_NAME); ?></p>
                <p><?php esc_attr_e('Email: mario.rossi@example.com', EFW_NAME); ?></p>
                <p><?php esc_attr_e('User Id: 12345', EFW_NAME); ?></p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  <?php
  }

  public function efw_plugin_setting_new_order_received()
  {
    $options = get_option('every_log_plugin_options');
    if (!empty($options)) {
      $orderevent = (array_key_exists('order_received_event_notify', $options)) ? $options['order_received_event_notify'] : '';
      $orderpush = (array_key_exists('order_received_push_notify', $options)) ? $options['order_received_push_notify'] : '';
    } else {
      $orderevent = '';
      $orderpush = '';
    }
  ?>
    <div class="el_api_card">
      <div class="el_api_row">
        <div class="el_api_col-md-8">
          <div class="left-sec">
            <h2><?php _e('New Order Received', EFW_NAME); ?></h2>
            <div class="register-content">
              <div class="el_api_custom_switch">
                <input type="checkbox" class="el_api_custom_input" id="customSwitch3" name="every_log_plugin_options[order_received_event_notify]" value="yes" <?php ($orderevent == 'yes') ? _e("checked", EFW_NAME) : " " ?>>
                <label class="el_api_custom_label" for="customSwitch3"><?php _e('Event Notification', EFW_NAME); ?></label>
              </div>
              <div class="el_api_custom_switch">
                <input type="checkbox" class="el_api_custom_input" id="customSwitch4" name="every_log_plugin_options[order_received_push_notify]" value="yes" <?php ($orderpush == 'yes') ? _e("checked", EFW_NAME) : " " ?>>
                <label class="el_api_custom_label" for="customSwitch4"><?php _e('Push Notification', EFW_NAME); ?></label>
              </div>
              <div class="el_api_custom_field">
                <label for="validationServerUsername" class="el_api_normal_label"><?php _e('Create Tags', EFW_NAME); ?></label>
                <div class="el_api_input_group">
                  <img src="<?php _e(plugin_dir_url(__FILE__) . 'images/tag.png', EFW_NAME); ?>">
                  <input type="text" class="el_api_normal_input" name="every_log_plugin_options[order_received_custom_tags]" id="validationServerUsername" placeholder="Write tags separated by comma" aria-describedby="inputGroupPrepend3" value="<?php (!empty($options['order_received_custom_tags'])) ? _e($options['custom_tags'], EFW_NAME) : " " ?>">
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="el_api_col-md-4">
          <div class="right-sec">
            <h2><?php esc_attr_e('Notification Example', EFW_NAME); ?></h2>
            <div class="inner-info">
              <h4><?php esc_attr_e('Title and Summary', EFW_NAME); ?></h4>
              <div class="el_api_example">
                <h6><?php esc_attr_e('New order received', EFW_NAME); ?></h6>
                <p><?php esc_attr_e('New order received of ₤ XX, XX', EFW_NAME); ?></p>
              </div>
            </div>

            <div class="inner-info">
              <h4><?php esc_attr_e('Body', EFW_NAME); ?></h4>
              <div class="el_api_example">
                <h6><?php esc_attr_e('New order received on "e-commerce name" from mario.rossi@example.com', EFW_NAME); ?></h6>
                <p><?php esc_attr_e('Order Amount: ₤ XX, XX', EFW_NAME); ?></p>
                <p><?php esc_attr_e('Order Number: R01010101', EFW_NAME); ?></p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  <?php
  }

  public function efw_plugin_setting_product_out_stock()
  {
    $options = get_option('every_log_plugin_options');
    if (!empty($options)) {
      $out_stockevent_notify = (array_key_exists('out_stock_event_notify', $options)) ? $options['out_stock_event_notify'] : '';
      $out_stockpush_notify = (array_key_exists('out_stock_push_notify', $options)) ? $options['out_stock_push_notify'] : '';
    } else {
      $out_stockevent_notify = '';
      $out_stockpush_notify = '';
    }
  ?>
    <div class="el_api_card">
      <div class="el_api_row">
        <div class="el_api_col-md-8">
          <div class="left-sec">
          <h2><?php esc_attr_e('Product Out of Stock', EFW_NAME); ?> <b style="font-size: 13px;">(Activate low on stock email notification, for receive this event correctly)</b></h2>
            <div class="register-content">
              <div class="el_api_custom_switch">
                <input type="checkbox" class="el_api_custom_input" id="customSwitch5" name="every_log_plugin_options[out_stock_event_notify]" value="yes" <?php ($out_stockevent_notify == 'yes') ? _e('checked', EFW_NAME) : ''; ?> />
                <label class="el_api_custom_label" for="customSwitch5"><?php esc_attr_e('Event Notification', EFW_NAME); ?></label>
              </div>
              <div class="el_api_custom_switch">
                <input type="checkbox" class="el_api_custom_input" id="customSwitch6" name="every_log_plugin_options[out_stock_push_notify]" value="yes" <?php ($out_stockpush_notify == 'yes') ? _e('checked', EFW_NAME) : ''; ?> />
                <label class="el_api_custom_label" for="customSwitch6"><?php esc_attr_e('Push Notification', EFW_NAME); ?></label>
              </div>
              <div class="el_api_custom_field">
                <label for="validationServerUsername" class="el_api_normal_label"><?php esc_attr_e('Create Tags', EFW_NAME); ?></label>
                <div class="el_api_input_group">
                  <img src="<?php _e(plugin_dir_url(__FILE__) . 'images/tag.png', EFW_NAME); ?>">
                  <input type="text" class="el_api_normal_input" name="every_log_plugin_options[out_stock_custom_tags]" id="validationServerUsername" placeholder="Write tags separated by comma" aria-describedby="inputGroupPrepend3" value="<?php (!empty($options['out_stock_custom_tags'])) ? _e($options['out_stock_custom_tags'], EFW_NAME) : " "; ?>" />
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="el_api_col-md-4">
          <div class="right-sec">
            <h2><?php esc_attr_e('Notification Example', EFW_NAME); ?></h2>
            <div class="inner-info">
              <h4><?php esc_attr_e('Title and Summary', EFW_NAME); ?></h4>
              <div class="el_api_example">
                <h6><?php esc_attr_e('Product out of stock', EFW_NAME); ?></h6>
                <p><?php esc_attr_e('The "xyz" product is out of stock', EFW_NAME); ?></p>
              </div>
            </div>
            <div class="inner-info">
              <h4><?php esc_attr_e('Body', EFW_NAME); ?></h4>
              <div class="el_api_example">
                <h6><?php esc_attr_e('Product is out of stock', EFW_NAME); ?></h6>
                <p><?php esc_attr_e('Product Name: xyz', EFW_NAME); ?></p>
                <p><?php esc_attr_e('Product Id: 12345555', EFW_NAME); ?></p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  <?php
  }

  public function efw_plugin_settings_return_order()
  {
    $options = get_option('every_log_plugin_options');
    if (!empty($options)) {
      $return_order_event_notify = (array_key_exists('return_order_event_notify', $options)) ? $options['return_order_event_notify'] : '';
      $return_order_push_notify = (array_key_exists('return_order_push_notify', $options)) ? $options['return_order_push_notify'] : '';
    } else {
      $return_order_event_notify = '';
      $return_order_push_notify = '';
    }
  ?>
    <div class="el_api_card">
      <div class="el_api_row">
        <div class="el_api_col-md-8">
          <div class="left-sec">
            <h2><?php esc_attr_e('Return Request', EFW_NAME); ?></h2>
            <div class="register-content">
              <div class="el_api_custom_switch">
                <input type="checkbox" class="el_api_custom_input" id="customSwitch7" name="every_log_plugin_options[return_order_event_notify]" value="yes" <?php ($return_order_event_notify == 'yes') ? _e('checked', EFW_NAME) : ''; ?> />
                <label class="el_api_custom_label" for="customSwitch7"><?php esc_attr_e('Event Notification', EFW_NAME); ?></label>
              </div>
              <div class="el_api_custom_switch">
                <input type="checkbox" class="el_api_custom_input" id="customSwitch8" name="every_log_plugin_options[return_order_push_notify]" value="yes" <?php ($return_order_push_notify == 'yes') ? _e('checked', EFW_NAME) : ''; ?> />
                <label class="el_api_custom_label" for="customSwitch8"><?php esc_attr_e('Push Notification', EFW_NAME); ?></label>
              </div>
              <div class="el_api_custom_field">
                <label for="validationServerUsername" class="el_api_normal_label"><?php esc_attr_e('Create Tags', EFW_NAME); ?></label>
                <div class="el_api_input_group">
                  <img src="<?php _e(plugin_dir_url(__FILE__) . 'images/tag.png', EFW_NAME); ?>">
                  <input type="text" class="el_api_normal_input" name="every_log_plugin_options[return_order_custom_tags]" id="validationServerUsername" placeholder="Write tags separated by comma" aria-describedby="inputGroupPrepend3" value="<?php (!empty($options['return_order_custom_tags'])) ? _e($options['return_order_custom_tags'], EFW_NAME) : " "; ?>" />
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="el_api_col-md-4">
          <div class="right-sec">
            <h2><?php esc_attr_e('Notification Example', EFW_NAME); ?></h2>
            <div class="inner-info">
              <h4><?php esc_attr_e('Title and Summary', EFW_NAME); ?></h4>
              <div class="el_api_example">
                <h6><?php esc_attr_e('Return Request', EFW_NAME); ?></h6>
                <p><?php esc_attr_e('You have received a return request for order number R01010101', EFW_NAME); ?></p>
              </div>
            </div>
            <div class="inner-info">
              <h4><?php esc_attr_e('Body', EFW_NAME); ?></h4>
              <div class="el_api_example">
                <p><?php esc_attr_e('Mario Rossi (mario.rossi@example.com) has request the return of the product XYZ(product id)', EFW_NAME); ?></p>
                <p><?php esc_attr_e('Product was in the order with number: R01010101', EFW_NAME); ?></p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
<?php
  }

  public function efw_styles_scripts($hook)
  {
    if ('every-log_page_every-log-license' == $hook) {
      wp_enqueue_style('admin_every_log_style', plugin_dir_url(__FILE__) . 'css/style.css', true, EFW_VERSION);
      wp_enqueue_script('admin_every_log_script', plugin_dir_url(__FILE__) . 'js/script.js', true, EFW_VERSION);
    }
  }

  public function efw_user_registration($user_id)
  {
    $options = get_option('every_log_plugin_options');
    if (empty($options))
      return;

    $eventNotify = $options['event_notify'];
    if ($eventNotify == 'yes') {
      if (isset($_POST['first_name'])) {
        $first_name = $_POST['first_name'];
        update_user_meta($user_id, 'first_name', $_POST['first_name']);
      }
      if (isset($_POST['last_name'])) {
        $last_name = $_POST['last_name'];
        update_user_meta($user_id, 'last_name', $_POST['last_name']);
      }

      $projectid = $options['store_name'];
      $key = $options['api_key'];
      $pushnotify = ($options['push_notify'] != 'yes') ? false : true;
      $customTags = array($options['custom_tags']);

      $user = get_userdata($user_id);
      $email = $user->user_email;
      $display_name = $user->display_name;
      $site_url = site_url();

      $value = array(
        "projectId" => $projectid,
        "title"     => "New user subscription",
        "summary"   => "New subscriber on " . $projectid . ':' . $display_name,
        "body"      => "New subscriber on " . $projectid . "\nName: $first_name\nSurname: $last_name\nEmail: $email\nUser ID: $user_id",
        "tags"      => $customTags,
        "link"      => $site_url,
        "push"      => $pushnotify
      );

      $apiData = $this->send_every_log_data($key, json_encode($value));
      return $apiData;
    }
  }

  public function efw_new_order_received($order_id)
  {
    $options = get_option('every_log_plugin_options');
    if (empty($options))
      return;

    $eventNotify = (array_key_exists('order_received_event_notify', $options)) ? $options['order_received_event_notify'] : '';
    if ($eventNotify == 'yes') {
      $key = $options['api_key'];
      $order = wc_get_order($order_id);
      $order_number = $order->get_order_number();
      $order_total = $order->get_total();
      $projectid = $options['store_name'];
      $userId = $order->get_customer_id();
      $user = get_userdata($userId);
      $email = $user->user_email;
      $customTags = array($options['order_received_custom_tags']);
      $pushnotify = ($options['order_received_push_notify'] != 'yes') ? false : true;
      $site_url = site_url();

      $value = array(
        "projectId" => $projectid,
        "title"     => "New order received",
        "summary"   => "New order received of " . html_entity_decode(get_woocommerce_currency_symbol()) . $order_total,
        "body"      => "New order recevied on " . $projectid . " from " . $email . "\nOrder Amount: " . html_entity_decode(get_woocommerce_currency_symbol()) . "$order_total\nOrder Number: $order_number",
        "tags"      => $customTags,
        "link"      => $site_url,
        "push"      => $pushnotify
      );
      $apiData = $this->send_every_log_data($key, json_encode($value));
      return $apiData;
    }
  }

  public function efw_out_of_stock_for_managed_stock($product_to_check)
  {
    if (!$product_to_check->get_manage_stock())
      return;

    if (!$product_to_check->is_in_stock() || (($product_to_check->get_stock_status() == 'onbackorder') && $product_to_check->get_stock_quantity() <= 0)) {
      $productName = $product_to_check->get_name();
      $productId = $product_to_check->get_id();

      $options = get_option('every_log_plugin_options');
      if (empty($options))
        return;

      $eventNotify = (array_key_exists('out_stock_event_notify', $options)) ? $options['out_stock_event_notify'] : '';
      if ($eventNotify == 'yes') {
        $key = $options['api_key'];
        $projectid = $options['store_name'];
        $pushnotify = ($options['out_stock_push_notify'] != 'yes') ? false : true;
        $customTags = array($options['out_stock_custom_tags']);
        $site_url = site_url();

        $value = array(
          "projectId" => $projectid,
          "title"     => "Managed Product out of stock",
          "summary"   => "The " . $productName . " product is out of stock",
          "body"      => "Product Name: " . $productName . "\nProduct id: $productId",
          "tags"      => $customTags,
          "link"      => $site_url,
          "push"      => $pushnotify
        );

        $apiData = $this->send_every_log_data($key, json_encode($value));
        return $apiData;
      }
    }
  }

  public function efw_out_of_stock_for_unmanaged_stock($product_id, $stock_status)
  {
    $product_to_check = wc_get_product($product_id);

    if ($product_to_check->get_manage_stock())
      return;

    if (!$product_to_check->is_in_stock()) {
      $productName = $product_to_check->get_name();
      $productId = $product_to_check->get_id();

      $options = get_option('every_log_plugin_options');
      if (empty($options))
        return;

      $eventNotify = (array_key_exists('out_stock_event_notify', $options)) ? $options['out_stock_event_notify'] : '';
      if ($eventNotify == 'yes') {
        $key = $options['api_key'];
        $projectid = $options['store_name'];
        $pushnotify = ($options['out_stock_push_notify'] != 'yes') ? false : true;
        $customTags = array($options['out_stock_custom_tags']);
        $site_url = site_url();

        $value = array(
          "projectId" => $projectid,
          "title"     => "Unmanaged Product out of stock",
          "summary"   => "The " . $productName . " product is out of stock",
          "body"      => "Product Name: " . $productName . "\nProduct id: $productId",
          "tags"      => $customTags,
          "link"      => $site_url,
          "push"      => $pushnotify
        );

        $apiData = $this->send_every_log_data($key, json_encode($value));
        return $apiData;
      }
    }
  }


  public function efw_order_refunded($order_id, $refund_id)
  {
    $options = get_option('every_log_plugin_options');
    if (empty($options))
      return;

    $eventNotify = $options['return_order_event_notify'];
    if ($eventNotify == 'yes') {
      $key = $options['api_key'];
      $order = wc_get_order($order_id);
      $order_number = $order->get_order_number();
      $order_total = $order->get_total();
      $projectid = $options['store_name'];
      $userId = $order->get_customer_id();
      $user = get_userdata($userId);
      $email = $user->user_email;
      $display_name = $user->display_name;
      $customTags = array($options['return_order_custom_tags']);
      $pushnotify = ($options['return_order_push_notify'] != 'yes') ? false : true;
      $site_url = site_url();
      $items = $order->get_items();

      foreach ($items as $item) {
        $product_name = $item['name'];
        $product_id = $item['product_id'];
        $value = array(
          "projectId" => $projectid,
          "title"     => "Return Request",
          "summary"   => "You have received a return request for order number: " . $order_number,
          "body"      => "$display_name ($email) has requested the return of the product $product_name ($product_id)\nProduct was in the order with number: $order_number",
          "tags"      => $customTags,
          "link"      => $site_url,
          "push"      => $pushnotify
        );

        $apiData = $this->send_every_log_data($key, json_encode($value));
        return $apiData;
      }
    }
  }

  public function send_every_log_data($key, $value)
  {
    $url = "https://api.everylog.io/api/v1/log-entries";

    $data = array(
      'method'      => 'POST',
      'headers'     => array(
        'Authorization' => 'Bearer ' . $key,
        'Content-Type' => 'application/json',
      ),
      'body' => $value
    );
    $response = wp_remote_post($url, $data);

    if (is_wp_error($response)) {
      $error_message = $response->get_error_message();
      _e("Something went wrong: $error_message", EFW_NAME);
    } else {
      return $response;
    }
  }

  public function add_hooks()
  {
    add_action('admin_enqueue_scripts', array($this, 'efw_styles_scripts'));
    add_action('admin_menu', array($this, 'efw_add_menu_page'));
    add_action('user_register', array($this, 'efw_user_registration'));
    add_action('woocommerce_thankyou', array($this, 'efw_new_order_received'));
    add_action('woocommerce_no_stock', array($this, 'efw_out_of_stock_for_managed_stock'), 1, 2);
    add_action('woocommerce_product_set_stock_status', array($this, 'efw_out_of_stock_for_unmanaged_stock'), 1, 2);
    add_action('woocommerce_variation_set_stock_status', array($this, 'efw_out_of_stock_for_unmanaged_stock'), 1, 2);
    add_action('woocommerce_order_refunded', array($this, 'efw_order_refunded'), 10, 2);
  }
}
