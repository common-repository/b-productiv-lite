<?php
	/*-------------------------------------------------------------------------------
	** File Name: 
	**			cl_menu.php
	**
	** File Description:  
	** 			Serves as the wrapper for the dashboard in the B-Productiv Plugin
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
	**			cl_admin_menu.php, cl_employee_menu.php, cl_contractor_menu.php, 
	**			cl_access_denied.php
	** 
	** Notes: 
	** 			Copyright (c) C. A. Lettsome Services, LLC. 2018  All rights reserved.
	**			http://www.clydelettsome.com
	*-------------------------------------------------------------------------------*/	
	session_start();
	
	$country=""; //prevent error
	$_SESSION['country']=""; //prevents error
	
	require_once(''.B_PRODUCTIV_PLUGIN_PATH.'css/b-productiv-style.css');
	require_once(''.B_PRODUCTIV_PLUGIN_PATH.'templates/cl_static_database.php');
	require_once(''.B_PRODUCTIV_PLUGIN_PATH.'templates/cl_dropdown_menus.php');	
	require_once(''.B_PRODUCTIV_PLUGIN_PATH.'templates/cl_global.php');
	
	$current_day = date('D'); //find current day
	$today = date('Y-m-d'); //find today's date 

	if ($_SESSION['p_page'] == "" )  
	{
		$_SESSION['p_page']="".esc_url(B_PRODUCTIV_PORTAL_LINK)."/?page=menu";
		$_SESSION['c_page']="".esc_url(B_PRODUCTIV_PORTAL_LINK)."/?page=menu";
	}
	else	
	{
		$_SESSION['p_page']=$_SESSION['c_page'];
		$_SESSION['c_page']="".esc_url(B_PRODUCTIV_PORTAL_LINK)."/?page=menu";
	}
	
	if ($_COOKIE['timer']=="1"&&(current_user_can('administrator')))
	{	
		require_once("cl_admin_menu.php");
	}
	elseif ($_COOKIE['timer']=="1"&&(current_user_can('employee')))
	{
		require_once("cl_employee_menu.php");
	}
	elseif ($_COOKIE['timer']=="1"&&(current_user_can('contractor')))
	{
		require_once("cl_contractor_menu.php");
	}
	else
	{
		require_once(B_PRODUCTIV_PLUGIN_PATH .'templates/cl_access_denied.php');
	} 
	print $display_block;
?>

