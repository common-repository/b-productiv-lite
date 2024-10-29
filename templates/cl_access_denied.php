<?php
	/*-------------------------------------------------------------------------------
	** File Name: 
	**			cl_access_denied.php
	**
	** File Description:  
	** 			Serves as the file used to indicate invalid access in the
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
	** 			Service Layer
	**
	** File Calls//Submits: 
	**			menu.php 
	**			 
	** Accessible By:
	**			Everyone
	**
	** Notes: 
	** 			Copyright (c) C. A. Lettsome Services, LLC. 2018  All rights reserved.
	**			http://www.clydelettsome.com
	*-------------------------------------------------------------------------------*/	
	
	session_unset();
	session_destroy();
	$_SESSION = array();
	
	 
	//set authorization cookie 
	$auth_code="100000000";
	
	 setcookie( "auth2", $auth_code, time() - 8 * 3600,  "/", ".B_PRODUCTIV_DOMAIN.", 0 );
	
	//logout of wordpress
	wp_logout();

	$display_block ="<center><img src=\"".esc_url(B_PRODUCTIV_PLUGIN_PATH)."icons/access_denied.png\" alt=\"Access Denied\" height=\"64\" width=\"64\" style=\"border: 0;\"></center>This page cannot be accessed without logging in first. If you were logged in, the page was closed for your protection. To gain access to this page you must log in.";
	$display_block.="<p><a href=\"".esc_url($site_url)."/b-productiv-portal/?page=\" >Restart the re-login process.</a></p>";
?>