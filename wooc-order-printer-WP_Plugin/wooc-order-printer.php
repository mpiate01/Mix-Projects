<?php
/**
	Plugin Name: Woocommerce orders printer
	Plugin URI: http://www.c-eight.com/
	Description: This plugin prints automatically new orders after refreshing the page
	Version: 1.0.6
	Author: Manuel Piatesi
	Author URI : http://www.c-eight.com/
	License: GPL2 
  Licence URI: https://www.gnu.org/licenses/gpl-2.0.html
	Text Domain: Woocommerce orders printer is free software: you can redistribute it and/or modify
	it under the terms of the GNU General Public License as published by
 the Free Software Foundation, either version 2 of the License, or
	any later version.
 
	Woocommerce orders printer is distributed in the hope that it will be useful,
	but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
	GNU General Public License for more details.
	You should have received a copy of the GNU General Public License
	along with Woocommerce orders printer. If not, see {License URI}.
**/
?>
<?php



if ( is_admin() ) 
{  
  



define('woo_ord_pnt_dir', plugin_dir_path( __FILE__ ) );

require_once woo_ord_pnt_dir . 'core/init.php';    





register_activation_hook(__FILE__, 'activation_woocop_order_printer');

function activation_woocop_order_printer()
{
  Session::setSession('activation','true');
}


register_deactivation_hook(__FILE__, 'deactivation_woocop_order_printer');

function deactivation_woocop_order_printer()
{
  if(Session::existsSession('activation'))
  {
      Session::deleteSession('activation');
  }
}

add_action( 'admin_menu', 'woocop_add_admin_menu' );
add_action( 'admin_init', 'woocop_settings_init' );
/*add_action( 'admin_head', 'my_custom_fonts');

function my_custom_fonts() {
  echo '<link rel="stylesheet" href="' .  woo_ord_pnt_dir . 'css/style.css" type="text/css" media="print" />';
}*/

add_action('admin_head', 'registerCustomAdminCss');
function registerCustomAdminCss(){
$src = "/wp-content/plugins/" . dirname(plugin_basename( __FILE__)) ."/css/style.css";
$handle = "customAdminCss";
wp_register_script($handle, $src);
wp_enqueue_style($handle, $src, array(), false, false);
    }


function woocop_add_admin_menu(  ) { 
	//parameters details
	 //add_management_page( $page_title, $menu_title, $capability,
// $menu_slug, $function );
	 //add a new setting page udner setting menu
  //add_management_page('Footer Text', 'Footer Text', 'manage_options',
 //__FILE__, //'footer_text_admin_page');
  //add new menu and its sub menu 
  
	create_plugin_refresh_settings_page();
	create_plugin_submenu_settings_page();

//	add_submenu_page( 'printer_setting_page', 'General Setting', 'Order Setting', 'manage_options', 'wooc-order-printer', 'woocop_options_page' );
}


function create_plugin_submenu_settings_page() {
	// Add the menu item and page
	$slug_parent = 'refreshing_setting_page';
	$page_title = 'General Setting';
	$menu_title = 'Order Setting';
	$capability = 'manage_options';
	$slug = 'order_setting_page';
	$callback = 'woocop_options_page';//array( $this, 'plugin_settings_page_content' );
	
	add_submenu_page( $slug_parent, $page_title, $menu_title, $capability, $slug, $callback );

	// Add the menu item and page
	$slug_parent = 'refreshing_setting_page';
	$page_title = 'Printer Setting';
	$menu_title = 'Printer Setting';
	$capability = 'manage_options';
	$slug = 'printer_setting_page';
	$callback = 'woocop_printer_page';//array( $this, 'plugin_settings_page_content' );
	
	add_submenu_page( $slug_parent, $page_title, $menu_title, $capability, $slug, $callback );
}


function create_plugin_refresh_settings_page() {
	// Add the menu item and page
	$page_title = 'Woo Order Printer';
	$menu_title = 'Start printing';
	$capability = 'manage_options';
	$slug = 'refreshing_setting_page';
	$callback = 'woocop_settings_page';//array( $this, 'plugin_settings_page_content' );
	$icon = 'dashicons-lightbulb';
	//$position = 100;

	add_menu_page( $page_title, $menu_title, $capability, $slug, $callback, $icon); //,$position );
}

function woocop_settings_init(  ) { 

	register_setting( 'pluginPage', 'woocop_settings' );

	add_settings_section(
		'woocop_pluginPage_section', 
		__( 'Select the last order been printed', 'wordpress' ), 
		'woocop_settings_section_callback', 
		'pluginPage'
	);

	add_settings_field( 
		'woocop_select_field_0', 
		__( 'ID order:', 'wordpress' ), 
		'woocop_select_field_0_render', 
		'pluginPage', 
		'woocop_pluginPage_section' 
	);



}


function woocop_select_field_0_render(  ) 
{ 
	$orders = new Order();
	$vals = $orders->getListOrders();
	$val_last_order = $orders->_getLastOrder();
	$options = get_option( 'woocop_settings' );
	?>
	<select name='orders'>
		
	<?php
		if(!is_array($vals))
		{
			echo '<option value="0">No new orders. Select the Save changes button</option>';
		}
		else
		{	
			echo '<option value="0">From first order</option>';
			foreach ($vals as $key => $value) 
	       	{	
	        	$selected = ($value->order_id == $val_last_order) ? 'selected' : '';
	            echo '<option value="' . escape($value->order_id) . '" ' . $selected . '>' . escape($value->order_id) . '</option>';
	        }
	    }
    ?>
	</select>

<?php

}


function woocop_settings_section_callback(  ) { 

	echo __( '<p>Please select an ID order</p>', 'wordpress' );

}

function woocop_printer_page(  ) { 

	$css = new CSS();
	$css->getCSS();
	$width = $css->getWidthPage();
	$size = $css->getFontSizePage();
	
	?>
	<form action='' method='post'>

		<h2>Printer Settings</h2>
		<label for= "width">Width in cm:</label>
		<input type="number" name="width" min = "1" max "40" value = "<?php echo ($_POST['width']) ? escape($_POST['width']) : escape($width);  ?>" required>
		<label for= "fontsize">Fontsize in pixels:</label>
		<input type="number" name="fontsize" min = "1" max "40" value = "<?php echo (isset($_POST['fontsize'] )) ? escape($_POST['fontsize']) : escape($size); ?>" required>
		<input type="reset">		
		<?php
		submit_button();
		save_css_database();
		/*settings_fields( 'pluginPage' );
		do_settings_sections( 'pluginPage' );
		submit_button();
		save_last_order_database();
		*/?>

	</form>
	<?php
	
}

function save_css_database()
{
	if(isset($_POST['submit']))
	{
		if (is_numeric($_POST['width']) && is_numeric($_POST['fontsize']))
		{
			$newCSS = array(
				'width' => $_POST['width'],
				'size'	=> $_POST['fontsize'] 
			);
			$css = new CSS();
			try {
					if($css->updateCSS($newCSS))
					{
						echo '<div id="message" class="updated below-h2"><h3>Printer settings updated</h3></div>';
					}
				} catch (Exception $e) {
					
					echo '<div id="message" class="updated below-h2"><h3>' . $e->getMessage() . '</h3></div>';
				}	

		}
		else
		{
			echo '<div id="message" class="updated below-h2"><h3>Values must be numeric!</h3></div>';
		}
	}
}

function woocop_options_page(  ) { 

	?>
	<form action='' method='post'>

		<h2>Order Settings</h2>

		<?php
		settings_fields( 'pluginPage' );
		do_settings_sections( 'pluginPage' );
		submit_button();
		save_last_order_database();
		?>

	</form>
	<?php
	
}

function save_last_order_database()
{
	if(isset($_POST['orders']) && is_numeric($_POST['orders']))
  	{
    	$orders = new Order();
    	$id = escape($_POST['orders']);
    	$orders->activation_setup_plugin($id);
    	Session::deleteSession('activation');
    	//Redirect to printer
    	$URL = get_admin_url() . 'admin.php?page=refreshing_setting_page';
    	echo "<script type='text/javascript'>document.location.href='{$URL}';</script>";
		echo '<META HTTP-EQUIV="refresh" content="0;URL=' . $URL . '">';
  	}  
}



  //add_menu_page('Woo Order Printer', 'Printing Settings', 'manage_options', 'printer_setting_page', 'woocop_settings_page', 'dashicons-lightbulb');  //'plugin_dir_url( __FILE__ ) . 'images/icon.png''



  /**
  *	COME AGGIUNGERE UN SUBMENU
  *	add_submenu_page( 'printer_setting_page', 'Page title', 'Sub-menu title', 'manage_options', 'child-submenu-handle', 'my_magic_function');
  *
  **/

/*
 * Add plugin action links.
 *
 * Add a link to the settings page on the plugins.php page.
 *
 * @since 1.0.0
 *
 * @param  array  $links List of existing plugin action links.
 * @return array         List of modified plugin action links.
 */

function myplugin_settings_link( $links ) {
    $url = get_admin_url() . 'admin.php?page=order_setting_page';
    $settings_link = '<a href="' . $url . '">' . __('Settings', 'textdomain') . '</a>';
    array_unshift( $links, $settings_link );
    return $links;
}
 
function myplugin_after_setup_theme() {
     add_filter('plugin_action_links_' . plugin_basename(__FILE__), 'myplugin_settings_link');
}
add_action ('after_setup_theme', 'myplugin_after_setup_theme');

/*
*
* TO ADD LINKS ON THE RIGHT SIDE
*
**
function set_plugin_meta($links, $file) {
	
	$plugin = plugin_basename(__FILE__);

	// create link
	if ($file == $plugin) {
		return array_merge(
			$links,
			array( sprintf( '<a href="options-general.php?page=%s">%s</a>', $plugin, __('Settings') ) )
		);
	}

	return $links;
}

add_filter( 'plugin_row_meta', 'set_plugin_meta', 10, 2 );
*/


// mt_settings_page() displays the page content for the Test Settings submenu
function woocop_settings_page() {
    echo '<h2 class="no-print">' . __( 'Refreshing Settings Configurations', 'menu-test' ) . '</h2>';
	require_once 'printing.php';
}

}

?>