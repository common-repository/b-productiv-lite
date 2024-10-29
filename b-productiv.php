<?php
	/*
	Plugin Name: B-Productiv Lite
	Plugin URI:  http://clydelettsome.com/blog/b-productiv/ ‎
	Description: The purpose of this plugin is to improve business productivity for small businesses and organizations especially those with employees and contractors that telecommute or work remotely. This plugin helps you to set and maintain operational structure, assists with process flow, and encourages workers to remain on task, all which lead to improved results.
	Version: 1.0.0
	Author: C A Lettsome Services, LLC
	Author URI: http://clydelettsome.com
	Requires at least: 4.6
	Tested up to: 4.9.6
	License: GPLv3 or later
	*/
	
	//function bproductiv_get_page_by_slug
	function bproductiv_get_page_by_slug($slug) 
	{
		if ($pages = get_pages())
			foreach ($pages as $page)
				if ($slug === $page->post_title) 
					return $page;
				else
					return false;
	}

	//*************************Insert portal template********************************
	function bproductiv_portal_creation()
	{
		global $wpdb;
		$table_name = "".$wpdb->prefix . "bproductiv_";
		
		//Find the Admin folder path
		$bproductiv_admin_url_finder = str_replace( get_bloginfo( 'url' ) . '/', ABSPATH, get_admin_url() );
		define( 'B_PRODUCTIV_ADMIN_FINDER', ''.$bproductiv_admin_url_finder.'');
		
		//Find the content folder path
		$bproductiv_content_url_finder = str_replace( get_bloginfo( 'url' ) . '/', ABSPATH, content_url() );
		define( 'B_PRODUCTIV_CONTENT_FINDER', ''.$bproductiv_content_url_finder.'');
		
		//Find the site domain
		$site_url = get_site_url();
		$url_parse = wp_parse_url($site_url);
		define( 'B_PRODUCTIV_DOMAIN', ''.$url_parse['host'].'');
		define( 'B_PRODUCTIV_PORTAL_LINK', ''.$site_url.'/b-productiv-portal');
		
		define( 'B_PRODUCTIV_PLUGIN_PATH', plugin_dir_path(__FILE__));
		
		include(B_PRODUCTIV_PLUGIN_PATH.'/templates/b-productiv_template_config.php');
	}
	add_shortcode('b-productiv-portal', 'bproductiv_portal_creation');	
 
	//****************Create portal page when plugin is activated*********************
	function bproductiv_add_my_custom_page() 
	{
		// Create post object if it does not already exist
		if (! bproductiv_get_page_by_slug('b-productiv-portal')) 
		{
			$my_post = array(
			  'post_title'    => wp_strip_all_tags( 'B-Productiv Portal' ),
			  'post_content'  => '[b-productiv-portal]',
			  'post_status'   => 'publish',
			  'post_author'   => 1,
			  'post_type'     => 'page',
			);
			// Insert the post into the database
			$newvalue=wp_insert_post( $my_post );
			//Save the id in the database
			update_option( 'hclpage', $newvalue ); 
		}
	}
	register_activation_hook(__FILE__, 'bproductiv_add_my_custom_page');

	//****************Delete portal page when plugin is deactivated*******************
	function bproductiv_delete_my_plugin_page() 
	{
		//  Delete post object
		$the_page_id = get_option( 'hclpage' );
		if( $the_page_id ) 
		{
			wp_delete_post( $the_page_id, TRUE ); // this will delete the portal page
		}
			
	}
	register_deactivation_hook( __FILE__, 'bproductiv_delete_my_plugin_page' );


	//*******************************Admin page menu set up****************
	add_action('admin_menu', 'bproductiv_plugin_setup_menu');
	function bproductiv_plugin_setup_menu()
	{
		add_menu_page( 'B-Productiv Lite', 'B-Productiv Lite', 'manage_options', 'b-productiv-plugin', 'bproductiv_set_up' );
	}

	//*******************************Admin page include********************
	function bproductiv_set_up()
	{
		//get site url
		$site_url = get_site_url();
		define( 'B_PRODUCTIV_PORTAL_LINK', ''.$site_url.'/b-productiv-portal');
		
		//Find the content folder path
		$bproductiv_content_url_finder = str_replace( get_bloginfo( 'url' ) . '/', ABSPATH, content_url() );
		define( 'B_PRODUCTIV_CONTENT_FINDER', ''.$bproductiv_content_url_finder.'');
		
		//Find the Admin folder path
		$bproductiv_admin_url_finder = str_replace( get_bloginfo( 'url' ) . '/', ABSPATH, get_admin_url() );
		define( 'B_PRODUCTIV_ADMIN_FINDER', ''.$bproductiv_admin_url_finder.'');
		
		require_once(B_PRODUCTIV_CONTENT_FINDER . '/plugins/b-productiv-lite/wp_menu/cl_settings.php');  		
	}

	//***********************************Table set up**********************
	function bproductiv_table_creator()
	{
		//Find the Admin folder path
		$bproductiv_admin_url_finder = str_replace( get_bloginfo( 'url' ) . '/', ABSPATH, get_admin_url() );
		define( 'B_PRODUCTIV_ADMIN_FINDER', ''.$bproductiv_admin_url_finder.'');
	
		global $bproductiv_db_version;
		$bproductiv_db_version = "1.0.0";

		global $wpdb;
		$table_name = $wpdb->prefix . "bproductiv_";
			
		// **************Create the Error Message Table*******************
		$sql_error = "CREATE TABLE IF NOT EXISTS `" . $table_name . "error_msg`(
		`id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
		`error_code` int(11) NOT NULL,
		`error_english` text) ";
			
		// if changes, it will update table
		require_once(B_PRODUCTIV_ADMIN_FINDER. "includes/upgrade.php");
		dbDelta($sql_error); 
		
		//create a version. Update version if necessary
		add_option("error_msg_database_bproductiv_lite_version",$bproductiv_db_version);
		
		
		// **************Create the Tasks Table*******************
		$sql_tasks = "CREATE TABLE IF NOT EXISTS `" . $table_name . "tasks`(
		`id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
		`notice` text,
		`assigned_to` varchar(16),
		`poster` varchar(16),
		`priority` tinyint(1),
		`task_status` tinyint(1),
		`datee` date) ";
			
		// if changes, it will update table
		require_once(B_PRODUCTIV_ADMIN_FINDER . "includes/upgrade.php");
		dbDelta($sql_tasks); 
		
		//create a version. Update version if necessary
		add_option("tasks_database_bproductiv_lite_version",$bproductiv_db_version);
		
		
		// **************Create the Calendar Table*******************
		$sql_calendar = "CREATE TABLE IF NOT EXISTS `" . $table_name . "calendar`(
		`id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
		`notice` text,
		`next_date` date,
		`task_frequency` int(2),
		`task_repeat` tinyint(1),
		`assigned_to` varchar(16),
		`poster` varchar(16),
		`task_status` tinyint(1)) ";
			
		// if changes, it will update table
		require_once(B_PRODUCTIV_ADMIN_FINDER . "includes/upgrade.php");
		dbDelta($sql_calendar); 
		
		//create a version. Update version if necessary
		add_option("calendar_database_bproductiv_lite_version",$bproductiv_db_version);
		
		// **************Create the User Info Table*******************
		$sql_user_info = "CREATE TABLE IF NOT EXISTS `" . $table_name . "user_info`(
		`id` bigint(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
		`user_id` varchar(16),
		`username` varchar(16),
		`address` varchar(50),
		`city` varchar(30),
		`country` varchar(2),
		`state` char(3),
		`zip` varchar(10),
		`phone` varchar(12))";
			
		// if changes, it will update table
		require_once(B_PRODUCTIV_ADMIN_FINDER . "includes/upgrade.php");
		dbDelta($sql_user_info); 
		
		//create a version. Update version if necessary
		add_option("user_info_database_bproductiv_lite_version",$bproductiv_db_version);
	}
	register_activation_hook(__FILE__, "bproductiv_table_creator");

	//-------------------Populate the Error Message Table--------------
	function bproductiv_create_table_insert_data()
	{
		global $wpdb;
		$table_name = $wpdb->prefix . "bproductiv_";
		$table_name = "".$table_name."error_msg";

		$wpdb->insert($table_name, array('id' => "", 'error_code' => 1001, 'error_english' => "Please enter a username."));
		$wpdb->insert($table_name, array('id' => "", 'error_code' => 1002, 'error_english' => "Please enter a valid password."));
		$wpdb->insert($table_name, array('id' => "", 'error_code' => 1003, 'error_english' => "Please re-type your password."));
		$wpdb->insert($table_name, array('id' => "", 'error_code' => 1004, 'error_english' => "Please enter a valid email address."));
		$wpdb->insert($table_name, array('id' => "", 'error_code' => 1005, 'error_english' => "Please enter your first name."));
		$wpdb->insert($table_name, array('id' => "", 'error_code' => 1006, 'error_english' => "Please enter your last name."));
		$wpdb->insert($table_name, array('id' => "", 'error_code' => 1007, 'error_english' => "Please enter your address."));
		$wpdb->insert($table_name, array('id' => "", 'error_code' => 1008, 'error_english' => "Please enter the city you live in."));
		$wpdb->insert($table_name, array('id' => "", 'error_code' => 1009, 'error_english' => "Please enter the state, province, commonwealth, or territory where you live."));
		$wpdb->insert($table_name, array('id' => "", 'error_code' => 1010, 'error_english' => "Please enter your zip code."));
		$wpdb->insert($table_name, array('id' => "", 'error_code' => 1011, 'error_english' => "Your new password and re-entered new password must match."));
		$wpdb->insert($table_name, array('id' => "", 'error_code' => 1012, 'error_english' => "Invalid new password. Passwords must be 8-60 characters long, can only contain alphanumeric characters, and these special characters: !@#$%^&*()"));
		$wpdb->insert($table_name, array('id' => "", 'error_code' => 1013, 'error_english' => "Your email address is not valid."));
		$wpdb->insert($table_name, array('id' => "", 'error_code' => 1014, 'error_english' => "This username is being used by another user.  Please enter another username."));
		$wpdb->insert($table_name, array('id' => "", 'error_code' => 2001, 'error_english' => "Please enter a username and a password."));
		$wpdb->insert($table_name, array('id' => "", 'error_code' => 2002, 'error_english' => "The username and/or email address you provided does not match our records."));
		$wpdb->insert($table_name, array('id' => "", 'error_code' => 5001, 'error_english' => "Please refresh the page. Enter a username and password."));
		$wpdb->insert($table_name, array('id' => "", 'error_code' => 5002, 'error_english' => "Incorrect username or password.  Please refresh the page."));
		$wpdb->insert($table_name, array('id' => "", 'error_code' => 3001, 'error_english' => "You cannot use this coupon unless you order more items."));
		$wpdb->insert($table_name, array('id' => "", 'error_code' => 3002, 'error_english' => "That coupon has expired or does not exist in our system."));
		$wpdb->insert($table_name, array('id' => "", 'error_code' => 1015, 'error_english' => "Your password is incorrect."));
		$wpdb->insert($table_name, array('id' => "", 'error_code' => 4001, 'error_english' => "Please enter a phone number in the format indicated."));
		$wpdb->insert($table_name, array('id' => "", 'error_code' => 4002, 'error_english' => "Invalid time/date are format."));
		$wpdb->insert($table_name, array('id' => "", 'error_code' => 4003, 'error_english' => "Please choose a delivery method."));
		$wpdb->insert($table_name, array('id' => "", 'error_code' => 4004, 'error_english' => "Please select the type of event."));
		$wpdb->insert($table_name, array('id' => "", 'error_code' => 6000, 'error_english' => "You must login before you can bid on this item."));
		$wpdb->insert($table_name, array('id' => "", 'error_code' => 6001, 'error_english' => "Please enter a valid bid."));
		$wpdb->insert($table_name, array('id' => "", 'error_code' => 6002, 'error_english' => "Time has expired for the auction on this item."));
		$wpdb->insert($table_name, array('id' => "", 'error_code' => 5003, 'error_english' => "Incorrect password."));
		$wpdb->insert($table_name, array('id' => "", 'error_code' => 1016, 'error_english' => "Username and passwords can only contain alpha-numeric and special characters.  No spaces."));
		$wpdb->insert($table_name, array('id' => "", 'error_code' => 7005, 'error_english' => "Please enter the type of depreciation."));
		$wpdb->insert($table_name, array('id' => "", 'error_code' => 7006, 'error_english' => "Please vendors/retailers name."));
		$wpdb->insert($table_name, array('id' => "", 'error_code' => 1017, 'error_english' => "Please select your home country."));
		$wpdb->insert($table_name, array('id' => "", 'error_code' => 7004, 'error_english' => "Please enter the purchase date."));
		$wpdb->insert($table_name, array('id' => "", 'error_code' => 7003, 'error_english' => "Please enter the number of days under warranty."));
		$wpdb->insert($table_name, array('id' => "", 'error_code' => 7002, 'error_english' => "Please enter the item\'s product serial number or product number."));
		$wpdb->insert($table_name, array('id' => "", 'error_code' => 7001, 'error_english' => "Please enter the manufacturer\'s name."));
		$wpdb->insert($table_name, array('id' => "", 'error_code' => 7000, 'error_english' => "Please enter the item name."));
		$wpdb->insert($table_name, array('id' => "", 'error_code' => 1300, 'error_english' => "Please enter a title for the invoice."));
		$wpdb->insert($table_name, array('id' => "", 'error_code' => 7008, 'error_english' => "Please indicate if the purchaser was reimbursed for this purchase."));
		$wpdb->insert($table_name, array('id' => "", 'error_code' => 8000, 'error_english' => "Please select a subject."));
		$wpdb->insert($table_name, array('id' => "", 'error_code' => 8001, 'error_english' => "Please type a message."));
		$wpdb->insert($table_name, array('id' => "", 'error_code' => 1018, 'error_english' => "The information you entered does not match the records for this account.  Please try again."));
		$wpdb->insert($table_name, array('id' => "", 'error_code' => 3333, 'error_english' => "Please select a security question."));
		$wpdb->insert($table_name, array('id' => "", 'error_code' => 3334, 'error_english' => "Please type an answer."));
		$wpdb->insert($table_name, array('id' => "", 'error_code' => 1019, 'error_english' => "Select the permission level for this user."));
		$wpdb->insert($table_name, array('id' => "", 'error_code' => 9000, 'error_english' => "Please enter a school."));
		$wpdb->insert($table_name, array('id' => "", 'error_code' => 9001, 'error_english' => "Please select a course."));
		$wpdb->insert($table_name, array('id' => "", 'error_code' => 9002, 'error_english' => "Please select an assignment category."));
		$wpdb->insert($table_name, array('id' => "", 'error_code' => 9003, 'error_english' => "Please enter a grade."));
		$wpdb->insert($table_name, array('id' => "", 'error_code' => 9004, 'error_english' => "Please enter a term/quarter."));
		$wpdb->insert($table_name, array('id' => "", 'error_code' => 1200, 'error_english' => "Please enter a payment type."));
		$wpdb->insert($table_name, array('id' => "", 'error_code' => 1201, 'error_english' => "Enter the name as shown on the card."));
		$wpdb->insert($table_name, array('id' => "", 'error_code' => 1202, 'error_english' => "Enter a valid card number."));
		$wpdb->insert($table_name, array('id' => "", 'error_code' => 1205, 'error_english' => "Enter the card's expiration date."));
		$wpdb->insert($table_name, array('id' => "", 'error_code' => 1204, 'error_english' => "This card has expired."));
		$wpdb->insert($table_name, array('id' => "", 'error_code' => 1206, 'error_english' => "Enter the billing address for the card."));
		$wpdb->insert($table_name, array('id' => "", 'error_code' => 1207, 'error_english' => "Enter the city for the card."));
		$wpdb->insert($table_name, array('id' => "", 'error_code' => 1203, 'error_english' => "Enter a valid cvv for the card."));
		$wpdb->insert($table_name, array('id' => "", 'error_code' => 1208, 'error_english' => "Enter the billing state for the card."));
		$wpdb->insert($table_name, array('id' => "", 'error_code' => 1209, 'error_english' => "Enter a valid billing zip code."));
		$wpdb->insert($table_name, array('id' => "", 'error_code' => 1210, 'error_english' => "Enter the billing phone number for the card."));
		$wpdb->insert($table_name, array('id' => "", 'error_code' => 1211, 'error_english' => "You must accept the terms of use."));
		$wpdb->insert($table_name, array('id' => "", 'error_code' => 1000, 'error_english' => "Incorrect word. Try again."));
		$wpdb->insert($table_name, array('id' => "", 'error_code' => 7007, 'error_english' => "Please enter the original purchase price."));
		$wpdb->insert($table_name, array('id' => "", 'error_code' => 1301, 'error_english' => "Please enter an invoice number."));
		$wpdb->insert($table_name, array('id' => "", 'error_code' => 1302, 'error_english' => "Please enter the original date for this debt."));
		$wpdb->insert($table_name, array('id' => "", 'error_code' => 1303, 'error_english' => "Please enter the terms for payment."));
		$wpdb->insert($table_name, array('id' => "", 'error_code' => 1304, 'error_english' => "Please enter the remainder due."));
		$wpdb->insert($table_name, array('id' => "", 'error_code' => 1305, 'error_english' => "Please enter the Paypal code here. Login to the business account to get the code. Use the Online Credit Card Payment - QB Invoice # as a template. Change the QB Invoice number to the Quickbooks Invoice number." ));
		$wpdb->insert($table_name, array('id' => "", 'error_code' => 1306, 'error_english' => "Please enter the project name as seen on the invoice, estimate, or based on project location."));
		$wpdb->insert($table_name, array('id' => "", 'error_code' => 1307, 'error_english' => "Invalid contact person. Please enter the contact person for the project."));
		$wpdb->insert($table_name, array('id' => "", 'error_code' => 1020, 'error_english' => "Please enter a valid username."));
		$wpdb->insert($table_name, array('id' => "", 'error_code' => 1400, 'error_english' => "Please enter additional comments and questions relevant to this support ticket in the discussion section."));
		$wpdb->insert($table_name, array('id' => "", 'error_code' => 1401, 'error_english' => "Please assign to a team member."));
		$wpdb->insert($table_name, array('id' => "", 'error_code' => 1402, 'error_english' => "Please indicate the invoice and/or project related to this support ticket."));
		$wpdb->insert($table_name, array('id' => "", 'error_code' => 1403, 'error_english' => "Please indicate the department or group most likely to best service this support ticket."));
		$wpdb->insert($table_name, array('id' => "", 'error_code' => 1405, 'error_english' => "Please provide a priority level."));
		$wpdb->insert($table_name, array('id' => "", 'error_code' => 1406, 'error_english' => "Please provide a descriptive subject/title for this support ticket."));
		$wpdb->insert($table_name, array('id' => "", 'error_code' => 1308, 'error_english' => "Please add the length of the warranty for this invoice. Note: Unless otherwise told the standard length is 60 days."));
		$wpdb->insert($table_name, array('id' => "", 'error_code' => 999, 'error_english' => "Please complete all required fields."));
		$wpdb->insert($table_name, array('id' => "", 'error_code' => 1100, 'error_english' => "Assign this task to an manager, employee or contractor."));
		$wpdb->insert($table_name, array('id' => "", 'error_code' => 1101, 'error_english' => "Assign a priority level to this task."));
		$wpdb->insert($table_name, array('id' => "", 'error_code' => 1102, 'error_english' => "Please describe the task."));
		$wpdb->insert($table_name, array('id' => "", 'error_code' => 1021, 'error_english' => "Please enter a role for this user."));
		$wpdb->insert($table_name, array('id' => "", 'error_code' => 1022, 'error_english' => "Please indicate if the member\'s account is active or inactive."));
		$wpdb->insert($table_name, array('id' => "", 'error_code' => 1407, 'error_english' => "Please enter a valid url."));
		$wpdb->insert($table_name, array('id' => "", 'error_code' => 1500, 'error_english' => "Indicate the vendor of the item(s) to be purchased."));
		$wpdb->insert($table_name, array('id' => "", 'error_code' => 1501, 'error_english' => "Indicate where the item(s) should be shipped."));
		$wpdb->insert($table_name, array('id' => "", 'error_code' => 1502, 'error_english' => "Indicate how the item(s) should be shipped."));
		$wpdb->insert($table_name, array('id' => "", 'error_code' => 1503, 'error_english' => "Indicate the payment method."));
		$wpdb->insert($table_name, array('id' => "", 'error_code' => 1504, 'error_english' => "Indicate the payment terms."));
		$wpdb->insert($table_name, array('id' => "", 'error_code' => 1505, 'error_english' => "Indicate the quantity of items that should be purchased."));
		$wpdb->insert($table_name, array('id' => "", 'error_code' => 1506, 'error_english' => "Indicate the part/item number for the item to be ordered."));
		$wpdb->insert($table_name, array('id' => "", 'error_code' => 1507, 'error_english' => "Please describe the item to be purchased."));
		$wpdb->insert($table_name, array('id' => "", 'error_code' => 1508, 'error_english' => "Please remove any commas and semicolons."));
		$wpdb->insert($table_name, array('id' => "", 'error_code' => 2300, 'error_english' => "Please enter a street address."));
		$wpdb->insert($table_name, array('id' => "", 'error_code' => 2301, 'error_english' => "Please enter a city."));
		$wpdb->insert($table_name, array('id' => "", 'error_code' => 2302, 'error_english' => "Please enter a county."));
		$wpdb->insert($table_name, array('id' => "", 'error_code' => 2303, 'error_english' => "Please enter a state."));
		$wpdb->insert($table_name, array('id' => "", 'error_code' => 2304, 'error_english' => "Please enter a zip code."));
		$wpdb->insert($table_name, array('id' => "", 'error_code' => 2305, 'error_english' => "Please enter a facility type"));
		$wpdb->insert($table_name, array('id' => "", 'error_code' => 2306, 'error_english' => "Please enter a construction type."));
		$wpdb->insert($table_name, array('id' => "", 'error_code' => 2307, 'error_english' => "Please enter the number of floors or levels."));
		$wpdb->insert($table_name, array('id' => "", 'error_code' => 2308, 'error_english' => "Please enter the total number of square feet."));
		$wpdb->insert($table_name, array('id' => "", 'error_code' => 2309, 'error_english' => "Please enter at least one type of engineering service."));
		$wpdb->insert($table_name, array('id' => "", 'error_code' => 2310, 'error_english' => "Please enter at least one service."));
		$wpdb->insert($table_name, array('id' => "", 'error_code' => 2311, 'error_english' => "Please enter the turn-around time."));
		$wpdb->insert($table_name, array('id' => "", 'error_code' => 2312, 'error_english' => "Please enter a document format for delivery."));
		$wpdb->insert($table_name, array('id' => "", 'error_code' => 2313, 'error_english' => "Please enter a delivery method."));
		$wpdb->insert($table_name, array('id' => "", 'error_code' => 1023, 'error_english' => "Please enter a valid first and last name."));
		$wpdb->insert($table_name, array('id' => "", 'error_code' => 2400, 'error_english' => "Please enter an appropriate estimated width for the pc board layout."));
		$wpdb->insert($table_name, array('id' => "", 'error_code' => 2401, 'error_english' => "Please enter an appropriate estimated length for the pc board layout."));
		$wpdb->insert($table_name, array('id' => "", 'error_code' => 2402, 'error_english' => "Please enter the desired number of layers for the pc board layout."));
		$wpdb->insert($table_name, array('id' => "", 'error_code' => 2403, 'error_english' => "Please enter the approximate number of parts in the build of material. Currently we are not designing boards with more than 50 parts."));
		$wpdb->insert($table_name, array('id' => "", 'error_code' => 2404, 'error_english' => "Please indicate the level of urgency for your request."));
		$wpdb->insert($table_name, array('id' => "", 'error_code' => 1103, 'error_english' => "Please describe the notice/alert."));
		$wpdb->insert($table_name, array('id' => "", 'error_code' => 1104, 'error_english' => "Please assign this notice/alert to someone."));
		$wpdb->insert($table_name, array('id' => "", 'error_code' => 1105, 'error_english' => "Please indicate the next time the assigned person should be alerted."));
		$wpdb->insert($table_name, array('id' => "", 'error_code' => 1106, 'error_english' => "Please indicate if this alert needs to be repeated."));
		$wpdb->insert($table_name, array('id' => "", 'error_code' => 1107, 'error_english' => "Please indicate how often this notice/alert should be repeated."));
		$wpdb->insert($table_name, array('id' => "", 'error_code' => 1108, 'error_english' => "There is a mis-match in your repeat and frequency response."));
		$wpdb->insert($table_name, array('id' => "", 'error_code' => 1109, 'error_english' => "Please indicate the frequency for this task."));
	}
	register_activation_hook(__FILE__, "bproductiv_create_table_insert_data");

	//-------------------Delete Error Message Table--------------
	function bproductiv_delete_error_table() 
	{
		global $wpdb;
		$table_name = $wpdb->prefix . "bproductiv_";
		$table_name = "".$table_name."error_msg";

		$wpdb->query("DROP TABLE IF EXISTS $table_name");
		
		//Remove the database version
		delete_option('error_msg_database_bproductiv_lite_version');
	}
	register_deactivation_hook( __FILE__, 'bproductiv_delete_error_table' );

	//--------------------Uninstall Tables------------------------
	register_uninstall_hook(__FILE__,'bproductiv_uninstall_plugin');
	function bproductiv_uninstall_plugin()
	{
		$delete_data = get_option('bproductiv_delete_data');
		if($delete_data == '1')
		{
			global $wpdb;
			$table_name = $wpdb->prefix . "bproductiv_";
			
			//Remove tables (if it exists)
			$tasks_table_name = "".$table_name."tasks";
			$wpdb->query("DROP TABLE IF EXISTS $tasks_table_name");
			
			//Remove the database version
			delete_option('tasks_database_bproductiv_lite_version');
			
			$calendar_table_name = "".$table_name."calendar";
			$wpdb->query("DROP TABLE IF EXISTS $calendar_table_name");
			
			//Remove the database version
			delete_option('calendar_database_bproductiv_lite_version');
			
			$user_info_table_name = "".$table_name."user_info";
			$wpdb->query("DROP TABLE IF EXISTS $user_info_table_name");
			
			//Remove the database version
			delete_option('user_info_database_bproductiv_lite_version');
			
			//Remove the remaining stored options
			delete_option('bproductiv_delete_data');
			delete_option('bproductiv_time_zone');
			delete_option('bproductiv_date_format');
			delete_option('bproductiv_super_admin');
			delete_option('bproductiv_default_email');
		}
	}

	//-------------------Add Custom Role--------------
	function bproductiv_custom_role_activate() 
	{
		add_role( 'contractor', 'Contractor',
		array(
		'read' => true
		) );
		
		add_role( 'employee', 'Employee',
		array(
		'read' => true,
		'delete_posts' => true,
		'edit_posts' => true,
		'upload_files' => true	) );

	}
	register_activation_hook( __FILE__, 'bproductiv_custom_role_activate' );

	//-------------------Delete Custom Role--------------
	function bproductiv_custom_role_deactivation() 
	{
		remove_role('contractor');
		
		remove_role('employee');
	}
	register_deactivation_hook( __FILE__, 'bproductiv_custom_role_deactivation' );

?>