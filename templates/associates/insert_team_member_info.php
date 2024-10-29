<?php
	/*-------------------------------------------------------------------------------
	** File Name: 
	**			insert_pass_change.php
	**
	** File Description:  
	** 			Serves as the data processing team member information file in the 
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
	if( isset( $_POST['bproductiv_team_nonce'] ) && (wp_verify_nonce( $_POST['bproductiv_team_nonce'], 'bproductiv_team_action' )))
	{
		if ($_COOKIE['timer']=="1"&&(((($_POST['action']=="update")&&(current_user_can('contractor')))||($_POST['action']=="update")&&(current_user_can('employee')))||(($_POST['action']=="update")&&(current_user_can('administrator'))&&($_POST[update_my_information]=="1"))))
		{
			//sanitize the zip
			$sanitized_zip = sanitize_text_field($_POST['zip']);
			
			//sanitize the state
			$sanitized_state = sanitize_text_field($_POST['state']);
			
			//sanitize the country
			$sanitized_country = sanitize_text_field($_POST['country']);
			
			//sanitize the city
			$sanitized_city = sanitize_text_field($_POST['city']);
			
			//sanitize the address
			$sanitized_address = sanitize_text_field($_POST['address']);
			
			//remove other characters from the phone number_format
			$phone = preg_replace("/[^0-9]/", "", $_POST['phone']); //remove all non-numbers
			
			// take a given email address and split it into the username and domain plus sanitize
			$sanitized_email = sanitize_email($_POST['per_email']);
			
			//Save sanitized posts as session variables
			$_SESSION['zip']=$sanitized_zip;
			$_SESSION['state']=$sanitized_state;
			$_SESSION['country']=$sanitized_country;
			$_SESSION['city']=$sanitized_city;
			$_SESSION['address']=$sanitized_address;
			$_SESSION['phone']=$phone;
			$_SESSION['per_email']=$sanitized_email;
			
			if(($sanitized_email=="") || (!(is_email($sanitized_email)))) 
			{ 
				//**retrieve error message and error code stating that this is not a valid mail domain name
				$error_code = 1004;
				header("Location: ".$_SESSION['c_page']."&error_code=".$error_code."");
				exit;
			}
			elseif(($phone=="") || (strlen( $phone ) > 12) || (strlen( $phone ) < 10 ))
			{
				//**retrieve error message and error code stating that this is not a valid phone
				$error_code = 4001;
				print $error_code;
				header("Location: ".$_SESSION['c_page']."&error_code=".$error_code."");
				exit;
			}
			elseif(($sanitized_address=="") || (strlen( $sanitized_address ) > 50))
			{
				//**retrieve error message and error code stating that this is not a valid address
				$error_code = 1007;
				header("Location: ".$_SESSION['c_page']."&error_code=".$error_code."");
				exit;
			}
			elseif(($sanitized_city=="") || (strlen( $sanitized_city ) > 30))
			{ 
				//**retrieve error message and error code stating that this is not a valid city name
				$error_code = 1008;
				header("Location: ".$_SESSION['c_page']."&error_code=".$error_code."");
				exit;
			}
			elseif(($sanitized_state=="") || (strlen( $sanitized_state ) > 3))
			{
				//**retrieve error message and error code stating that this is not a valid state name
				$error_code = 1009;
				header("Location: ".$_SESSION['c_page']."&error_code=".$error_code."");
				exit;
			}
			elseif(($sanitized_country=="")|| (strlen($sanitized_country) > 2))
			{
				//**retrieve error message and error code stating that this is not a valid country name
				$error_code = 1017;
				header("Location: ".$_SESSION['c_page']."&error_code=".$error_code."");
				exit;
			}
			elseif(($sanitized_zip=="") || (strlen($sanitized_zip) > 10))
			{
				//**retrieve error message and error code stating that this is not a valid zip code
				$error_code = 1010;
				header("Location: ".$_SESSION['c_page']."&error_code=".$error_code."");
				exit;
			}
			else
			{ 
				//verify user_id is an integer  
				$safe_user_id = intval($_POST['user_id']);
				
				//sanitize userName
				$sanitized_username = sanitize_user($_POST['username'],0);
				
				if(((strlen($safe_user_id) <=16) && ($safe_user_id))||($sanitized_username<=16))
				{
					if ($_POST['insert_update']!=0)
					{
						$sql2 = $wpdb->query( $wpdb->prepare( "UPDATE ".$table_contact." SET username = '%s', zip='%s', country='%s', state='%s', city='%s',address='%s', phone=%d WHERE user_id=%d", $sanitized_username, $sanitized_zip, $sanitized_country, $sanitized_state, $sanitized_city, $sanitized_address, $phone, $safe_user_id));
					}
					else
					{
						$sql2 = $wpdb->query( $wpdb->prepare( "INSERT INTO ".$table_contact." (username,user_id, zip,state,city,address,phone,country) VALUES ('$sanitized_username','$safe_user_id','$sanitized_zip','$sanitized_state','$sanitized_city','$sanitized_address','$phone', '$sanitized_country')"));	
					}
						
					//update team member information in the database
					$sql2 = $wpdb->query( $wpdb->prepare( "UPDATE ".$wordpress_users." SET user_email ='%s' WHERE ID = %d", $sanitized_email, $safe_user_id));

					unset($_SESSION['zip']);
					unset($_SESSION['country']);
					unset($_SESSION['state']);
					unset($_SESSION['city']);
					unset($_SESSION['address']);
					unset($_SESSION['phone']);
					unset($_SESSION['per_email']);
					//return to main
					header("Location: ".esc_url(B_PRODUCTIV_PORTAL_LINK)."/?page=menu");
					exit;
				}
				else
				{
					require_once(B_PRODUCTIV_PLUGIN_PATH .'templates/cl_access_denied.php');
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
