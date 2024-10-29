<?php
	/*-------------------------------------------------------------------------------
	** File Name: 
	**			cl_static_database.php
	**
	** File Description:  
	** 			Serves as the file used to store the database names for the
	**			B-Productiv Plugin
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
	** 			Service Layer
	**
	** File Calls//Submits: 
	**			All Portal Pages 
	**			 
	** Accessible By:
	**			Everyone
	**
	** Notes: 
	** 			Copyright (c) C. A. Lettsome Services, LLC. 2018  All rights reserved.
	**			http://www.clydelettsome.com
	*-------------------------------------------------------------------------------*/
	$error_table="".$table_name."error_msg";
	$calendar_table="".$table_name."calendar";
	$todo_table="".$table_name."tasks";

	$wordpress_usermeta="".$wpdb->prefix."usermeta";
	$wordpress_users="".$wpdb->prefix."users";
	$table_contact="".$table_name."user_info";

?>