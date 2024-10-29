<?php
	/*-------------------------------------------------------------------------------
	** File Name: 
	**			cl_static_functions.php
	**
	** File Description:  
	** 			Serves as the file used to store the functions for the
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
	** 			Business Layer
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
	function bproductiv_dateFormatConverter($date)
	{
		$dateNum = get_option('bproductiv_date_format');
		if($dateNum == 2)
		{
			$date_adjusted = date("d-m-Y", strtotime($date));
			$format_date= "DD-MM-YYYY";
		}
		elseif($dateNum == 3)
		{
			$date_adjusted = date("Y-m-d", strtotime($date));
			$format_date= "YYYY-MM-DD";
		}
		else
		{
			$date_adjusted = date("m-d-Y", strtotime($date));
			$format_date= "MM-DD-YYYY";
		}		
		return array($format_date, $date_adjusted);
	}
?>