<?php
	/*-------------------------------------------------------------------------------
	** File Name: 
	**			cl_calendar_main.php
	**
	** File Description:  
	** 			Serves as the wrapper for the reoccurring alert display page in the 
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
	**			cl_calendar_main2.php,  
	**			 
	** Accessible By:
	**			Everyone
	**
	** Notes: 
	** 			Copyright (c) C. A. Lettsome Services, LLC. 2018  All rights reserved.
	**			http://www.clydelettsome.com
	*-------------------------------------------------------------------------------*/	
	session_start();
 	
	require_once(''.B_PRODUCTIV_PLUGIN_PATH.'css/b-productiv-style.css');
	require_once(''.B_PRODUCTIV_PLUGIN_PATH.'templates/cl_static_database.php');
	require_once(''.B_PRODUCTIV_PLUGIN_PATH.'templates/cl_global.php'); 
	require_once(''.B_PRODUCTIV_PLUGIN_PATH.'templates/cl_static_functions.php');

	if ($_SESSION['p_page'] == "" )  
	{
		$_SESSION['p_page']="".esc_url(B_PRODUCTIV_PORTAL_LINK)."/?page=calendar_main";
		$_SESSION['c_page']="".esc_url(B_PRODUCTIV_PORTAL_LINK)."/?page=calendar_main";
	}
	else	
	{
		$_SESSION['p_page']=$_SESSION['c_page'];
		$_SESSION['c_page']="".esc_url(B_PRODUCTIV_PORTAL_LINK)."/?page=calendar_main";
	}
	
	//escaped next date
 	$escaped_next_date = esc_html($_GET['next_date']);	
	if(ISSET($escaped_next_date))
	{
		$next_date = $_GET['next_date'];
	}
	else 
	{
		$next_date = "";
	} 
	
	if(ISSET($_POST['submit']))
	{ 
		include("insert_calendar.php");
	}
	else
	{ 
		include("cl_calendar_main2.php");
		print $display_block;
	}
?>

