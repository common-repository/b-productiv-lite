<?php
	/*-------------------------------------------------------------------------------
	** File Name: 
	**			b-productiv_template_config.php
	**
	** File Description:  
	** 			Serves as the file used to select the correct portal page
	**			B-Productiv Plugin
	**
	** File Last Updated On: 
	**			6/22/2018
	**
	** Original Author: 
	**			Clyde A. Lettsome, PhD, PE
	**
	** Last Editor: 
	**			Clyde A. Lettsome, PhD, PE
	**
	** File Layer: 
	** 			Service Layer
	**
	** File Calls//Submits: 
	**			All Portal Pages 
	**			 
	** Accessible By:
	**			Everyone
	**
	** Notes: 
	** 			Copyright (c) C. A. Lettsome Services, LLC. 2018  All rights reserved.
	**			http://www.clydelettsome.com
	*-------------------------------------------------------------------------------*/
	$escaped_page = esc_html($_GET["page"]);
	
	//check for errors
/* 	error_reporting(E_ALL);
	ini_set('display_errors', 1); */ 
	
	$dim_div="<meta name=viewport content='width=700'><div style= \"width:100%\">";
	$dim_close="</div>";
	
	$time_zone = esc_html(get_option('bproductiv_time_zone'));

	//set your timezone
	date_default_timezone_set($time_zone);

	if($escaped_page=="reset_password")
	{
		print $dim_div;	
		include("login/cl_reset_password.php");
		print $dim_close;
	}
	elseif($escaped_page=="pass_change")
	{
		print $dim_div;
		include("associates/cl_pass_change.php");
		print $dim_close;
	}
	elseif($escaped_page=="menu")
	{ 
		print $dim_div;
		include("menu/cl_menu.php");
		print $dim_close;
	}
	elseif($escaped_page=="calendar_add")
	{ 
		print $dim_div;
		include("calendar/cl_calendar_add.php");
		print $dim_close;
	}
	elseif($escaped_page=="calendar_main")
	{ 
		print $dim_div;
		
		include("calendar/cl_calendar_main.php");

		print $dim_close;
	}
	elseif($escaped_page=="calendar_delete")
	{ 
		print $dim_div;
		include("calendar/cl_calendar_delete.php");
		print $dim_close;
	}
	elseif($escaped_page=="todo_main")
	{ 
		print $dim_div;
		include("tasks/cl_todo_main.php");
		print $dim_close;
	}
	elseif($escaped_page=="todo_add")
	{ 
		print $dim_div;
		include("tasks/cl_todo_add.php");
		print $dim_close;
	}
	elseif($escaped_page=="todo_delete")
	{ 
		print $dim_div;
		include("tasks/cl_todo_delete.php");
		print $dim_close;
	}
	elseif($escaped_page=="team_member_update")
	{
		print $dim_div;
		include("associates/cl_team_member_update.php");
		print $dim_close;
	}
	elseif($escaped_page=="logout")
	{
		print $dim_div;
		include("login/cl_logout.php");
		print $dim_close;
	}
	else
	{
		print $dim_div;
		include("login/cl_login.php");
		print $dim_close;
	}
	
	print "<p style=\"text-align: right; font-family: Times New Roman, Times, serif; font-size: 13px;\">Powered by <a href=\"http://clydelettsome.com/blog/b-productiv\" target=\"_blank\">B-Productiv Lite</a> <br>from <a href=\"http://clydelettsome.com\" target=\"_blank\">C A Lettsome Services, LLC</a></p";
?>