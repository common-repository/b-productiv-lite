<?php
	/*-------------------------------------------------------------------------------
	** File Name: 
	**			insert_pass_change.php
	**
	** File Description:  
	** 			Serves as the data processing reoccurring alerts file in the 
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
	**			menu.php, cl_access_denied.php 
	**			 
	** Accessible By:
	**			Employees (update), Managers (update, insert, delete)
	**
	** Notes: 
	** 			Copyright (c) C. A. Lettsome Services, LLC. 2018  All rights reserved.
	**			http://www.clydelettsome.com
	*-------------------------------------------------------------------------------*/
	$escaped_id = esc_html($_GET['id']);
	$escaped_action = esc_html($_GET['action']);
	$retrieved_nonce = $_REQUEST['_wpnonce'];
	
	//verify id is an integer  
	$safe_id = intval($_POST['id']);
	
	if( isset( $_POST['bproductiv_calendarUpdate_nonce'.$safe_id.''] ) && (wp_verify_nonce( $_POST['bproductiv_calendarUpdate_nonce'.$safe_id.''], 'bproductiv_calendarUpdate_action'.$safe_id.'' )))
	{
		if ($_COOKIE['timer']=="1"&&($_POST['action']=="update" && ((current_user_can('administrator'))||(current_user_can('employee')))))
		{	
			//sanitize next_date. *****Leave numbers and dashes*********
			$sanitized_next_date = preg_replace("([^0-9-])", "", $_POST['next_date']);
			
			//verify repeat is an integer  
			$safe_repeat = intval($_POST['repeat']);
			
			//verify task_status is an integer  
			$safe_task_status = intval($_POST['task_status']);
		
			//verify frequency is an integer  
			$safe_frequency = intval($_POST['frequency']);

			if (($safe_id) &&($safe_repeat==1) && ($safe_task_status==0)) //repeat and not previously complete
			{
				$date = date_create($sanitized_next_date);
				if(($safe_frequency==1))
				{
					//yearly
					date_modify($date, '+1 year');
					$next_date=date_format($date, 'Y-m-d');
				}
				elseif(($safe_frequency==2))
				{
					//twice a year
					date_modify($date, '+6 months');
					$next_date=date_format($date, 'Y-m-d');
				}
				elseif(($safe_frequency==3))
				{
					//three times a year
					date_modify($date, '+4 months');
					$next_date=date_format($date, 'Y-m-d');
				}
				elseif(($safe_frequency==4))
				{
					//quarterly
					date_modify($date, '+3 months');
					$next_date=date_format($date, 'Y-m-d');
				}
				elseif(($safe_frequency==6))
				{
					//quarterly
					date_modify($date, '+2 months');
					$next_date=date_format($date, 'Y-m-d');
				}
				elseif(($safe_frequency==12))
				{
					//monthly
					date_modify($date, '+1 months');
					$next_date=date_format($date, 'Y-m-d');
				}
				elseif(($safe_frequency==26))
				{
					//biweekly
					date_modify($date, '+2 weeks');
					$next_date=date_format($date, 'Y-m-d');
				}
				elseif(($safe_frequency==52))
				{
					//weekly 
					date_modify($date, '+1 weeks');
					$next_date=date_format($date, 'Y-m-d');
					
				}
				elseif(($safe_frequency==365))
				{
					//daily 
					date_modify($date, '+1 days');
					$next_date=date_format($date, 'Y-m-d');	
				}
				else
				{
					//never
					$next_date=$date;
				}
				 
				$sql2 = $wpdb->query( $wpdb->prepare( "UPDATE ".$calendar_table." SET next_date='%s' WHERE id=%d", $next_date, $safe_id));

				header("Location: ".esc_url(B_PRODUCTIV_PORTAL_LINK)."/?page=menu");
				exit; 									
			}
			elseif(($safe_id) && ($safe_repeat==0) && ($safe_task_status==0)) //do not repeat and task was not originally complete
			{
				$sql2 = $wpdb->query( $wpdb->prepare( "UPDATE ".$calendar_table." SET task_status=%d WHERE id=%d", 1, $safe_id));
		
				header("Location: ".esc_url(B_PRODUCTIV_PORTAL_LINK)."/?page=menu");
				exit;									
			}
			else
			{
				header("Location: ".esc_url(B_PRODUCTIV_PORTAL_LINK)."/?page=menu");
				exit;
			} 
		}
		else
		{
			require_once(B_PRODUCTIV_PLUGIN_PATH .'templates/cl_access_denied.php');
		}
	}
	elseif( isset( $_POST['bproductiv_calendarAdd_nonce'] ) && (wp_verify_nonce( $_POST['bproductiv_calendarAdd_nonce'], 'bproductiv_calendarAdd_action' )))
	{
		if ($_COOKIE['timer']=="1"&&($_POST['action']=="insert" && (current_user_can('administrator'))))
		{
			 //verify task_status is an integer  
			$safe_frequency = intval($_POST['frequency']);
			
			//verify repeat is an integer  
			$safe_repeat = intval($_POST['repeat']);
			
			//verify task_status is an integer  
			$safe_task_status = intval($_POST['task_status']);
			
			//sanitize userNamel
			$sanitized_usernamel = sanitize_user($_POST['username1'],0);
			
			//sanitize next_date. *****Leave numbers and dashes*********
			$sanitized_next_date = preg_replace("([^0-9-])", "", $_POST['next_date']);
			
			//separate into month day year
			$test_date  = explode('-', $sanitized_next_date);
			
			//sanitize(escaped because sanitize started in version 4.7) notice 
			$sanitized_notice = esc_textarea($_POST['notice']);  
			
			//escaped current user name
			$escaped_current_username = esc_html($current_username);		
			
			$_SESSION['frequency']=$safe_frequency;
			$_SESSION['repeat']=$safe_repeat;
			$_SESSION['task_status']=$safe_task_status;
			$_SESSION['username1']=$sanitized_usernamel;
			$_SESSION['next_date']=$sanitized_next_date;
			$_SESSION['notice']=$sanitized_notice;
			$_SESSION['username22']=$sanitized_usernamel;
			
			if($sanitized_notice=="") 
			{
				//**retrieve error message and error code stating that this is not a valid notice
				$error_code = 1103;
				header("Location: ".$_SESSION['c_page']."&error_code=".$error_code."");
				exit; 
			}
			elseif(($sanitized_usernamel=="") || (strlen($sanitized_usernamel) >16))
			{
				//**retrieve error message and error code stating that this is not a valid username
				$error_code = 1104;
				header("Location: ".$_SESSION['c_page']."&error_code=".$error_code."");
				exit; 
			}
			elseif(($sanitized_next_date=="") || (strlen($sanitized_next_date) >10) || (strlen($sanitized_next_date) <10))
			{
				//**retrieve error message and error code stating that this is not a valid date
				$error_code = 4002;
				header("Location: ".$_SESSION['c_page']."&error_code=".$error_code."");
				exit;  
			}
			elseif ((!(preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/",$sanitized_next_date)))||(count($test_date) != 3)||(!(checkdate($test_date[1], $test_date[2], $test_date[0] ))))
			{
				//**retrieve error message and error code stating that this is not a valid date
				$error_code = 4002;
				header("Location: ".$_SESSION['c_page']."&error_code=".$error_code."");
				exit;
			}
			elseif(($safe_repeat=="" && $safe_repeat!=0) || (strlen($safe_repeat) >1))
			{
				//**retrieve error message and error code stating that this is not a valid repeat option
				$error_code = 1106;
				header("Location: ".$_SESSION['c_page']."&error_code=".$error_code."");
				exit;  
			}
			elseif(($safe_frequency=="" && $safe_frequency!=0) || (strlen($safe_frequency) >3))
			{
				//**retrieve error message and error code stating that frequency is not known.
				$error_code = 1109; 
				header("Location: ".$_SESSION['c_page']."&error_code=".$error_code."");
				exit;  
			}
			elseif(($safe_repeat==1 && $safe_frequency==0)||($safe_repeat==0 && $safe_frequency!=0))
			{
				//**retrieve error message and error code stating that there is a repeat frequency miss-match
				$error_code = 1108;
				header("Location: ".$_SESSION['c_page']."&error_code=".$error_code."");
				exit;
			}
			else
			{
				$sql2 = $wpdb->query( $wpdb->prepare( "INSERT INTO ".$calendar_table." (notice,next_date,task_repeat,assigned_to, poster, task_status,task_frequency) VALUES 
				('$sanitized_notice','$sanitized_next_date','$safe_repeat','$sanitized_usernamel','$escaped_current_username','0','$safe_frequency')"));
				unset($_SESSION['frequency']);
				unset($_SESSION['repeat']);
				unset($_SESSION['task_status']);
				unset($_SESSION['username1']);
				unset($_SESSION['next_date']);
				unset($_SESSION['notice']);
				unset($_SESSION['username22']);

				header("Location: ".esc_url(B_PRODUCTIV_PORTAL_LINK)."/?page=menu");
				exit;
			} 		
		}
		else
		{
			require_once(B_PRODUCTIV_PLUGIN_PATH .'templates/cl_access_denied.php');
		}
	}
	elseif (wp_verify_nonce($retrieved_nonce, 'delete_calendar2'.$escaped_id.'' ) )
	{
		if ($_COOKIE['timer']=="1"&&(($escaped_action=="delete") && (current_user_can('administrator'))))
		{
			$sql2 = $wpdb->get_results( $wpdb->prepare( "DELETE from ".$calendar_table." WHERE id=%d", $escaped_id));
				
			header("Location: ".esc_url(B_PRODUCTIV_PORTAL_LINK)."/?page=menu");
			exit;	
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
