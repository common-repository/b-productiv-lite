<?php
	/*-------------------------------------------------------------------------------
	** File Name: 
	**			insert_pass_change.php
	**
	** File Description:  
	** 			Serves as the data processing change name and password file in the 
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
	**			menu.php, cl_access_denied 
	**			 
	** Accessible By:
	**			Employees, Contractors, Managers
	**
	** Notes: 
	** 			Copyright (c) C. A. Lettsome Services, LLC. 2018  All rights reserved.
	**			http://www.clydelettsome.com
	*-------------------------------------------------------------------------------*/
	if( isset( $_POST['bproductiv_pass_nonce'] ) && (wp_verify_nonce( $_POST['bproductiv_pass_nonce'], 'bproductiv_pass_action' )))
	{
		if($_COOKIE['timer']=="1"&&((current_user_can('employee'))||(current_user_can('administrator'))||(current_user_can('contractor'))))
		{
			//sanitize F_Name
			$sanitized_f_name = sanitize_text_field($_POST['f_name']);
			
			//sanitize L_Name
			$sanitized_l_name = sanitize_text_field($_POST['l_name']);
			
			//sanitize current_pass
			$sanitized_current_pass = sanitize_text_field(trim($_POST['current_pass']));

			//trimmed password
			$trimmed_new_password = trim($_POST['new_pass1']); // removes leading and trailing spaces
			$trimmed_new_password2 = trim($_POST['new_pass2']); // removes leading and trailing spaces

			//sanitize new_pass1
			$sanitized_new_pass1 = sanitize_text_field($trimmed_new_password);
			
			//sanitize new_pass2
			$sanitized_new_pass2 = sanitize_text_field($trimmed_new_password2); // special characters for passwords.
			
			//sanitize current_user_id
			$sanitized_current_user_id = intval($current_user_id);
			
			//escaped username
			$escaped_current_username = esc_html($current_username);
			
			//check for required fields from the form
			if ((!$sanitized_f_name) || (strlen($sanitized_f_name) >20))
			{
				//provide a first name
				$error_code = 1005; 
				header("Location: ".$_SESSION['c_page']."&error_code=".$error_code."");
				exit;
			}
			elseif((!$sanitized_l_name) || (strlen($sanitized_l_name) >25))
			{
				//provide a  last name
				$error_code = 1006;
				header("Location: ".$_SESSION['c_page']."&error_code=".$error_code."");
				exit;
			}
			elseif((!$sanitized_current_pass) || (strlen($sanitized_current_pass) >60))
			{
				//provide a  password
				$error_code = 1015;
				header("Location: ".$_SESSION['c_page']."&error_code=".$error_code."");
				exit;
			}	
			elseif(($sanitized_new_pass1!="" && ($sanitized_new_pass1 != $sanitized_new_pass2)) || ($sanitized_new_pass2 != "" && ($sanitized_new_pass1 != $sanitized_new_pass2)))
			{
				//password mismatch
				$error_code = 1011;
				header("Location: ".$_SESSION['c_page']."&error_code=".$error_code."");
				exit;
			}
			elseif(($sanitized_new_pass1 != "") && (!preg_match('/^[0-9A-Za-z!@#$%^&*()]{8,60}$/', $trimmed_new_password))) 
			{
				//provide a valid new password
				$error_code = 1012;
				header("Location: ".$_SESSION['c_page']."&error_code=".$error_code."");
				exit;
			}
			else
			{
				if(user_pass_ok($escaped_current_username,$sanitized_current_pass))
				{
					if(((strlen($sanitized_current_user_id) <=16)) && ($sanitized_current_user_id!=""))
					{
						$first_name = "first_name";
						$last_name = "last_name";
						
						//create and issue the query
						$sql = $wpdb->query( $wpdb->prepare( "UPDATE ".$wordpress_usermeta." SET meta_value = '%s' WHERE user_id=%d AND meta_key = '%s'", $sanitized_f_name, $sanitized_current_user_id, $first_name));

						//create and issue the query
						$sql = $wpdb->query( $wpdb->prepare( "UPDATE ".$wordpress_usermeta." SET meta_value = '%s' WHERE user_id=%d AND meta_key = '%s'", $sanitized_l_name, $sanitized_current_user_id, $last_name));
			 
						if($sanitized_new_pass1!="")
						{
							$sanitized_display_name = "".$sanitized_f_name." ".$sanitized_l_name.""; 
							//create and issue the query
							$sql = $wpdb->query( $wpdb->prepare( "UPDATE ".$wordpress_users." SET user_pass = MD5('%s'), display_name = '%s'  WHERE ID = %d", $sanitized_new_pass1, $sanitized_display_name, $sanitized_current_user_id));
							
							//redirect back to login form
							header("Location: ".esc_url(B_PRODUCTIV_PORTAL_LINK)."/?page=");
							exit;  
						}
						else
						{
							$sanitized_display_name = "".$sanitized_f_name." ".$sanitized_l_name."";
							//create and issue the query
							$sql = $wpdb->query( $wpdb->prepare( "UPDATE ".$wordpress_users." SET display_name = '%s'  WHERE ID = %d", $sanitized_display_name, $sanitized_current_user_id));
							
							//redirect back to login form
							header("Location: ".esc_url(B_PRODUCTIV_PORTAL_LINK)."/?page=menu");
							exit; 
						}
					}
					else
					{
						require_once(B_PRODUCTIV_PLUGIN_PATH .'templates/cl_access_denied.php');
					}
				}
				else
				{
					//password incorrect
					$error_code = 5003;
					header("Location: ".$_SESSION['c_page']."&error_code=".$error_code."");
					exit;
				}
			}
		}
		else
		{
			require_once(B_PRODUCTIV_PLUGIN_PATH .'templates/cl_access_denied.php');
		}
	}
	else
	{
		require_once(B_PRODUCTIV_PLUGIN_PATH.'templates/cl_forbidden.php');
	}
?>
