<?php
	/*-------------------------------------------------------------------------------
	** File Name: 
	**			cl_calendar_delete2.php
	**
	** File Description:  
	** 			Serves as the body for the reoccurring alerts deleting page in the 
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
	**			insert_calendar.php, menu.php
	**			 
	** Accessible By:
	**			Managers
	**
	** Notes: 
	** 			Copyright (c) C. A. Lettsome Services, LLC. 2018  All rights reserved.
	**			http://www.clydelettsome.com
	*-------------------------------------------------------------------------------*/
	$retrieved_nonce = $_REQUEST['_wpnonce'];
	$escaped_id = esc_html($_GET['id']);
	
	if (wp_verify_nonce($retrieved_nonce, 'delete_calendar'.$escaped_id.'' ) )
	{
		if ($_COOKIE['timer']=="1"&&(current_user_can('administrator')))
		{
			//create and issue the query
			$result = $wpdb->get_results( $wpdb->prepare( "SELECT notice, next_date FROM ".$calendar_table." WHERE id=%d",$escaped_id));
			foreach($result as $newArray)
			{
				$escaped_next_date= esc_html($newArray->next_date);
				list($date_format, $next_date_adjusted) = bproductiv_dateFormatConverter($escaped_next_date);
				$escaped_notice= esc_html($newArray->notice);
			}
			$nonced_calendarDelete2 = wp_nonce_url("".esc_url(B_PRODUCTIV_PORTAL_LINK)."/?page=calendar_delete&id=$escaped_id&action=delete", "delete_calendar2".$escaped_id."");
			$display_block ="
			  <font color=\"#000066\"><font size=\"2\">
			
				<center>Please confirm that you would like to delete Notice: $escaped_notice that was scheduled to occurred on $next_date_adjusted ($date_format). <br><font color=\"#FF0000\" style=\"font-size:13px;\" face=\"Arial, Helvetica, sans-serif\">Note:This is a permanent action. Deleting this item will remove it from the reoccurring alert list.</font><br>
				<a href= \"".$nonced_calendarDelete2."\" ><font >Yes</font></a> / <a href=\"".esc_url(B_PRODUCTIV_PORTAL_LINK)."/?page=menu\"><font >No</font></a></center>";
			
			$display_block.="</font></b></font>
			"; 
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

