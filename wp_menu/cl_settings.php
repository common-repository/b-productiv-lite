<?php
	/*-------------------------------------------------------------------------------
	** File Name: 
	**			cl_settings.php
	**
	** File Description:  
	** 			Serves as the wrapper for the Wordpress setting page in the 
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
	**			cl_settings2.php,  
	**			 
	** Accessible By:
	**			Super Admin
	**
	** Notes: 
	** 			Copyright (c) C. A. Lettsome Services, LLC. 2018  All rights reserved.
	**			http://www.clydelettsome.com
	*-------------------------------------------------------------------------------*/
	session_start();
	
	$country=""; //prevent error
	$_SESSION['country']=""; //prevents error
	
	//CONSTANTS
	define( 'B_PRODUCTIV_PLUGIN_PATH', plugin_dir_path(dirname( __FILE__ ) ) );
	
	$site_url = get_site_url();
	$url_parse = wp_parse_url($site_url);
	define( 'B_PRODUCTIV_DOMAIN', ''.$url_parse['host'].'');
	
	global $wpdb;
	$table_name = "".$wpdb->prefix . "bproductiv_";
	require_once(''.B_PRODUCTIV_PLUGIN_PATH.'templates/cl_static_database.php');
	require_once(''.B_PRODUCTIV_PLUGIN_PATH.'templates/cl_static_functions.php'); 
	require_once(''.B_PRODUCTIV_PLUGIN_PATH.'templates/cl_dropdown_menus.php');  
	require_once(''.B_PRODUCTIV_PLUGIN_PATH.'templates/cl_global.php'); 
	
	$errorNum = "";
	if(ISSET($_GET["error_code"]))
	{
		$errorNum = $_GET["error_code"];
	}

	include("cl_settings2.php");
	print $display_block;
?>