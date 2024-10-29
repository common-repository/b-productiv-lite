<?php
	/*-------------------------------------------------------------------------------
	** File Name: 
	**			cl_login.php
	**
	** File Description:  
	** 			Serves as the wrapper for the login page in the B-Productiv Plugin			
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
	**			cl_login2.php  
	**			 
	** Accessible By:
	**			Everyone
	**
	** Notes: 
	** 			Copyright (c) C. A. Lettsome Services, LLC. 2018  All rights reserved.
	**			http://www.clydelettsome.com
	*-------------------------------------------------------------------------------*/	
	session_start();
	
	$errorNum = "";
	if(ISSET($_GET["error_code"]))
	{
		$errorNum = $_GET["error_code"];
	}

	require_once(''.B_PRODUCTIV_PLUGIN_PATH.'css/b-productiv-style.css');
	require_once(''.B_PRODUCTIV_PLUGIN_PATH.'templates/cl_static_functions.php'); 
	require_once(''.B_PRODUCTIV_PLUGIN_PATH.'templates/cl_static_database.php');

	if(!ISSET($_SESSION['p_page']))
	{
		$_SESSION['p_page'] = "";
	}	

 	if ($_SESSION['p_page'] == "" )  
	{
		$_SESSION['p_page']="".esc_url(B_PRODUCTIV_PORTAL_LINK)."/?page=";
		$_SESSION['c_page']="".esc_url(B_PRODUCTIV_PORTAL_LINK)."/?page=";
	}
	else	
	{
		$_SESSION['p_page']="".$_SESSION['c_page'];
		$_SESSION['c_page']="".esc_url(B_PRODUCTIV_PORTAL_LINK)."/?page=";
	} 

	if(ISSET($_POST['submit']))
	{
		include("insert_login.php");
	}
	else
	{ 
		include("cl_login2.php");
		print $display_block;
	} 	
?>
