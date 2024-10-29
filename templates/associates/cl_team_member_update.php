<?php	
	/*-------------------------------------------------------------------------------
	** File Name: 
	**			cl_team_member_update.php
	**
	** File Description:  
	** 			Serves as the wrapper for the team member update changing page in the 
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
	**			cl_team_member_update2.php,  
	**			 
	** Accessible By:
	**			Everyone
	**
	** Notes: 
	** 			Copyright (c) C. A. Lettsome Services, LLC. 2018  All rights reserved.
	**			http://www.clydelettsome.com
	*-------------------------------------------------------------------------------*/	
	session_start();
	
	if(!ISSET($_GET['country']))
	{
		$_GET['country']="";
	}
	if(!ISSET($country))
	{
		$country="";
	}
	
	if(!ISSET($_SESSION['per_email']))
	{
		$_SESSION['per_email']="";
		
	}
	if(!ISSET($per_email))
	{
		$per_email="";
	}
	
	if(!ISSET($_SESSION['phone']))
	{
		$_SESSION['phone']="";
	}
	if(!ISSET($phone))
	{
		$phone = "";
	}
	
	if(!ISSET($_SESSION['address']))
	{
		$_SESSION['address']="";
	}
	if(!ISSET($address))
	{
		$address = "";
	}
	
	if(!ISSET($_SESSION['city']))
	{
		$_SESSION['city']="";
	}
	if(!ISSET($city))
	{
		$city = "";
	}
	
	if(!ISSET($_SESSION['zip']))
	{
		$_SESSION['zip']="";
	}
	if(!ISSET($zip))
	{
		$zip = "";
	}
	
	if(!ISSET($_SESSION['state']))
	{
		$_SESSION['state']="";
	}
	if(!ISSET($state))
	{
		$state = "";
	}
	
	$errorNum = "";
	if(ISSET($_GET["error_code"]))
	{
		$errorNum = $_GET["error_code"];
	}
	
	if($_GET['country']!="")
	{	
		$_SESSION['country']= esc_html($_GET['country']);
		$_SESSION['address']= esc_html($_GET['address']);
		$_SESSION['zip']= esc_html($_GET['zip']);
		$_SESSION['city']= esc_html($_GET['city']);
		$_SESSION['phone']= esc_html($_GET['phone']);
		$_SESSION['per_email']= esc_html($_GET['per_email']);
	}
	
	require_once(''.B_PRODUCTIV_PLUGIN_PATH.'css/b-productiv-style.css');
	require_once(''.B_PRODUCTIV_PLUGIN_PATH.'templates/cl_global.php');
	require_once(''.B_PRODUCTIV_PLUGIN_PATH.'templates/cl_static_functions.php'); 
	require_once(''.B_PRODUCTIV_PLUGIN_PATH.'templates/cl_static_database.php'); 
	
	if ($_SESSION['p_page'] == "" )  
	{
		$_SESSION['p_page']="".esc_url(B_PRODUCTIV_PORTAL_LINK)."/?page=team_member_update";
		$_SESSION['c_page']="".esc_url(B_PRODUCTIV_PORTAL_LINK)."/?page=team_member_update";
	}
	else	
	{
		$_SESSION['p_page']=$_SESSION['c_page'];
		$_SESSION['c_page']="".esc_url(B_PRODUCTIV_PORTAL_LINK)."/?page=team_member_update";
	}

	
	if(ISSET($_POST['submit']))
	{
		include("insert_team_member_info.php");
	}
	else
	{ 
		include("cl_team_member_update2.php");
		print $display_block;
	} 	
?>