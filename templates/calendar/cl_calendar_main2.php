<?php
	/*-------------------------------------------------------------------------------
	** File Name: 
	**			cl_calendar_main2.php
	**
	** File Description:  
	** 			Serves as the body for the reoccurring alerts display page in the 
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
	**			Managers, Employees
	**
	** Notes: 
	** 			Copyright (c) C. A. Lettsome Services, LLC. 2018  All rights reserved.
	**			http://www.clydelettsome.com
	*-------------------------------------------------------------------------------*/
	if ($_COOKIE['timer']=="1"&&((current_user_can('administrator'))||(current_user_can('employee'))))
	{
		//escaped next date
		$escaped_next_date = esc_html($next_date);
		
		//escaped current user
		$escaped_current_username = esc_html($current_username);
		
		//escaped super user
		$escaped_super_user = esc_html(get_option('bproductiv_super_admin'));
		
		list($date_format, $next_date_adjusted) = bproductiv_dateFormatConverter($escaped_next_date);
		
		$color_count=0;
		if ($escaped_current_username==$escaped_super_user)
		{
			$calendar = $wpdb->get_results( $wpdb->prepare( "SELECT id, notice, assigned_to, poster, task_frequency, task_repeat, task_status FROM ".$calendar_table." WHERE next_date='%s'", $escaped_next_date));
		}
		else
		{
			$calendar = $wpdb->get_results( $wpdb->prepare( "SELECT id, notice, assigned_to, poster, task_frequency, task_repeat, task_status FROM ".$calendar_table." WHERE (next_date='%s') AND (assigned_to='%s' OR poster='%s')", $escaped_next_date, $escaped_current_username, $escaped_current_username));
		}

		$display_block = "<h2>List of Reoccurring Alerts</h2> ";
		//get info and build report display
		$display_block .= "<br><br>
		<table id=\"bproductivt\">
		<tr class=\"bproductivh\">
		<td class=\"bproductivhd\"><center>Frequency: Notice</center></td>
		<td class=\"bproductivhd\"><center>Assigned To / Posted By</center></td>
		<td class=\"bproductivhd\"><center>Due Date<br>$date_format</center></td>
		<td class=\"bproductivhd\"><center>Status</center></td>
		<td class=\"bproductivhd\"><center>Delete</center></td>
		</tr>"; 

		foreach( $calendar AS $calendarArray )
		{	
			$escaped_id = esc_html($calendarArray->id);
			$escaped_notice = esc_textarea($calendarArray->notice);			
			$escaped_username = esc_html($calendarArray->assigned_to);
			$escaped_poster = esc_html($calendarArray->poster);
			$escaped_frequency = esc_html($calendarArray->task_frequency);
			$escaped_repeat = esc_html($calendarArray->task_repeat);
			$escaped_task_status = esc_html($calendarArray->task_status);
			
			if($escaped_frequency==1)
			{
				$how_often = "Yearly";
			}
			elseif($escaped_frequency==2)
			{
				$how_often = "Every Six Months";
			}
			elseif($escaped_frequency==3)
			{
				$how_often = "Every Four Months";
			}
			elseif($escaped_frequency==4)
			{
				$how_often = "Quarterly";
			}
			elseif($escaped_frequency==6)
			{
				$how_often = "Bi-Monthly";
			}
			elseif($escaped_frequency==12)
			{
				$how_often = "Monthly";
			}
			elseif($escaped_frequency==26)
			{
				$how_often = "BiWeekly";
			}
			elseif($escaped_frequency==52)
			{
				$how_often = "Weekly";
			}
			elseif($escaped_frequency==365)
			{
				$how_often = "Daily";
			}
			else
			{
				$how_often = "None";
			}

			if($escaped_task_status==1)
			{
				$status="Completed";
			}
			else
			{
				$status="<form method=\"post\" action=\"".esc_url($_SESSION['c_page'])."\">".wp_nonce_field('bproductiv_calendarUpdate_action'.$escaped_id.'', 'bproductiv_calendarUpdate_nonce'.$escaped_id.'' )."<input type=\"hidden\" name=\"next_date\" value=$escaped_next_date><input type=\"hidden\" name=\"id\" value=$escaped_id><input type=\"hidden\" name=\"repeat\" value=$escaped_repeat><input type=\"hidden\" name=\"next_date\" value=\"$escaped_next_date\"><input type=\"hidden\" name=\"frequency\" value=$escaped_frequency><input type=\"hidden\" name=\"task_status\" value=$escaped_task_status><input type=\"hidden\" name=\"action\" value=\"update\"><center><input type=\"submit\" name=\"submit\" value=\"Completed\" ></center></form>";
			}
		
			if ($color_count%2 == 0)
			{
				$display_block .= "<tr class=\"odd\">
					<td class=\"calendar_main_column1\">$how_often: $escaped_notice</td>
					<td ><center>$escaped_username / $escaped_poster</center></td>
					<td ><center>$next_date_adjusted</center></td>
					<td ><center>".$status."</center></td>
					<td >";
					if (($escaped_current_username==$escaped_poster) || ($escaped_current_username==$escaped_super_user)) //only super admin and admin poster can delete
					{
						$nonced_calendarDelete = wp_nonce_url("".esc_url(B_PRODUCTIV_PORTAL_LINK)."/?page=calendar_delete&id=$escaped_id", "delete_calendar".$escaped_id."");
						$display_block .= "<a href= \"".$nonced_calendarDelete."\" ><center>Delete</center></a>";
					}
					$display_block .= "</td>
				</tr>";
				$color_count=$color_count+1;
			}
			else
			{
				$display_block .= "<tr class=\"even\">
				<td class=\"calendar_main_column1\">$how_often: $escaped_notice</td>
				<td align=center><center>$escaped_username / $escaped_poster</center></td>
				<td><center>$next_date_adjusted</center></td>
				<td><center>".$status."</center></td>
				<td >";
					if (($escaped_current_username==$escaped_poster) || ($escaped_current_username==$escaped_super_user)) //only super admin and admin poster can delete
					{
						$nonced_calendarDelete = wp_nonce_url("".esc_url(B_PRODUCTIV_PORTAL_LINK)."/?page=calendar_delete&id=$escaped_id", "delete_calendar".$escaped_id."");
						$display_block .= "<a href= \"".$nonced_calendarDelete."\" ><center>Delete</center></a>";
					}
					$display_block .= "</td>
				</tr>";
				$color_count=$color_count+1;
			}
		}	 
		$display_block .= "</table>";
		require_once(B_PRODUCTIV_PLUGIN_PATH.'templates/cl_footer_nav.php');	
	}
	else
	{
		require_once(B_PRODUCTIV_PLUGIN_PATH .'templates/cl_access_denied.php');
	}
?>
