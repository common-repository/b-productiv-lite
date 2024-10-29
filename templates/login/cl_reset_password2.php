<?php
	/*-------------------------------------------------------------------------------
	** File Name: 
	**			cl_reset_password2.php
	**
	** File Description:  
	** 			Serves as the body for the password reset page in the 		
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
	**			insert_reset.php
	**			 
	** Accessible By:
	**			Everyone
	**
	** Notes: 
	** 			Copyright (c) C. A. Lettsome Services, LLC. 2018  All rights reserved.
	**			http://www.clydelettsome.com
	*-------------------------------------------------------------------------------*/

	$display_block="
	<h1>Reset Password</h1>";
	require_once(B_PRODUCTIV_PLUGIN_PATH.'templates/cl_print_error.php');   

	$display_block.="<table border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
		<tr>
			<td> 
				<form action=\"".esc_url(B_PRODUCTIV_PORTAL_LINK)."/?page=reset_password\" enctype=\"multipart/form-data\" method=\"POST\">
				".wp_nonce_field('bproductiv_reset_action', 'bproductiv_reset_nonce' )."				
				Enter Your Username: <br>
				<input type=\"hidden\" name=\"action\" value=\"update\"><input type=\"text\" maxlength=\"16\" size=\"30\" name=\"username\" value=\"\" autocomplete=\"off\" autocapitalize=\"off\">
			 
				<p><font color=\"#FF0000\" style=\"font-size:13px;\" face=\"Arial, Helvetica, sans-serif\">
				<br>
				After submitting, please check your email account for your temporary password.</font></p>
				  <input type=\"submit\" name=\"submit\" value=\"Submit\" style=\"font-size:13px; \">
				<input type=\"reset\" name=\"Submit2\" value=\"Reset\" style=\"font-size:13px; \">
				</form>
			</td>
		</tr>	
	</table>
	";
?>