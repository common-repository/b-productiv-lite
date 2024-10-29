<?php
	/*-------------------------------------------------------------------------------
	** File Name: 
	**			cl_contractor.php
	**
	** File Description:  
	** 			Serves as the body of the contractor dashboard in the B-Productiv Plugin
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
	** File Calls/Submits: 
	**			N/A
	** 
	** Notes: 
	** 			Copyright (c) C. A. Lettsome Services, LLC. 2018  All rights reserved.
	**			http://www.clydelettsome.com
	*-------------------------------------------------------------------------------*/
	if ($_COOKIE['timer']=="1"&&(current_user_can('contractor')))
	{
		$display_block ="
		<p><h1>Dashboard </h1></p>
		<br><strong>Display Name:</strong> ".esc_html($current_user_display_name)."<br>
		";
		$escaped_current_username = esc_html($current_username);
		$display_block .="<hr> </hr><TABLE id=\"menu_outer\">
			<tr>
				<td class=\"left_side_menu\">";
					/*Empty or future purposes*/
				$display_block .="</td>
				<td>	
					<center><h3><img src=\"".esc_url(B_PRODUCTIV_PLUGIN_PATH)."icons/todo.png\" alt=\"to do\" title=\"To Do\" height=\"55\" width=\"55\" style=\"border: 0;\"> To Do's</h3></center>
				</td>
			<tr>
			<tr>
				<td class=\"left_side_menu\" >";
					/*Empty or future purposes*/
				$display_block .="</td>
				<td><br><br><br>";
					
					$todo = $wpdb->get_results( $wpdb->prepare( "SELECT id, notice, assigned_to, poster, task_status, priority FROM ".$todo_table." WHERE assigned_to='%s' OR poster = '%s' ORDER BY priority ASC", $escaped_current_username, $escaped_current_username));
					
					$high=0;
					$normal=0;
					$low=0;
					foreach( $todo AS $todoArray ) 
					{
						$escaped_priority1 = esc_html($todoArray->priority);
						$escaped_task_status = esc_html($todoArray->task_status);
						if (($escaped_priority1==1)&&($escaped_task_status==0))
						{
							$high++;
						}
						elseif(($escaped_priority1==2)&&($escaped_task_status==0))
						{
							$normal++;
						}
						elseif(($escaped_priority1==3)&&($escaped_task_status==0))
						{
							$low++;
						}
					}
					$display_block .="<center>";
					if($high>0)
					{
						$display_block .="<img src=\"".esc_url(B_PRODUCTIV_PLUGIN_PATH)."icons/high_hot.png\" alt=\"High Priority\" height=\"20\" width=\"20\" style=\"border: 0;\"> You have ".$high." high priority task(s) listed.<br><br>";
					}
					if($normal>0)
					{
						$display_block .="<img src=\"".esc_url(B_PRODUCTIV_PLUGIN_PATH)."icons/normal_alert.png\" alt=\"Normal Priority\" height=\"20\" width=\"20\" style=\"border: 0;\"> You have ".$normal." normal priority task(s) listed.<br><br>";
					}
					if($low>0)
					{
						$display_block .="<img src=\"".esc_url(B_PRODUCTIV_PLUGIN_PATH)."icons/normal_info.png\" alt=\"Low Priority\" height=\"20\" width=\"20\" style=\"border: 0;\"> You have ".$low." low priority task(s) listed.<br><br>";
					}
					$display_block .="</font><p><a href=\"".esc_url(B_PRODUCTIV_PORTAL_LINK)."/?page=todo_main\" >See To Do List</a></p></center>";
				$display_block .= "</td>
			</tr>
		</table>
		<br><br>";
		$display_block .="<h3>My Information</h3>
		<table id=\"menu_outer\">
		<tr>

		<td class=\"bottom_menu\"><center><a href=\"".esc_url(B_PRODUCTIV_PORTAL_LINK)."/?page=pass_change\"><img src=\"".esc_url(B_PRODUCTIV_PLUGIN_PATH)."icons/password_key.png\" alt=\"Change My Name And Password\" title=\"Change My Password\" style=\"border: 0;\" height=\"50\" width=\"50\"></a></center></td>
		<td class=\"bottom_menu\"><center><a href=\"".esc_url(B_PRODUCTIV_PORTAL_LINK)."/?page=team_member_update\"><img src=\"".esc_url(B_PRODUCTIV_PLUGIN_PATH)."icons/contact_mail.png\" alt=\"Update My Personal Information\" title=\"Update My Personal Information\" style=\"border: 0;\" height=\"52\" width=\"64\"></a></center></td>
		<td class=\"bottom_menu\"><center></center></td>
		</tr>
		<tr>
		<td class=\"bottom_menu\"><center>Change My Name And Password</center></td>
		<td class=\"bottom_menu\"><center>Update My Personal Information</center></td>
		<td class=\"bottom_menu\"><center></center></td>
		</tr>
		</table>
		<br><br>
		";
		require_once(B_PRODUCTIV_PLUGIN_PATH.'templates/cl_footer_nav.php');
	}
	 else 
	{
		require_once(B_PRODUCTIV_PLUGIN_PATH .'templates/cl_access_denied.php');
	}
?>


