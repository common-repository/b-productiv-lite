<?php
	/*-------------------------------------------------------------------------------
	** File Name: 
	**			cl_todo_main.php
	**
	** File Description:  
	** 			Serves as the wrapper for the task display page in the 
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
	**			cl_todo_main2.php,  
	**			 
	** Accessible By:
	**			Everyone
	**
	** Notes: 
	** 			Copyright (c) C. A. Lettsome Services, LLC. 2018  All rights reserved.
	**			http://www.clydelettsome.com
	*-------------------------------------------------------------------------------*/	
	session_start();
	
	if (ISSET($_GET['action']))
	{
		$escaped_action = esc_html($_GET['action']);
	}
	else
	{
		$escaped_action = "";
	}
	
	if (!ISSET($_POST['sorting']))
	{
		$sorting = "";
	}
	else
	{
		$sorting = $_POST['sorting'];
	}
	 
	require_once(''.B_PRODUCTIV_PLUGIN_PATH.'css/b-productiv-style.css');
	require_once(''.B_PRODUCTIV_PLUGIN_PATH.'templates/cl_global.php');
	require_once(''.B_PRODUCTIV_PLUGIN_PATH.'templates/cl_static_functions.php'); 
	require_once(''.B_PRODUCTIV_PLUGIN_PATH.'templates/cl_static_database.php');
	
	if ($_SESSION['p_page'] == "" )  
	{
		$_SESSION['p_page']="".esc_url(B_PRODUCTIV_PORTAL_LINK)."/?page=todo_main";
		$_SESSION['c_page']="".esc_url(B_PRODUCTIV_PORTAL_LINK)."/?page=todo_main";
	}
	else	
	{
		$_SESSION['p_page']=$_SESSION['c_page'];
		$_SESSION['c_page']="".esc_url(B_PRODUCTIV_PORTAL_LINK)."/?page=todo_main";
	}
	
	if(ISSET($_POST['submit']) || $escaped_action=='update')
	{
		include("insert_todo.php");
	}
	else
	{ 
		include("cl_todo_main2.php");
		print $display_block;
	} 	
?>
