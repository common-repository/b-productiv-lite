<?php
	/*-------------------------------------------------------------------------------
	** File Name: 
	**			cl_logout.php
	**
	** File Description:  
	** 			Serves as the logout page for the B-Productiv Plugin		
	**
	** File Last Updated On: 
	**			6/12/2018
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
	**			N/A
	**			 
	** Accessible By:
	**			Everyone
	**
	** Notes: 
	** 			Copyright (c) C. A. Lettsome Services, LLC. 2018  All rights reserved.
	**			http://www.clydelettsome.com
	*-------------------------------------------------------------------------------*/		
	session_start();
	
	session_unset();
	session_destroy();
	$_SESSION = array();
	
	//set authorization cookie 
	$auth_code=100000000;
	//unset the ;
   	setcookie("auth2", $auth_code, time()-3600*8, "/", B_PRODUCTIV_DOMAIN, 0);
	
	//logout of wordpress
	wp_logout();
	
	//go back to the login page
	header("Location: ".esc_url(B_PRODUCTIV_PORTAL_LINK)."/?page=");
	exit;
?>