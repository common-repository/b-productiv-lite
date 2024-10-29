<?php
	/*-------------------------------------------------------------------------------
	** File Name: 
	**			cl_pass_change2.php
	**
	** File Description:  
	** 			Serves as the body for the password and name changing page in the 
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
	** 			Presentation Layer
	**
	** File Calls//Submits: 
	**			insert_pass_change.php, cl_access_denied.php
	**			 
	** Accessible By:
	**			Managers, Contractors, Employees
	**
	** Notes: 
	** 			Copyright (c) C. A. Lettsome Services, LLC. 2018  All rights reserved.
	**			http://www.clydelettsome.com
	*-------------------------------------------------------------------------------*/
	if ($_COOKIE['timer']=="1"&&((current_user_can('employee'))||(current_user_can('administrator'))||(current_user_can('contractor'))))
	{
		//create and issue the query
		$first_name = "first_name";
		$last_name = "last_name";
		
		$validate_current_user_id = intval($current_user_id);
		
		$result = $wpdb->get_results( $wpdb->prepare( "SELECT meta_value FROM ".$wordpress_usermeta." WHERE user_id=%d AND meta_key = '%s'", $validate_current_user_id, $first_name));

		foreach($result as $newArray)
		{
			$f_name = esc_html($newArray->meta_value);
		}			

		//create and issue the query
		$result = $wpdb->get_results( $wpdb->prepare( "SELECT meta_value FROM ".$wordpress_usermeta." WHERE user_id=%d AND meta_key = '%s'", $validate_current_user_id, $last_name));

		foreach($result as $newArray)
		{
			$l_name = esc_html($newArray->meta_value);
		}
		$display_block ="
		<h1>Change My Name And/Or Password</h1>";
		include(B_PRODUCTIV_PLUGIN_PATH ."templates/cl_print_error.php");
		$display_block.="<table id=\"innertable\">
		<form action=\"".esc_url(B_PRODUCTIV_PORTAL_LINK)."/?page=pass_change\" method=\"POST\">
		".wp_nonce_field('bproductiv_pass_action', 'bproductiv_pass_nonce' )."
		<input type=\"hidden\" name=\"action\" value=\"update\">
			<tr>
				<td class=\"team_mem_column1\">
					First Name <span class=\"required_fields\">*</span>
				</td>
				<td>  
					<input type=\"text\" maxlength=\"20\" size=\"30\" autocomplete=\"off\" autocapitalize=\"off\" name=\"f_name\" value=\"$f_name\">
				</td>
			</tr>
			<tr>
				<td class=\"team_mem_column1\">
					Last Name <span class=\"required_fields\">*</span>
				</td>
				<td>  
					<input type=\"text\" maxlength=\"25\" size=\"30\" autocomplete=\"off\" autocapitalize=\"off\" name=\"l_name\" value=\"$l_name\">
				</td class=\"team_mem_column1\">
			</tr>
			<tr>
				<td class=\"team_mem_column1\">
					Enter Your Current Password <span class=\"required_fields\">*</span>
				</td>
				<td>  
					<input type=\"password\" maxlength=\"60\" size=\"30\" autocomplete=\"off\" autocapitalize=\"off\" name=\"current_pass\">
				</td>
			</tr>
			<tr>
				<td class=\"team_mem_column1\">
					Enter Your New Password <span class=\"required_fields\">**</span>
				</td>
				<td>  
					<input type=\"password\" maxlength=\"60\" size=\"30\" autocomplete=\"off\" autocapitalize=\"off\" name=\"new_pass1\"><br>
					(Leave this blank if you do not want to make any changes to your password.)
				</td>
			</tr>
			<tr>
				<td class=\"team_mem_column1\">
					Re-enter Your New Password <span class=\"required_fields\">**</span>
				</td>
				<td>  
					<input type=\"password\" maxlength=\"60\" size=\"30\" autocomplete=\"off\" autocapitalize=\"off\" name=\"new_pass2\"><br>
					(Leave this blank if you do not want to make any changes to your password.)
				</td>
			</tr>
		</table>
		<span class=\"required_fields\">*
		Indicates fields that must be filled</span>
		<br><span class=\"required_fields\">**
		If you change your password, you will need to log back into the system.</span><br>
		<input type=\"submit\" name=\"submit\" value=\"Submit\">
		<input type=\"reset\" name=\"Submit2\" value=\"Reset\">
		</form>	
		<br><br>
		";
		require_once(''.B_PRODUCTIV_PLUGIN_PATH.'templates/cl_footer_nav.php');
	}
	else
	{
		require_once(B_PRODUCTIV_PLUGIN_PATH .'templates/cl_access_denied.php');
	}
?>