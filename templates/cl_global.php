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
	$current_user = wp_get_current_user();
	global $current_user;

    $current_username = $current_user->user_login;
    $current_user_email_address = $current_user->user_email;
    $current_user_first_name = $current_user->user_firstname;
    $current_user_last_name = $current_user->user_lastname;
    $current_user_display_name = $current_user->display_name;
    $current_user_id = $current_user->ID;
?>