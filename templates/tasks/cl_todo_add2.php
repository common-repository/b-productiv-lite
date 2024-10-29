<?php
	/*-------------------------------------------------------------------------------
	** File Name: 
	**			cl_todo_add2.php
	**
	** File Description:  
	** 			Serves as the body for the tasks adding page in the 
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
	**			insert_todo.php, cl_access_denied.php
	**			 
	** Accessible By:
	**			Managers (Adding, Updating), Employees (Updating)
	**
	** Notes: 
	** 			Copyright (c) C. A. Lettsome Services, LLC. 2018  All rights reserved.
	**			http://www.clydelettsome.com
	*-------------------------------------------------------------------------------*/
	if ($_COOKIE['timer']=="1"&&((current_user_can('administrator'))||(current_user_can('employee'))))//admins and employees only
	{
		//create and issue the query
		$capabilities = $wpdb->prefix . "capabilities";	
		$worker_result = $wpdb->get_results( $wpdb->prepare( "SELECT user_id, meta_value FROM ".$wordpress_usermeta." WHERE meta_key = '%s' ", $capabilities));
		
		$display_block ="
		 <h1>Add To Do's</h1>";
		include(B_PRODUCTIV_PLUGIN_PATH.'templates/cl_print_error.php');
		$display_block.="<table id=\"bproductivt\">
			<form method=\"post\" action=\"".esc_url(B_PRODUCTIV_PORTAL_LINK)."/?page=todo_add\">
			".wp_nonce_field('bproductiv_ToDoAdd_action', 'bproductiv_ToDoAdd_nonce' )."
			<input type=\"hidden\" name=\"action\" value=\"insert\">	
			<tr>
				<td class=\"todo_add_column1\">Task Description<span class=\"required_fields\">*</span> </td>
				<td >
				<textarea name=\"notice\" cols=\"40\" rows=\"5\">".$_SESSION['notice2']."</textarea>
				</td>
			</tr>
			<tr >
				<td class=\"todo_add_column1\">Assign To<span class=\"required_fields\">*</span></td>
				<td >";
				$display_block.="<select name=\"username\">";
				$x=0;	
				$display_block.="\t<option value=";
				$display_block.=">\n";
				$x++;
				
				foreach($worker_result as $WorkerArray)
				{
					$escaped_user_id = esc_html($WorkerArray->user_id);
					$escaped_meta_value = esc_html($WorkerArray->meta_value);
					
					if((strpos($escaped_meta_value, "administrator"))||(strpos($escaped_meta_value, "contractor"))||(strpos($escaped_meta_value, "employee")))
					{
						$name_result = $wpdb->get_results( $wpdb->prepare( "SELECT display_name, user_login FROM ".$wordpress_users." WHERE ID = %d ", $escaped_user_id));
						foreach($name_result as $nameArray)
						{
							$escaped_display_name = esc_html($nameArray->display_name);
							$escaped_username = esc_html($nameArray->user_login);
						}
						
						$display_block.="\t<option value=\"".$escaped_username."\"";
						if($escaped_username == $_SESSION['username22'])
						{
							$display_block.="SELECTED";
						}
						$display_block.=">".$escaped_display_name." \n";
						$x++;
					}

				}
				$display_block.="</select>";
				$display_block.="</td>
				
			</tr>
			<tr >
				<td class=\"todo_add_column1\">Priority Level<span class=\"required_fields\">*</span></td>
				<td >
					<input type=\"radio\" name=\"priority\" value=1"; if($_SESSION['priority']==1){$display_block.="checked=\"checked\"";}  $display_block.="><label>High</label><br>
					<input type=\"radio\" name=\"priority\" value=2"; if($_SESSION['priority']==2){$display_block.="checked=\"checked\"";}  $display_block.="><label>Normal</label><br>
					<input type=\"radio\" name=\"priority\" value=3"; if($_SESSION['priority']==3){$display_block.="checked=\"checked\"";}  $display_block.="><label>Low</label>
				</td>
			</tr>
		</table>
		<span class=\"required_fields\">* Indicates fields that must be filled</span><br>
		<input type=\"submit\" name=\"submit\" value=\"Submit\" >
		<input type=\"reset\" name=\"Submit2\" value=\"Reset\" >

		</form> ";
		unset($_SESSION['notice2']);
		unset($_SESSION['priority']);
		unset($_SESSION['username22']);
		$display_block.="
		</font>";
		require_once(B_PRODUCTIV_PLUGIN_PATH.'templates/cl_footer_nav.php');
	}
	else
	{
		require_once(B_PRODUCTIV_PLUGIN_PATH .'templates/cl_access_denied.php');
	} 
?>