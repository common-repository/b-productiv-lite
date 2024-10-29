<?php
	/*-------------------------------------------------------------------------------
	** File Name: 
	**			cl_calendar_delete.php
	**
	** File Description:  
	** 			Serves as the wrapper for the reoccurring alerts deleting page in the 
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
	**			cl_calendar_delete2.php,  
	**			 
	** Accessible By:
	**			Managers
	**
	** Notes: 
	** 			Copyright (c) C. A. Lettsome Services, LLC. 2018  All rights reserved.
	**			http://www.clydelettsome.com
	*-------------------------------------------------------------------------------*/	
	session_start();

	require_once(''.B_PRODUCTIV_PLUGIN_PATH.'css/b-productiv-style.css');
	require_once(''.B_PRODUCTIV_PLUGIN_PATH.'templates/cl_global.php'); 
	require_once(''.B_PRODUCTIV_PLUGIN_PATH.'templates/cl_static_database.php');
	require_once(''.B_PRODUCTIV_PLUGIN_PATH.'templates/cl_static_functions.php');

	if (ISSET($_GET['action']))
	{
		$escaped_action = esc_html($_GET['action']);
	}
	else
	{
		$escaped_action = "";
	}

	$escaped_id = esc_html($_GET['id']);
	
	if ($_SESSION['p_page'] == "" )  
	{
		$_SESSION['p_page']="".esc_url(B_PRODUCTIV_PORTAL_LINK)."/?page=calendar_delete";
		$_SESSION['c_page']="".esc_url(B_PRODUCTIV_PORTAL_LINK)."/?page=calendar_delete";
	}
	else	
	{
		$_SESSION['p_page']=$_SESSION['c_page'];
		$_SESSION['c_page']="".esc_url(B_PRODUCTIV_PORTAL_LINK)."/?page=calendar_delete";
	}	

	if($escaped_action=="delete")
	{ 
		include("insert_calendar.php");
	}
	else
	{ 
		include("cl_calendar_delete2.php");
		print $display_block;
	}
?>