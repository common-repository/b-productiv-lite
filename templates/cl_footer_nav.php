<?php
	/*-------------------------------------------------------------------------------
	** File Name: 
	**			cl_footer_nav.php
	**
	** File Description:  
	** 			Serves as the navigation footer for the portal pages in the
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
	**			All Portal Pages 
	**			 
	** Accessible By:
	**			Everyone
	**
	** Notes: 
	** 			Copyright (c) C. A. Lettsome Services, LLC. 2018  All rights reserved.
	**			http://www.clydelettsome.com
	*-------------------------------------------------------------------------------*/
	$display_block .= "<table id=\"footer\">
		<tr>
			<td >
				<a href=\"".esc_url(B_PRODUCTIV_PORTAL_LINK)."/?page=logout\" ><img src=\"".esc_url(B_PRODUCTIV_PLUGIN_PATH)."icons/logout_Lock.png\" alt=\"Logout\" height=\"34\" width=\"34\" style=\"border: 0;\"></a>
			</td>
			<td >
				<a href=\"".esc_url(B_PRODUCTIV_PORTAL_LINK)."/?page=menu\" ><img src=\"".esc_url(B_PRODUCTIV_PLUGIN_PATH)."icons/Home.png\" alt=\"Home\" width=\"36\" height=\"36\" style=\"border: 0;\"></a>	
			</td>
		</tr>
	</table>";
?>