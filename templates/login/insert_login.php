<?php
	/*-------------------------------------------------------------------------------
	** File Name: 
	**			insert_login.php
	**
	** File Description:  
	** 			Serves as the data processing login file in the 
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
	if( isset( $_POST['bproductiv_login_nonce'] ) && (wp_verify_nonce( $_POST['bproductiv_login_nonce'], 'bproductiv_login_action' )))
	{
		//sanitize username
		$sanitized_username=sanitize_user($_POST['user'],0);
		
		//sanitize password
		$sanitized_password = preg_replace("/[^a-zA-Z0-9!@#$%^&*()]\s\s+/", "", $_POST['password']); // \s\s+ removes leading and trailing spaces
		
		//sanitize password2
		$sanitized_password2 = preg_replace("/\s\s+/", "", $_POST['password']); // \s\s+ removes leading and trailing spaces
		
		//check for required fields from the form
		if ((!$sanitized_username) || (strlen($sanitized_username) >16))
		{    
			//provide a  username
			$error_code = 1001;
			header("Location: ".esc_url(B_PRODUCTIV_PORTAL_LINK)."/?page=&error_code=$error_code");
			exit;	
		}
		elseif ((!$sanitized_password) || ($sanitized_password!=$sanitized_password2) || (strlen($sanitized_password) >60))
		{	
			//provide a valid password
			$error_code = 1002;
			header("Location: ".esc_url(B_PRODUCTIV_PORTAL_LINK)."/?page=&error_code=$error_code");
			exit;  	
		}
		else
		{
			//create and issue the query 00000000000 
			if(user_pass_ok($sanitized_username,$sanitized_password))
			{ 
				$result = $wpdb->get_results( $wpdb->prepare( "SELECT ID FROM ".$wordpress_users." WHERE user_login = '%s'", $sanitized_username));
				foreach( $result AS $resultArray )
				{
					$id = esc_html($resultArray->ID);
				}
				
				// fetch capabilities
				$capabilities = $wpdb->prefix . "capabilities";
				$result2 = $wpdb->get_results( $wpdb->prepare( "SELECT meta_value FROM ".$wordpress_usermeta." WHERE user_id = %d AND meta_key = '%s'", $id, $capabilities));

				foreach( $result2 AS $result2Array )
				{
					$auth_code = esc_html($result2Array->meta_value);
				}
				
				//set current user and register them as WP current user.
				$WP_user = get_user_by( 'id', $id ); 
				if( $WP_user ) 
				{
					wp_set_current_user( $id, $WP_user->user_login );
					wp_set_auth_cookie( $id );
					do_action( 'wp_login', $WP_user->user_login );
				}			
				
				//identify the user type
				if((strpos($auth_code, "administrator"))||(strpos($auth_code, "employee"))||(strpos($auth_code, "contractor")))
				{
					$timer = "1";	//set timed cookie
				}
				else
				{
					$timer = "0"; //who are you
				}
				
				//set authorization
				setcookie("timer", $timer, time()+3600*8, "/", B_PRODUCTIV_DOMAIN, 0);

				if(($timer == "1"))
				{	
					//go to the main menu
					header("Location: ".esc_url(B_PRODUCTIV_PORTAL_LINK)."/?page=menu");
					exit;
				}
				else
				{
					//go to the main menu
					header("Location: ".esc_url(B_PRODUCTIV_PORTAL_LINK)."/?page=menu");
					exit;
				}  	
			}
			else
			{
				//information does not match
				$error_code = 1018;
				header("Location: ".esc_url(B_PRODUCTIV_PORTAL_LINK)."/?page=&error_code=$error_code");
				exit; 
			}   
		}
	}
	else
	{
		require_once(B_PRODUCTIV_PLUGIN_PATH.'templates/cl_forbidden.php');
	}	
?>