<?php
	/*-------------------------------------------------------------------------------
	** File Name: 
	**			cl_print_error.php
	**
	** File Description:  
	** 			Serves as the print error code for the B-Productiv Plugin
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
	if (($errorNum!=""))
	{	
		//generate a query to obtain error message
		$validated_errorNum = intval($errorNum);
		
		$result = $wpdb->get_results( $wpdb->prepare( "SELECT * from ".$error_table." WHERE error_code= '%s' ", esc_html($validated_errorNum)));
		
		foreach( $result AS $row )
		{
			$errorMSG = $row->error_english;
		}

		$display_block.="<font color=\"red\" face=\"Arial, Helvetica, sans-serif\" style=\"font-size:13px;\"><b>Error: ";
		$display_block.= esc_html($errorMSG);
		$display_block.="</b></font>";
		
		//**clear the error code** 
		$errorNum="";

	}
?>
