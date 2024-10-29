<?php
	/*-------------------------------------------------------------------------------
	** File Name: 
	**			cl_admin_menu.php
	**
	** File Description:  
	** 			Serves as the body of the admin dashboard in the B-Productiv Plugin
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
	**			cl_menu.php 
	**			
	** Notes: 
	** 			Copyright (c) C. A. Lettsome Services, LLC. 2018  All rights reserved.
	**			http://www.clydelettsome.com
	*-------------------------------------------------------------------------------*/
	if ($_COOKIE['timer']=="1"&&(current_user_can('administrator')))
	{
		define("ADAY", (60*60*24));
		$nowArray = getdate();
		
							
		//----variable initialization------
		$i=0;
		$validated_month = 0;
		$validated_year = 0;
		
		
		if($_POST)
		{
			//sanitize date 
			$sanitized_month = sanitize_text_field($_POST['month1']);
			$sanitized_year = sanitize_text_field($_POST['year1']);
		
			//validate date		
			$validated_month = intval($sanitized_month);
			$validated_year = intval($sanitized_year);
		}
		
		$escaped_super_user = esc_html(get_option('bproductiv_super_admin'));
		
		if (!checkdate($validated_month, 1, $validated_year)) 
		{
			
			$month = $nowArray['mon'];
			$year = $nowArray['year'];
			$day = $nowArray['mday'];
		}
		else 
		{
			$month = $validated_month;
			$year = $validated_year;
			$day = $nowArray['mday'];
		}

		$start = mktime (12, 0, 0, $month, 1, $year);
		$firstDayArray = getdate($start);

		$display_block ="
		<p><h1>Dashboard </h1></p>
		<br><strong>Display Name:</strong> ".esc_html($current_user_display_name)."<br>";
		$escaped_current_username = esc_html($current_username);
		$display_block .="<hr> </hr><TABLE id=\"menu_outer\">
			<tr>
				<td class=\"left_side_menu\">
					<h3><img src=\"".esc_url(B_PRODUCTIV_PLUGIN_PATH)."icons/reminder.png\" alt=\"Reminder\" title=\"Reminder\" height=\"45\" width=\"45\" style=\"border: 0;\">Reoccurring Alerts</h3>
				</td>
				<td>	
					<center><h3><img src=\"".esc_url(B_PRODUCTIV_PLUGIN_PATH)."icons/todo.png\" alt=\"to do\" title=\"To Do\" height=\"55\" width=\"55\" style=\"border: 0;\"> To Do's</h3></center>
				</td>
			<tr>
			<tr>
				<td class=\"left_side_menu\" >
					<b>Choose A Date</b>
					<br>
					<form method=\"post\" action=\"".esc_url(B_PRODUCTIV_PORTAL_LINK)."/?page=menu\" >";
					
					//check for order items based on user session id
					if ($escaped_current_username==$escaped_super_user)
					{
						$get_calendar_res = $wpdb->get_results( $wpdb->prepare( "SELECT day(next_date) AS post_next_date FROM ".$calendar_table." WHERE (year(next_date)=%s AND month(next_date)=%s) ORDER BY day(next_date)", $year, $month));
					}
					else
					{
						$get_calendar_res = $wpdb->get_results( $wpdb->prepare( "SELECT day(next_date) AS post_next_date FROM ".$calendar_table." WHERE ((year(next_date)= %s AND month(next_date)= %s) AND (assigned_to='%s')) ORDER BY day(next_date)", $year, $month, $escaped_current_username));
					}
					
					//get number of rows
					$rowcount = $wpdb->num_rows;
					
					$display_block .="<select name=\"month1\">";
					for ($x=1; $x <= count($months); $x++) 
					{
						$display_block .="\t<option value=\"$x\"";
						if ($x == $month){$display_block .=" SELECTED";}
						$display_block .=">".$months[$x-1]."\n";
					}

					$display_block .="</select>
					<select name=\"year1\">";

					$time=time();
					$current_date=date("Y-m-d", $time);
					$year_str=date("Y", $time);
					$year_int = (int)$year_str;
					$current_day=date("d", $time);
					$current_month=date("m", $time);
					$current_year=date("Y", $time);
					for (($x=$year_int-1); $x<($year_int+3); $x++) 
					{
						$display_block .="\t<option";
						//print($x == $year)?" SELECTED":"";
						if ($x == $year){$display_block .=" SELECTED";}
						$display_block .=">$x\n";
					}
					$display_block .="
					</select>";

					$display_block .=" <input type=\"submit\" value=\"Submit\">";

					$display_block .="</form><br><br>";
					$display_block .="<TABLE id = \"calendar_menu\">";
					foreach ($days as $day) 
					{
						$display_block .="<td class=\"day\">$day</td>";
					}
					
					for ($count=0; $count < (6*7); $count++) 
					{
						$dayArray = getdate($start); 
						if (($count % 7) == 0) 
						{
							if ($dayArray['mon'] != $month) 
							{
								break;
							} 
							else 
							{
								$display_block .="</tr><tr>\n";
							}
						}
						
						//get calendar results from the database
						if(isset($get_calendar_res[$i]))
						{
							$day_next_date = $get_calendar_res[$i]->post_next_date; 
						}
						else
						{
							$day_next_date = "";
						}
						
						if ($count < $firstDayArray['wday'] || $dayArray['mon'] != $month) //do not enter dated if not in this month
						{
							$display_block .="\t<td><br></td>\n";
						} 
						elseif ($day_next_date==$dayArray['mday']) //if database result equal this day then add link
						{     
							$day=$dayArray['mday'];
							$end_time = $year;
							$end_time .= "-";
							if ($month>9)
							{
								$end_time .= $month;
							}
							else
							{
								$end_time .= "0";
								$end_time .= $month;
							}
							$end_time .= "-";
							if ($day>9)
							{
								$end_time .= $day;
							}
							else
							{
								$end_time .= "0";
								$end_time .= $day;
							}
							
							//color background if today
							if (($current_day== $dayArray['mday'])&&($current_month== $dayArray['mon']) && ($current_year== $dayArray['year']))
							{
								$display_block .="<td class = \"today\">"; 
							}
							else
							{
								$display_block .="<td>";
							}
							$display_block .="<a href=\"".esc_url(B_PRODUCTIV_PORTAL_LINK)."/?page=calendar_main&next_date=$end_time\">".$dayArray['mday']."</a></td>\n";

							$start += ADAY;
							
							$endloop = $rowcount-1;
							//advance until new date found in database results
							while($day_next_date==$dayArray['mday'] && $i<$endloop)
							{ 
								$i=$i+1;
								$day_next_date = $get_calendar_res[$i]->post_next_date; 	
							}
						}
						else // place date with no link
						{
							if (($current_day== $dayArray['mday'])&&($current_month== $dayArray['mon']) && ($current_year== $dayArray['year']))
							{
								$display_block .="<td class=\"today\">";
							}
							else
							{
								$display_block .="<td>";
							}
							$display_block .="".$dayArray['mday']." &nbsp;&nbsp; </td>\n";
								$start += ADAY;
						}
					}
					$display_block .="</tr></table>";
					$display_block .="<a href=\"".esc_url(B_PRODUCTIV_PORTAL_LINK)."/?page=calendar_add\">Add a New Alert</a>
				</td>
				<td><br><br><br>";
					if ($escaped_current_username==$escaped_super_user)
					{
						$todo = $wpdb->get_results( $wpdb->prepare( "SELECT id, notice, assigned_to, poster, task_status, priority FROM ".$todo_table." ORDER BY priority ASC",0));
					}
					else
					{
						$todo = $wpdb->get_results( $wpdb->prepare( "SELECT id, notice, assigned_to, poster, task_status, priority FROM ".$todo_table." WHERE assigned_to='%s' OR poster = '%s' ORDER BY priority ASC", $escaped_current_username, $escaped_current_username));
					}

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


