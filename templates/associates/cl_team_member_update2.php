<?php
	/*-------------------------------------------------------------------------------
	** File Name: 
	**			cl_team_member_update2.php
	**
	** File Description:  
	** 			Serves as the body for the team member update changing page in the 
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
	**			insert_team_member_info.php, cl_access_denied.php
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
		$result = $wpdb->get_results( $wpdb->prepare( "SELECT ID, user_email, display_name FROM ".$wordpress_users." WHERE user_login='%s'", esc_html($current_username)));

		foreach($result as $newArray)
		{
			$escaped_id = esc_html($newArray->ID);
			if ($_SESSION['per_email']=="")
			{
				$email = esc_html($newArray->user_email);
			}
			else
			{
				$email = esc_html($_SESSION['per_email']);
			}
			$display_name = esc_html($newArray->display_name);
		}
		
		//create and issue the query
		$result = $wpdb->get_results( $wpdb->prepare( "SELECT phone, address, city, country, state, zip, username FROM ".$table_contact." WHERE user_id=%d", $escaped_id));
		
		foreach($result as $newArray)
		{
		}
		if ($_SESSION['phone']=="")
		{
			if(ISSET($newArray->phone))
			{
				$phone = esc_html($newArray->phone);
			}
			else
			{
				$phone = "";
			}
		}
		else
		{
			$phone = $_SESSION['phone'];
		}
		
		if ($_SESSION['address']=="")
		{
			if(ISSET($newArray->address))
			{
				$address = esc_html($newArray->address);
			}
			else
			{
				$address = "";
			}
		}
		else
		{
			$address = $_SESSION['address'];
		}
		
		if ($_SESSION['city']=="")
		{
			if(ISSET($newArray->city))
			{
				$city = esc_html($newArray->city);
			}
			else
			{
				$city = "";
			}
		}
		else
		{
			$city = $_SESSION['city'];
		}
		
		if ($_SESSION['country']=="")
		{
			if(ISSET($newArray->country))
			{
				$country = esc_html($newArray->country);
			}
			else
			{
				$country = "";
			}
		}
		else
		{
			$country = $_SESSION['country'];
		}

		if ($_SESSION['state']=="")
		{
			if(ISSET($newArray->state))
			{
				$state = esc_html($newArray->state);
			}
			else
			{
				$state = "";
			}
		}
		else
		{
			$state = $_SESSION['state'];
		}
			
		if ($_SESSION['zip']=="")
		{
			if(ISSET($newArray->zip))
			{
				$zip = esc_html($newArray->zip);
			}
			else
			{
				$zip = "";
			}
		}
		else
		{
			$zip = $_SESSION['zip'];
		}
		
		require_once(''.B_PRODUCTIV_PLUGIN_PATH.'templates/cl_dropdown_menus.php'); //needs to be here
		$escaped_username = esc_html($current_username);
		unset($_SESSION[$country]);
		unset($_SESSION[$state]);
		
		$display_block = "<font size=\"4\"><h1>Team Member Personal Information</h1></font>";
		include(B_PRODUCTIV_PLUGIN_PATH.'templates/cl_print_error.php');
		
		$display_block.="
		<table id=\"bproductivt50\">
			<form method=\"post\" action=\"".esc_url(B_PRODUCTIV_PORTAL_LINK)."/?page=team_member_update\">
			<input type=\"hidden\" name=\"username\" value= \"$escaped_username\"> 
			<input type=\"hidden\" name=\"user_id\" value= $escaped_id>
			<input type=\"hidden\" name=\"update_my_information\" value= \"1\">
			<input type=\"hidden\" name=\"action\" value=\"update\">
			".wp_nonce_field('bproductiv_team_action', 'bproductiv_team_nonce' )."
			<tr>
				<td class=\"team_mem_column1\">Name</td>
				<td >$display_name</td>
			</tr>
			<tr>
				<td class=\"team_mem_column1\">Personal Email Address<span class=\"required_fields\">*</span></td>
				<td ><input type=\"text\" maxlength=\"30\" size=\"30\" autocomplete=\"off\" autocapitalize=\"off\" name=\"per_email\" value=\"$email\"></td>
			</tr>
			<tr>
				<td class=\"team_mem_column1\">Personal Phone #<span class=\"required_fields\">*</span></td>
				<td ><input type=\"text\" maxlength=\"12\" size=\"12\" autocomplete=\"off\" autocapitalize=\"off\" name=\"phone\" value=$phone> ##########</td>
			</tr>
			<tr>
				<td class=\"team_mem_column1\">Home Street Address<span class=\"required_fields\">*</span></td>
				<td ><input type=\"text\" maxlength=\"50\" size=\"40\" autocomplete=\"off\" autocapitalize=\"off\" name=\"address\" value=\"$address\" ></td>
			</tr>
			<tr>
				<td class=\"team_mem_column1\">Home Country<span class=\"required_fields\">*</span></td>
				<td >";
				$portal_templates_path = "".esc_url(B_PRODUCTIV_PORTAL_LINK)."/?page=team_member_update";
				$display_block.="<select name=\"country\" onChange=\"location.href='".esc_js($portal_templates_path)."&country='+this.value+'&phone='+phone.value+'&per_email='+per_email.value+'&address='+address.value+'&city='+city.value+'&zip='+zip.value+''\">";
				$x=0;	
				$display_block.="\t<option value=";
				$display_block.=">\n";
				$x++;
				$max = sizeof($country_v);

				while($x<$max)
				{
					$display_block.="\t<option value=\"".$country_v[$x]."\"";
					if($country_v[$x] == $country)
					{
						$display_block.="SELECTED";
					}
					$display_block.=">".$country_n[$x]."</option>";
					$x++;
				}
				$display_block.="</select></td>
			</tr>
			<tr>
				<td class=\"team_mem_column1\">Home City<span class=\"required_fields\">*</span> </td>
				<td ><input type=\"text\" maxlength=\"30\" size=\"30\" autocomplete=\"off\" autocapitalize=\"off\" name=\"city\" value=\"$city\"></td>
			</tr>
			<tr>
				<td class=\"team_mem_column1\">Home State/Province<span class=\"required_fields\">*</span></td>
				<td >";
					$display_block.="<select name=\"state\" >";
					$x=0;	
					$display_block.="\t<option value=";
					$display_block.=">\n";
					$x++;
					$max = sizeof($state_v);

					while($x<$max)
					{
						$display_block.="\t<option value=\"".$state_v[$x]."\"";
						if($state_v[$x] == $state)
						{
							$display_block.="SELECTED";
						}
						$display_block.=">".$state_n[$x]."</option>";
						$x++;
					}
					$display_block.="</select>
				</td>
			</tr>
			<tr>
				<td class=\"team_mem_column1\">Home Postal Code<span class=\"required_fields\">*</span></td>
				<td ><input type=\"text\" maxlength=\"10\" size=\"10\" autocomplete=\"off\" autocapitalize=\"off\" name=\"zip\" value='$zip'></td>
			</tr>
		</table>
		<p><span class=\"required_fields\">*
		Indicates fields that must be filled</span></p>
		<br><input type=\"Submit\" name=\"submit\" value=\"Submit\" \>
		<input type=\"reset\" name=\"Submit2\" value=\"Reset\" \></form>
		<br><br>";
		
		require_once(B_PRODUCTIV_PLUGIN_PATH.'templates/cl_footer_nav.php');
	}
	else
	{
		require_once(B_PRODUCTIV_PLUGIN_PATH .'templates/cl_access_denied.php');
	}
?>
