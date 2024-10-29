<?php
	/*-------------------------------------------------------------------------------
	** File Name: 
	**			insert_reset.php
	**
	** File Description:  
	** 			Serves as the data processing password reset file in the 
	**			B-Productiv Plugin
	**
	** File Last Updated On: 
	**			6/20/2018
	**
	** Original Author: 
	**			Clyde A. Lettsome, PhD, PE
	**
	** Last Editor: 
	**			Clyde A. Lettsome, PhD, PE
	**
	** File Layer: 
	** 			Data Layer
	**
	** File Calls//Submits: 
	**			menu.php 
	**			 
	** Accessible By:
	**			Employees (update), Managers (update, insert, delete)
	**
	** Notes: 
	** 			Copyright (c) C. A. Lettsome Services, LLC. 2018  All rights reserved.
	**			http://www.clydelettsome.com
	*-------------------------------------------------------------------------------*/
	if( isset( $_POST['bproductiv_reset_nonce'] ) && (wp_verify_nonce( $_POST['bproductiv_reset_nonce'], 'bproductiv_reset_action' )))
	{
		require_once(''.B_PRODUCTIV_PLUGIN_PATH.'templates/cl_static_database.php');
		require_once(''.B_PRODUCTIV_PLUGIN_PATH.'templates/cl_static_functions.php');
		
		$default_email = esc_html(get_option('bproductiv_default_email'));
		
		//sanitize userName
		$sanitized_username = sanitize_user($_POST['username'],0);
		
		$randval = wp_generate_password();

		//check for required fields from the form
		if ((!$sanitized_username) || (strlen($sanitized_username) >16)) 
		{
			//provide a  username
			$error_code = 1020;
			header("Location: ".esc_url(B_PRODUCTIV_PORTAL_LINK)."/?page=reset_password&error_code=$error_code");
			exit;
		}
		else
		{ 
			//create and issue the query
			$sql = $wpdb->query( $wpdb->prepare( "UPDATE ".$wordpress_users." SET user_pass = MD5('%s') WHERE user_login = '%s'", $randval, $sanitized_username));

			//verify username was found and update password
			if ($sql==1) 
			{	
				//get the email address
				$email_result = $wpdb->get_results( $wpdb->prepare( "SELECT user_email, display_name FROM ".$wordpress_users." WHERE user_login = '%s'", $sanitized_username));
				foreach( $email_result AS $emailArray )
				{
					//fetch results from array
					$email_address = esc_html($emailArray->user_email);
					$display_name = esc_html($emailArray->display_name);
				}
						
				//create a From: mailheader
				$headers = "From: ".B_PRODUCTIV_DOMAIN."<".$default_email."> \r\n";

				$headers .= "Content-Type: text/plain\r\n";
				
				$subject = "No Reply: Information You Requested";
				$message = "Dear $display_name,\n\n Your temporary password is $randval . \n\n If you did not request a temporary password, please notify your manager.";
				
				//mail password
				 mail($email_address, $subject, $message, $headers); 
				
				//redirect back to login form
				header("Location: ".esc_url(B_PRODUCTIV_PORTAL_LINK)."/?page=");
				exit; 
			} 
			else 
			{
				//**retrieve error message stating that username does not match our records
				$error_code = 1020;
				header("Location: ".esc_url(B_PRODUCTIV_PORTAL_LINK)."/?page=reset_password&error_code=$error_code");
				exit;
			} 
		} 
	}
	else
	{
		require_once(B_PRODUCTIV_PLUGIN_PATH.'templates/cl_forbidden.php');
	}
?>
