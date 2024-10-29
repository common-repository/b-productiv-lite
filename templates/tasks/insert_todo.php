<?php
	/*-------------------------------------------------------------------------------
	** File Name: 
	**			insert_pass_change.php
	**
	** File Description:  
	** 			Serves as the data processing tasks file in the 
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
	**			menu.php, cl_access_denied,php 
	**			 
	** Accessible By:
	**			Employees (update, insert, delete), Managers (update, insert, delete)
	**
	** Notes: 
	** 			Copyright (c) C. A. Lettsome Services, LLC. 2018  All rights reserved.
	**			http://www.clydelettsome.com
	*-------------------------------------------------------------------------------*/
 	$escaped_id = esc_html($_GET['id']);
	$escaped_action = esc_html($_GET['action']);
	$retrieved_nonce = $_REQUEST['_wpnonce'];  
	
	if( isset( $_POST['bproductiv_ToDoAdd_nonce'] ) && (wp_verify_nonce( $_POST['bproductiv_ToDoAdd_nonce'], 'bproductiv_ToDoAdd_action' )))
	{
		//sanitize action
		$sanitize_action = sanitize_text_field($_POST['action']);
		
		if($_COOKIE['timer']=="1"&&($sanitize_action=="insert" && ((current_user_can('administrator'))||(current_user_can('employee')))))
		{
			// escape notice because sanitize does not work before 4.7
			$sanitized_notice = esc_textarea($_POST['notice']);
			
			//verify priority is an integer  
			$safe_priority = intval($_POST['priority']);
			
			//sanitize userName
			$sanitized_username = sanitize_user($_POST['username'],0);
			
			$_SESSION['notice2']=$sanitized_notice;
			$_SESSION['priority']=$safe_priority;
			$_SESSION['username22']=$sanitized_username;
	
			if($sanitized_notice=="" )
			{ 
				//**retrieve error message and error code stating please describe the task
				$error_code = 1102;
				header("Location: ".$_SESSION['c_page']."&error_code=".$error_code."");
				exit;
			}
			elseif($sanitized_username=="" || (strlen($sanitized_username) >16))
			{ 
				//**retrieve error message and error code stating to assign task to contractor or employee
				$error_code = 1100;
				header("Location: ".$_SESSION['c_page']."&error_code=".$error_code."");
				exit; 
			}
			elseif($safe_priority==""|| (strlen($safe_priority) >1))
			{
				//**retrieve error message and error code stating to assign priority level to a task
				$error_code = 1101;
				header("Location: ".$_SESSION['c_page']."&error_code=".$error_code."");
				exit; 
			}
			else
			{
				$date = date("Y-m-d");
				$sql2 = $wpdb->query( $wpdb->prepare( "INSERT INTO ".$todo_table." (notice,assigned_to,priority,task_status,datee,poster) VALUES ('$sanitized_notice','$sanitized_username','$safe_priority',0,'$date','$current_username')"));	
				unset($_SESSION['notice2']);
				unset($_SESSION['priority']);
				unset($_SESSION['username22']);
				header("Location: ".esc_url(B_PRODUCTIV_PORTAL_LINK)."/?page=todo_main");
				exit;		
			}
		}
		else
		{
			require_once(B_PRODUCTIV_PLUGIN_PATH .'templates/cl_access_denied.php');
		} 
	}
	elseif (wp_verify_nonce($retrieved_nonce, 'update_todo'.$escaped_id.'' ) )
	{
		if ($_COOKIE['timer']=="1"&&($escaped_action=="update" && ((current_user_can('administrator'))||(current_user_can('employee'))||(current_user_can('contractor')))))
		{	
			$sql2 = $wpdb->query( $wpdb->prepare( "UPDATE ".$todo_table." SET task_status=%d WHERE id='%s'", 1, $escaped_id));
		
			header("Location: ".esc_url(B_PRODUCTIV_PORTAL_LINK)."/?page=todo_main");
			exit;									
		}
		else
		{
			require_once(B_PRODUCTIV_PLUGIN_PATH .'templates/cl_access_denied.php');
		} 
	}
 	elseif( isset( $_POST['bproductiv_todoDelete_nonce'] ) && (wp_verify_nonce( $_POST['bproductiv_todoDelete_nonce'], 'bproductiv_todoDelete_action' )))
	{
		//sanitize action
		$sanitize_action = sanitize_text_field($_POST['action']);
		if ($_COOKIE['timer']=="1"&&(($sanitize_action=="delete") && ((current_user_can('administrator'))||(current_user_can('employee')))))
		{
			$todo = $wpdb->get_results( $wpdb->prepare( "SELECT id FROM ".$todo_table." ORDER BY priority ASC"));
			foreach( $todo AS $todoArray )
			{	
				$escaped_id = esc_html($todoArray->id);
				
				if ($_POST["".$escaped_id.""]== TRUE)
				{	
					$sql2 = $wpdb->get_results( $wpdb->prepare( "DELETE from ".$todo_table." WHERE id=%d", $escaped_id));	
				}
			}
			header("Location: ".esc_url(B_PRODUCTIV_PORTAL_LINK)."/?page=todo_main");
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
