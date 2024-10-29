<?php
	/*-------------------------------------------------------------------------------
	** File Name: 
	**			cl_todo_main2.php
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
	**			insert_todo.php, cl_access_denied.php
	**			 
	** Accessible By:
	**			Managers, Employees, Contractors
	**
	** Notes: 
	** 			Copyright (c) C. A. Lettsome Services, LLC. 2018  All rights reserved.
	**			http://www.clydelettsome.com
	*-------------------------------------------------------------------------------*/
	if(($sorting=="")||( isset( $_POST['bproductiv_ToDoOrder_nonce'] ) && (wp_verify_nonce( $_POST['bproductiv_ToDoOrder_nonce'], 'bproductiv_ToDoOrder_action' ))))
	{
		if ((current_user_can('administrator'))||(current_user_can('employee'))||(current_user_can('contractor')))
		{	
			$color_count=0;
			$appeared_once = 0;
			
			//sanitize the order
			$sanitized_order = sanitize_sql_orderby( $sorting ); 
			//escaped current user
			$escaped_current_username = esc_html($current_username);
			
			//escaped super user
			$escaped_super_user = esc_html(get_option('bproductiv_super_admin'));
			 
			$display_block = "<h2>List of To Do's</h2>";
			if($_COOKIE['timer']=="1"&&($_COOKIE['timer']=="1"&&((current_user_can('administrator'))||(current_user_can('employee')))))
			{
				$display_block .= "<center><a href=\"".esc_url(B_PRODUCTIV_PORTAL_LINK)."/?page=todo_add\"><img src=\"".esc_url(B_PRODUCTIV_PLUGIN_PATH)."icons/add_plus_red.png\" alt=\"Add A New To Do\" height=\"32\" width=\"32\" style=\"border: 0;\"></a></center>
				<br>";
			}
			
			//sorting form
			$display_block .= "<center><form action=\"".esc_url(B_PRODUCTIV_PORTAL_LINK)."/?page=todo_main\" method=\"POST\">
			".wp_nonce_field('bproductiv_ToDoOrder_action', 'bproductiv_ToDoOrder_nonce' )."
				<select name=\"sorting\">
					<option value=\"\"></option>
					<option value=\"priority ASC\" ";if($sanitized_order =="priority ASC"){$display_block .= "SELECTED";}$display_block .= ">Priority: Highest to Lowest</option>
					<option value=\"priority DESC\" ";if($sanitized_order =="priority DESC"){$display_block .= "SELECTED";}$display_block .= ">Priority: Lowest to Highest</option>
					<option value=\"date DESC\" ";if($sanitized_order =="date DESC"){$display_block .= "SELECTED";}$display_block .= ">Date: Newest to Oldest</option>
					<option value=\"date ASC\" ";if($sanitized_order =="date ASC"){$display_block .= "SELECTED";}$display_block .= ">Date: Oldest to Newest</option>
					<option value=\"task_status ASC\" ";if($sanitized_order =="task_status ASC"){$display_block .= "SELECTED";}$display_block .= ">Status: Incomplete to Complete</option>
					<option value=\"task_status DESC\" ";if($sanitized_order =="task_status DESC"){$display_block .= "SELECTED";}$display_block .= ">Status: Complete to Incomplete</option>
				</select>
				<input type=\"submit\" value=\"Sort\">
			</form></center>";
			
			//query the database for specified order
			if ($escaped_current_username==$escaped_super_user)
			{
				if($sanitized_order =="")
				{
					$todo = $wpdb->get_results( $wpdb->prepare( "SELECT id, notice, assigned_to, task_status, priority, datee, poster FROM ".$todo_table." ORDER BY priority ASC",0));
				}
				else
				{
					$todo = $wpdb->get_results( $wpdb->prepare( "SELECT id, notice, assigned_to, task_status, priority, datee, poster FROM ".$todo_table." ORDER BY ".$sanitized_order." ",0 ));
				}
			}
			 else
			{
				if($sanitized_order =="")
				{
					$todo = $wpdb->get_results( $wpdb->prepare( "SELECT id, notice, assigned_to, task_status, priority, poster, datee FROM ".$todo_table." WHERE assigned_to='%s' OR poster='%s' ORDER BY priority ASC", $escaped_current_username, $escaped_current_username));
				}
				else
				{
					$todo = $wpdb->get_results( $wpdb->prepare( "SELECT id, notice, assigned_to, task_status, priority, poster, datee FROM ".$todo_table." WHERE assigned_to='%s' OR poster='%s' ORDER BY ".$sanitized_order."", $escaped_current_username, $escaped_current_username ));
				}
			}
			$temp_date= "2018-01-01";
			list($date_format, $next_date_adjusted) = bproductiv_dateFormatConverter($temp_date);
			//get info and build report display
			$display_block .= "<br><br>
			<table id=\"bproductivt\">
			<tr class=\"bproductivh\">
			<td class=\"bproductivhd\"><center>Priority</center></td>		
			<td class=\"bproductivhd\"><center>Notice</center></td>
			<td class=\"bproductivhd\"><center>Assigned To/Posted By</center></td>
			<td class=\"bproductivhd\"><center>Date Assigned<br>$date_format</center></td>	
			<td class=\"bproductivhd\"><center>Status</center></td>
			<td class=\"bproductivhd\"><center>Delete</center></td>
			</tr>";
			
			//get number of rows
			$rowcount = $wpdb->num_rows;
			if ($rowcount > 0) 
			{	
				$display_block .= "<form method=\"post\" action=\"".esc_url(B_PRODUCTIV_PORTAL_LINK)."/?page=todo_add\" >
				
				";
				foreach( $todo AS $todoArray )
				{	
					$escaped_id = esc_html($todoArray->id);
					$escaped_notice = esc_textarea($todoArray->notice);			
					$escaped_username = esc_html($todoArray->assigned_to);
					$escaped_date=esc_html($todoArray->datee);
					$escaped_priority1 = esc_html($todoArray->priority);
					$escaped_task_status = esc_html($todoArray->task_status);
					$escaped_poster = esc_html($todoArray->poster);
					
					list($date_format, $next_date_adjusted) = bproductiv_dateFormatConverter($escaped_date);
					
					if ($escaped_priority1==1)
					{
						$priority="High";
						$priority2="high_hot";
					}
					elseif($escaped_priority1==2)
					{
						$priority="Normal";
						$priority2="normal_alert";
					}
					else
					{
						$priority="Low";
						$priority2="normal_info";
					}

					if($escaped_task_status==1)
					{
						$status="<img src=\"".esc_url(B_PRODUCTIV_PLUGIN_PATH)."icons/check_ok.png\" alt=\"Completed\" height=\"20\" width=\"20\" style=\"border: 0;\">";
						$status_gray="<img src=\"".esc_url(B_PRODUCTIV_PLUGIN_PATH)."icons/check_ok_gray.png\" alt=\"Completed\" height=\"20\" width=\"20\" style=\"border-style: none; background-color: #E3E3E3; \">";
					}
					else
					{
						$status="<input type=\"submit\" name=\"submit\" value=\"Mark as Complete\" >";
						$status_gray="<input type=\"submit\" name=\"submit\" value=\"Mark as Complete\" >";
					}
					
					//create nonce url for updates
 					$nonced_toDoUpdate = wp_nonce_url("".esc_url(B_PRODUCTIV_PORTAL_LINK)."/?page=todo_main&id=$escaped_id&action=update", "update_todo".$escaped_id."");
					if ($color_count%2 == 0)
					{
						$display_block .= "<tr class=\"odd\">
						<td class=\"todo_column1\"><center><img src=\"".esc_url(B_PRODUCTIV_PLUGIN_PATH)."icons/".$priority2."_gray.png\" alt=\"".$priority."\" height=\"28\" width=\"28\" style=\"border: 0; background-color: #E3E3E3;\"></center></td>
						<td>$escaped_notice</td>
						<td><center>$escaped_username / $escaped_poster</center></td>
						<td><center>$next_date_adjusted</center></td>
						<td><center>"; if ($escaped_task_status==1){$display_block .= "$status_gray";} else {$display_block .= "<a href= \"".$nonced_toDoUpdate."\" ><center><button type=\"button\">MARK AS COMPLETE</button></center> </a>";} $display_block .= "</center></td>
						<td>";
						if ((($escaped_current_username==$escaped_poster) || ($escaped_current_username==$escaped_super_user))&&($escaped_task_status==1)) //only poster and super user allowed to delete if the task has been completed.
						{
							$display_block .= "<center><input name=\"".$escaped_id."\" type=\"checkbox\" value=\"TRUE\" ></center>";
							$appeared_once =1;
						}
						
						$display_block .= "</td> 
						</tr>";
						$color_count=$color_count+1;
					}
					else
					{
						$display_block .= "<tr class=\"even\">
						<td class=\"todo_column1\"><center><img src=\"".esc_url(B_PRODUCTIV_PLUGIN_PATH)."icons/".$priority2.".png\" alt=\"".$priority."\" height=\"28\" width=\"28\" style=\"border: 0;\"></center></td>
						<td>$escaped_notice</td>
						<td><center>$escaped_username / $escaped_poster</center></td>
						<td><center>$next_date_adjusted</center></td>
						<td><center>"; if ($escaped_task_status==1){$display_block .= "$status";} else {$display_block .= "<a href= \"".$nonced_toDoUpdate."\" ><center><button type=\"button\">MARK AS COMPLETE</button></center> </a>";} $display_block .= "</center></td>
						<td >";
						if ((($escaped_current_username==$escaped_poster) || ($escaped_current_username==$escaped_super_user))&&($escaped_task_status==1)) //only poster and super user allowed to delete if the task has been completed.
						{
							$display_block .= "<center><input name=\"".$escaped_id."\" type=\"checkbox\" value=\"TRUE\" ></center>";
							$appeared_once =1;
						}
						
						$display_block .= "</td>
						</tr>";
						$color_count=$color_count+1;
					} 
				}
				$display_block .= "</table>";
				if ($appeared_once==1) //only poster and super user allowed to delete if the task has been completed.
				{
					$display_block .= "<input type=\"hidden\" name=\"action\" value=\"delete\"><input type=\"submit\" name=\"submit\" value=\"Submit\" >
					<input type=\"reset\" name=\"Submit2\" value=\"Reset\" >
					".wp_nonce_field('bproductiv_todoDelete_action', 'bproductiv_todoDelete_nonce' )."
					</form><br><br>";
				}
				else
				{
					$display_block .= "</form><br><br>";
				}
			}
			else
			{
				$display_block .= "</table>
				<center>There are no tasks available.</center><br><br>";
			}
			
			//Calendar section
			if ($_COOKIE['timer']=="1"&&((current_user_can('administrator'))||(current_user_can('employee'))))
			{
				$today = date("Y-m-d");
				$tomorrow = date("Y-m-d", strtotime("+1 day"));

				$color_count=0;
				if ($escaped_current_username==$escaped_super_user)
				{
					$calendar = $wpdb->get_results( $wpdb->prepare( "SELECT id, notice, assigned_to, poster, task_frequency, task_repeat, task_status, next_date FROM ".$calendar_table." WHERE next_date='%s' OR next_date='%s'", $today, $tomorrow));
				}
				else
				{
					$calendar = $wpdb->get_results( $wpdb->prepare( "SELECT id, notice, assigned_to, poster, task_frequency, task_repeat, task_status, next_date FROM ".$calendar_table." WHERE (next_date='%s' OR next_date='%s') AND (assigned_to='%s' OR poster='%s')", $today, $tomorrow, $escaped_current_username, $escaped_current_username));
				} 
				$rowcount = $wpdb->num_rows;
				
				$display_block .= "<h2>List of Upcoming Reoccurring Alerts</h2>";

				//get info and build report display
				$display_block .= "<br><br>
				<table id=\"bproductivt\">
				<tr class=\"bproductivh\">
				<td class=\"bproductivhd\"><center>Frequency: Notice</center></td>
				<td class=\"bproductivhd\"><center>Assigned To/Posted By</center></td>
				<td class=\"bproductivhd\"><center>Due Date<br>$date_format</center></td>
				<td class=\"bproductivhd\"><center>Status</center></td>
				<td class=\"bproductivhd\"><center>Delete</center></td>
				</tr>"; 
				
				if ($rowcount>0) 
				{
					foreach( $calendar AS $calendarArray )
					{
						$escaped_id = esc_html($calendarArray->id);
						$escaped_notice = esc_textarea($calendarArray->notice);			
						$escaped_username = esc_html($calendarArray->assigned_to);
						$escaped_poster = esc_html($calendarArray->poster);
						$escaped_frequency = esc_html($calendarArray->task_frequency);
						$escaped_repeat = esc_html($calendarArray->task_repeat);
						$escaped_task_status = esc_html($calendarArray->task_status);
						$escaped_next_date = esc_html($calendarArray->next_date);
						$date = date_create($escaped_next_date);

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
		
						//$notify_date=date_format($date, 'Y-m-d');
						list($date_format, $next_date_adjusted) = bproductiv_dateFormatConverter($escaped_next_date);

						if($today<=$escaped_next_date)
						{ 
							if($escaped_task_status==1)
							{
								$status="Completed";
							}
							else
							{
								$status="<input type=\"submit\" name=\"submit\" value=\"Mark as Complete\" >";
							}
						
							if ($color_count%2 == 0)
							{
								$display_block .= "<tr class=\"odd\">
									<td class=\"calendar_main_column1\">$how_often: $escaped_notice</td>
									<td><center>$escaped_username / $escaped_poster</center></td>
									<td><center>$next_date_adjusted</center></td>
									<td><center><form method=\"post\" action=\"".esc_url(B_PRODUCTIV_PORTAL_LINK)."/?page=calendar_main\">".wp_nonce_field('bproductiv_calendarUpdate_action'.$escaped_id.'', 'bproductiv_calendarUpdate_nonce'.$escaped_id.'' )."<input type=\"hidden\" name=\"next_date\" value=$escaped_next_date><input type=\"hidden\" name=\"id\" value=$escaped_id><input type=\"hidden\" name=\"repeat\" value=$escaped_repeat><input type=\"hidden\" name=\"next_date\" value=\"$escaped_next_date\"><input type=\"hidden\" name=\"frequency\" value=$escaped_frequency>".$status."<input type=\"hidden\" name=\"action\" value=\"update\"></form></center></td>
									<td >";
									if (($escaped_current_username==$escaped_poster) || ($escaped_current_username==$escaped_super_user)) //only admins can delete
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
									<td ><center>$escaped_username / $escaped_poster</center></td>
									<td><center>$next_date_adjusted</center></td>
									<td><center><form method=\"post\" action=\"".esc_url(B_PRODUCTIV_PORTAL_LINK)."/?page=calendar_main\">".wp_nonce_field('bproductiv_calendarUpdate_action'.$escaped_id.'', 'bproductiv_calendarUpdate_nonce'.$escaped_id.'' )."<input type=\"hidden\" name=\"next_date\" value=$escaped_next_date><input type=\"hidden\" name=\"id\" value=$escaped_id><input type=\"hidden\" name=\"repeat\" value=$escaped_repeat><input type=\"hidden\" name=\"next_date\" value=\"$escaped_next_date\"><input type=\"hidden\" name=\"frequency\" value=$escaped_frequency>".$status."<input type=\"hidden\" name=\"action\" value=\"update\"></form></center></td>
									<td >";
									if (($escaped_current_username==$escaped_poster) || ($escaped_current_username==$escaped_super_user)) //only admins can delete
									{
										$nonced_calendarDelete = wp_nonce_url("".esc_url(B_PRODUCTIV_PORTAL_LINK)."/?page=calendar_delete&id=$escaped_id", "delete_calendar".$escaped_id."");
										$display_block .= "<a href= \"".$nonced_calendarDelete."\" ><center>Delete</center></a>";
									}
									$display_block .= "</td>
								</tr>";
								$color_count=$color_count+1;
							}
						} 
					} 
					$display_block .= "</table>"; 
				}
				else
				{ 
					$display_block .= "</table>
					<center>There are no upcoming alerts to complete today or tomorrow.<br>
					Please do check the main Reoccurring Alerts area for possible additional alerts.</center><br><br>";
				}
			} 	
			if($_COOKIE['timer']=="1"&&((current_user_can('administrator'))||(current_user_can('employee'))))
			{
				$display_block .= "<center><a href=\"".esc_url(B_PRODUCTIV_PORTAL_LINK)."/?page=todo_add\"><img src=\"".esc_url(B_PRODUCTIV_PLUGIN_PATH)."icons/add_plus_red.png\" alt=\"Add A New To Do\" height=\"32\" width=\"32\" style=\"border: 0;\"></a></center>
				<br>";
			}
			$display_block .= "<br><br>";
			require_once(B_PRODUCTIV_PLUGIN_PATH.'templates/cl_footer_nav.php'); 
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
