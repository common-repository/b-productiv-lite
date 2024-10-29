<?php
	/*-------------------------------------------------------------------------------
	** File Name: 
	**			cl_calendar_add.php
	**
	** File Description:  
	** 			Serves as the wrapper for the reoccurring alerts adding page in the 
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
	**			cl_calendar_add2.php,  
	**			 
	** Accessible By:
	**			Managers
	**
	** Notes: 
	** 			Copyright (c) C. A. Lettsome Services, LLC. 2018  All rights reserved.
	**			http://www.clydelettsome.com
	*-------------------------------------------------------------------------------*/	
	session_start();
	
	$country=""; //prevent error
	$_SESSION['country']=""; //prevents error

	$errorNum = "";
	if(ISSET($_GET["error_code"]))
	{
		$errorNum = $_GET["error_code"];
	}

	if(!ISSET($_SESSION['frequency']))
	{
		$_SESSION['frequency']="";
	}

	if(!ISSET($_SESSION['repeat']))
	{
		$_SESSION['repeat']="";
	}

	if(!ISSET($_SESSION['username22']))
	{
		$_SESSION['username22']="";
	}

	if(!ISSET($_SESSION['notice']))
	{
		$_SESSION['notice']="";
	}
	
	if(!ISSET($_SESSION['next_date']))
	{
		$_SESSION['next_date']="";
	}
	
	require_once(''.B_PRODUCTIV_PLUGIN_PATH.'css/b-productiv-style.css');
	require_once(''.B_PRODUCTIV_PLUGIN_PATH.'templates/cl_global.php');
	require_once(''.B_PRODUCTIV_PLUGIN_PATH.'templates/cl_static_functions.php'); 
	require_once(''.B_PRODUCTIV_PLUGIN_PATH.'templates/cl_static_database.php');
	require_once(''.B_PRODUCTIV_PLUGIN_PATH.'templates/cl_dropdown_menus.php');

	if ($_SESSION['p_page'] == "" )  
	{
		$_SESSION['p_page']="".esc_url(B_PRODUCTIV_PORTAL_LINK)."/?page=calendar_add";
		$_SESSION['c_page']="".esc_url(B_PRODUCTIV_PORTAL_LINK)."/?page=calendar_add";
	}
	else	
	{
		$_SESSION['p_page']=$_SESSION['c_page'];
		$_SESSION['c_page']="".esc_url(B_PRODUCTIV_PORTAL_LINK)."/?page=calendar_add";
	}

	if(ISSET($_POST['submit']))
	{ 
		include("insert_calendar.php");
	}
	else
	{ 
		include("cl_calendar_add2.php");
		print $display_block; 
	}	
?>
