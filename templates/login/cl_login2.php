<?php
	/*-------------------------------------------------------------------------------
	** File Name: 
	**			cl_login2.php
	**
	** File Description:  
	** 			Serves as the body for the login page in the B-Productiv Plugin		
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
	**			insert_login.php
	**			 
	** Accessible By:
	**			Everyone
	**
	** Notes: 
	** 			Copyright (c) C. A. Lettsome Services, LLC. 2018  All rights reserved.
	**			http://www.clydelettsome.com
	*-------------------------------------------------------------------------------*/	
	 
	$display_block ="<form action=\"".esc_url(B_PRODUCTIV_PORTAL_LINK)."/?page=\" method=\"POST\">";
	require_once(B_PRODUCTIV_PLUGIN_PATH.'templates/cl_print_error.php');
	$display_block .="".wp_nonce_field('bproductiv_login_action', 'bproductiv_login_nonce' )."
	<table width=\"50%\" border=\"0\" cellspacing=\"0\" cellpadding=\"5\">
          <tr> 
            <td height=\"200\"> ";
                $display_block.="<p>Username:
                  <input type=\"text\" maxlength=\"16\" size=\"30\" name=\"user\" autocomplete=\"off\" autocapitalize=\"off\">
                </p>
                <p>Password:
                  <input type=\"password\" maxlength=\"60\" size=\"30\" name=\"password\">
                </p>
                <p> 
                  <input type=\"SUBMIT\" name=\"submit\" class=\"button\" value=\"Login\" \">
                </p>
              <p><a href=\"".esc_url(B_PRODUCTIV_PORTAL_LINK)."/?page=reset_password\">Forgot Your Password?</a></p>
              <p>&nbsp; </p>
              </td>
          </tr>

        </table>
        </form>";
?>
