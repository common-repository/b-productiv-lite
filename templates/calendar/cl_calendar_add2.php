<?php
	/*-------------------------------------------------------------------------------
	** File Name: 
	**			cl_calendar_add2.php
	**
	** File Description:  
	** 			Serves as the body for the reoccurring alerts adding page in the 
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
	**			insert_calendar.php, cl_access_denied.php
	**			 
	** Accessible By:
	**			Managers (Adding, Updating), Employees (Updating)
	**
	** Notes: 
	** 			Copyright (c) C. A. Lettsome Services, LLC. 2018  All rights reserved.
	**			http://www.clydelettsome.com
	*-------------------------------------------------------------------------------*/
	if ($_COOKIE['timer']=="1"&&(current_user_can('administrator')))
	{
		//create and issue the query
		$capabilities = $wpdb->prefix . "capabilities";
		$worker_result = $wpdb->get_results( $wpdb->prepare( "SELECT user_id, meta_value FROM ".$wordpress_usermeta." WHERE meta_key = '%s' ",$capabilities));
		
		$display_block="
		<h1>Add An Alert</h1>";
		include(B_PRODUCTIV_PLUGIN_PATH.'templates/cl_print_error.php');
		
		$display_block.="<table id=\"bproductivt50\">
			<form method=\"post\" action=\"".esc_url(B_PRODUCTIV_PORTAL_LINK)."/?page=calendar_add\">
			".wp_nonce_field('bproductiv_calendarAdd_action', 'bproductiv_calendarAdd_nonce' )."
			<input type=\"hidden\" name=\"action\" value=\"insert\">	
			<tr>
				<td class=\"calendar_add_column1\">Notice<span class=\"required_fields\">*</span> </td>
				<td>
				<textarea name=\"notice\" cols=\"40\" rows=\"5\" autocomplete=\"off\" autocapitalize=\"off\">".$_SESSION['notice']."</textarea>
				</td>
			</tr>
			<tr>
				<td class=\"calendar_add_column1\">Assign To<span class=\"required_fields\">*</span></td>
				<td>";
				$display_block.="<select name=\"username1\">";
				$x=0;	
				$display_block.="\t<option value=";
				$display_block.=">\n";
				$x++;

				foreach($worker_result as $WorkerArray)
				{
					$escaped_wuser_id = esc_html($WorkerArray->user_id); 
					$escaped_meta_value = esc_html($WorkerArray->meta_value);
					if((strpos($escaped_meta_value, "administrator"))||(strpos($escaped_meta_value, "employee")))
					{ 
						$name_result = $wpdb->get_results( $wpdb->prepare( "SELECT display_name, user_login FROM ".$wordpress_users." WHERE ID = %d ",$escaped_wuser_id));
						foreach($name_result as $nameArray)
						{
							$escaped_wdisplay_name = esc_html($nameArray->display_name);
							$escaped_wusername = esc_html($nameArray->user_login);
						}
						$display_block.="\t<option value=\"".$escaped_wusername."\"";
						if($escaped_wusername == $_SESSION['username22'])
						{
							$display_block.="SELECTED";
						}
						$display_block.=">".$escaped_wdisplay_name." \n";
						$x++; 
					}
					
				}  
				$display_block.="</select>";
				$display_block.="</td>
				
			</tr>
			<tr>
				<td class=\"calendar_add_column1\">Start Date<span class=\"required_fields\">*</span> </td>
				<td><input type=\"text\" maxlength=\"10\" size=\"10\" name=\"next_date\" value=\"".$_SESSION['next_date']."\" autocomplete=\"off\" autocapitalize=\"off\"> YYYY-MM-DD</td> 
			</tr>
			<tr>
				<td class=\"calendar_add_column1\">Repeat<span class=\"required_fields\">*</span></td>
				<td>
					<input type=\"radio\" name=\"repeat\" value=0 ";if($_SESSION['repeat']==0){$display_block.="checked";}$display_block.="><label>No</label><br>
					<input type=\"radio\" name=\"repeat\" value=1 ";if($_SESSION['repeat']==1){$display_block.="checked";}$display_block.="><label>Yes</label>
				</td>
			</tr>
			<tr>
				<td class=\"calendar_add_column1\">Frequency<span class=\"required_fields\">*</span></td>
				<td>
				<table id=\"innertable\">
					<tr>
						<td><input type=\"radio\" name=\"frequency\" value=1 ";if($_SESSION['frequency']==1){$display_block.="checked";}$display_block.=">
						<label>Once a Year</label></td>
						<td><input type=\"radio\" name=\"frequency\" value=2 ";if($_SESSION['frequency']==2){$display_block.="checked";}$display_block.=">
						<label>Twice a Year</label></td>
					</tr>
					<tr>
						<td><input type=\"radio\" name=\"frequency\" value=3 ";if($_SESSION['frequency']==3){$display_block.="checked";}$display_block.=">
						<label>Three times a Year</label></td>
						<td><input type=\"radio\" name=\"frequency\" value=4 ";if($_SESSION['frequency']==4){$display_block.="checked";}$display_block.=">
						<label>Quarterly</label></td>
					</tr>
					<tr>
						<td><input type=\"radio\" name=\"frequency\" value=6 ";if($_SESSION['frequency']==6){$display_block.="checked";}$display_block.=">
						<label>Bi-Monthly</label></td>
						<td><input type=\"radio\" name=\"frequency\" value=12 ";if($_SESSION['frequency']==12){$display_block.="checked";}$display_block.=">
						<label>Monthly</label></td>
					</tr>
					<tr>
						<td><input type=\"radio\" name=\"frequency\" value=26 ";if($_SESSION['frequency']==26){$display_block.="checked";}$display_block.=">
						<label>BiWeekly</label></td>
						<td><input type=\"radio\" name=\"frequency\" value=52 ";if($_SESSION['frequency']==52){$display_block.="checked";}$display_block.=">
						<label>Weekly</label></td>
					</tr>
					<tr>
						<td><input type=\"radio\" name=\"frequency\" value=365 ";if($_SESSION['frequency']==365){$display_block.="checked";}$display_block.=">
						<label>Daily</label></td>
						<td><input type=\"radio\" name=\"frequency\" value=0 ";if($_SESSION['frequency']==0){$display_block.="checked";}$display_block.=">
						<label>None</label></td>
					</tr>
			  </table>
				</td>
			</tr>
		</table>
	 
		<p><span class=\"required_fields\">*
		Indicates fields that must be filled</span></p>
		 <input type=\"submit\" name=\"submit\" value=\"Submit\" >
		<input type=\"reset\" name=\"Submit2\" value=\"Reset\" >
		</form><br><br>";
		require_once(B_PRODUCTIV_PLUGIN_PATH.'templates/cl_footer_nav.php');	
		
	}
	else
	{
		require_once(B_PRODUCTIV_PLUGIN_PATH .'templates/cl_access_denied.php');
	}
?>