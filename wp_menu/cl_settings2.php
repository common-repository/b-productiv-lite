<?php
	/*-------------------------------------------------------------------------------
	** File Name: 
	**			cl_calendar_main2.php
	**
	** File Description:  
	** 			Serves as the body for the for the Wordpress setting page in the 
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
	**			file_settings.php
	**			 
	** Accessible By:
	**			Super Admin
	**
	** Notes: 
	** 			Copyright (c) C. A. Lettsome Services, LLC. 2018  All rights reserved.
	**			http://www.clydelettsome.com
	*-------------------------------------------------------------------------------*/
	$return_url = ''.B_PRODUCTIV_ADMIN_FINDER.'admin.php?page=b-productiv-plugin';
	
	$escaped_delete_data = esc_html(get_option('bproductiv_delete_data'));
	$escaped_super_user = esc_html(get_option('bproductiv_super_admin'));

	if((ISSET($_POST['submit']))&&(current_user_can('administrator'))&&(($current_username == $escaped_super_user)||($escaped_super_user == "")))
	{
		if( ! empty( $_POST ) && check_admin_referer( 'bproductiv_save_setting_action', 'bproductiv_setting_nonce' ))
		{
			//sanitize the email
			$sanitized_default_email = sanitize_email($_POST['default_email']);
			//validate the email
			if($sanitized_default_email!="" && is_email($_POST['default_email'])) 
			{
				delete_option('bproductiv_default_email');
				add_option( 'bproductiv_default_email',$sanitized_default_email,'','yes' );
			}
			else
			{
				delete_option('bproductiv_default_email');
				$_SESSION['default_email']=$sanitized_default_email;
				//**retrieve error message and error code stating enter default email
				$error_code = 1004;
				$_SESSION['processed']='1';
			}
			
			//sanitize userName
			$sanitized_super_admin = sanitize_user($_POST['super_admin'],0);
			//validate userName
			if (($sanitized_super_admin) && (strlen($sanitized_super_admin) <=60)) 
			{
				delete_option('bproductiv_super_admin');
				add_option( 'bproductiv_super_admin',$sanitized_super_admin,'','yes' );
			}

			//sanitize TimeZone
			$sanitize_time_zone = sanitize_text_field( $_POST['time_zone'] );
			//validate TimeZone
			list($country, $timezone) = split("/", $sanitize_time_zone);
			if (($sanitize_time_zone) && (strlen($sanitize_time_zone) <=40) && ($country)&& ($timezone)) 
			{
				delete_option('bproductiv_time_zone');
				add_option('bproductiv_time_zone',$sanitize_time_zone,'','yes');
			}
			
			//validate date format
			$dateNum_val = intval($_POST['date_formatt']);
			if (($dateNum_val) && ($dateNum_val!="") && (strlen($dateNum_val) <=1)) 
			{
				delete_option('bproductiv_date_format');
				add_option( 'bproductiv_date_format',$dateNum_val,'','yes' );
			}
			
			//validate date format
			$delete_data_val = intval($_POST['delete_data']);
			if (($delete_data_val ==1) && ($delete_data_val) && (strlen($delete_data_val) <=1))
			{
				delete_option('bproductiv_delete_data');
				add_option( 'bproductiv_delete_data','1','','yes' );
			}
			elseif(($delete_data_val=="") && (strlen($delete_data_val) <=1))
			{
				delete_option('bproductiv_delete_data');
				add_option('bproductiv_delete_data','0','','yes');
			} 
			?> 
			
			<meta http-equiv="refresh" content="0; url= <?php echo esc_url("".$return_url."&error_code=".$error_code.""); ?>" />
			
			<?php
		}
		else
		{
			require_once(B_PRODUCTIV_PLUGIN_PATH.'templates/cl_forbidden.php');
		} 
	}
	else
	{		
		//check for errors
/* 		error_reporting(E_ALL);
		ini_set('display_errors', 1); */
		
		if(!ISSET($_SESSION['processed']))
		{
			$_SESSION['processed']="";
		}
		
		$display_block ="
		<h1>B-Productiv - Lite</h1>
		<h3>Settings</h3>";
		include(B_PRODUCTIV_PLUGIN_PATH.'templates/cl_print_error.php');
		$display_block.="<table width=\"100%\" border=\"0\" cellspacing=\"3\" cellpadding=\"3\">
			<tr>";
		 	if ((current_user_can('administrator'))&&(($current_username == $escaped_super_user)||($escaped_super_user == "")))
			{
				//get stored options
				$time_zone = esc_html(get_option('bproductiv_time_zone')); 
				$dateNum = esc_html(get_option('bproductiv_date_format')); 
				
				if($_SESSION['processed']=='1')
				{
					$default_email = $_SESSION['default_email'];
					unset($_SESSION['processed']);
					unset($_SESSION['default_email']);	
				}
				else
				{
					$default_email = esc_html(get_option('bproductiv_default_email')); 
				}
				
				//create and issue the query
				$capabilities = $wpdb->prefix . "capabilities";
				$find = '%administrator%';
				$super_results = $wpdb->get_results( $wpdb->prepare( "SELECT user_id FROM ".$wordpress_usermeta." WHERE meta_value LIKE %s AND meta_key = %s ", $find, $capabilities));
				$display_block.="
				<td>
					<table width=\"75%\" border=\"0\" cellspacing=\"3\" cellpadding=\"3\">
					<form method=\"post\" action=\"".esc_url($return_url)."\" >
						".wp_nonce_field('bproductiv_save_setting_action', 'bproductiv_setting_nonce' )." 
						<tr>
							<td>
								<b>Super Admin <i>(required)</i></b>
							</td>
							<td >";
								$display_block.="<select name=\"super_admin\">";
								$x=0;	

								foreach( $super_results AS $row )
								{
									$escaped_user_id = esc_html($row->user_id);
									$namesql = $wpdb->get_results( $wpdb->prepare( "SELECT display_name, user_login FROM ".$wordpress_users." WHERE ID = %s ", $escaped_user_id));
									foreach( $namesql AS $row )
									{
										$escaped_display_name = esc_html($row->display_name);
										$escaped_username = esc_html($row->user_login);
									}
									$display_block.="\t<option value=\"".$escaped_username."\"";
									if($escaped_username == $escaped_super_user)
									{
										$display_block.="SELECTED";
									}
									$display_block.=">".$escaped_username." \n";
									$x++;

								}
								$display_block.="</select>";
							$display_block.="</td>		
						</tr>
						<tr>
							<td>
								<b>Timezone Settings</b>
							</td>
							<td >";
								$display_block.="<select name=\"time_zone\">";
								$x=0;	 
								$max = sizeof($TimeZone_v);

								while($x<$max)
								{					
									$display_block.="\t<option value=\"".$TimeZone_v[$x]."\"";
									if($TimeZone_v[$x] == $time_zone)
									{
										$display_block.="SELECTED";
									}
									$display_block.=">".$TimeZone_n[$x]." \n";
									$x++;

								}
								$display_block.="</select>";
								$display_block.="<br>Choose a city or country in your timezone.
							</td>	
						</tr>
						<tr>
							<td>
								<b>Default Email Address <i>(required)</i></b>
							</td>
							<td>
								<input type=\"text\" maxlength=\"100\" size=\"50\" name=\"default_email\" value=\"$default_email\" autocomplete=\"off\" autocapitalize=\"off\">
							</td>
						</tr><tr>
							<td>
								<b>Date Display Format </b>
							</td>
							<td>
								<input type=\"radio\" name=\"date_formatt\" value=1 ";if(($dateNum==1) || ($dateNum!=2 && $dateNum!=3)){$display_block.="checked";}$display_block.="><label>MM-DD-YYYY</label> (Default)<br>
								<input type=\"radio\" name=\"date_formatt\" value=2 ";if($dateNum==2){$display_block.="checked";}$display_block.="><label>DD-MM-YYYY</label><br>
								<input type=\"radio\" name=\"date_formatt\" value=3 ";if($dateNum==3){$display_block.="checked";}$display_block.="><label>YYYY-MM-DD</label>
							</td>
						</tr>
						<tr>
							<td>
								<b>Delete Stored Data Upon Plugin Deletion?</b>
							</td>
							<td >
								<input type=\"checkbox\" name=\"delete_data\" value=1 "; if($escaped_delete_data==1){$display_block.="checked";}  $display_block.="> <font style=\"color:red;\">Warning: If selected, all B-Productiv database data will be deleted when the plugin is deleted.</font> 
							</td> 
						</tr>
					</table>
					<input type=\"submit\" name=\"submit\" value=\"Save Settings\" >
					</form>
					<br><br><p<b>Link to the Portal:</b> <a href= \"".esc_url(B_PRODUCTIV_PORTAL_LINK)."\" >".esc_url(B_PRODUCTIV_PORTAL_LINK)."</a></p>
					";
				$display_block.="</td>";	
			}
			else
			{
				$display_block.="<td>";
					$display_block.=" <h3><center>You are not authorized to see this page. Only the Super-Admin can see this page.<center></h3>";
				$display_block.="</td>";	
			}
			$display_block.="
				<td>
					<center><b>Ads and Donations</b></center>
					<a href=\"https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=8L2XXL68B4GEY\" target=\"_blank\" rel=\"nofollow\"><center><img src=\"https://www.paypal.com/en_US/i/btn/x-click-but21.gif\" alt=\"\" /></center></a>
					<a href= \"http://clydelettsome.com/blog/bproductiv-redirect/ \" target=\"_blank\"><center><img src=\"http://clydelettsome.com/blog/images/bproductiv-ad1.png\" alt=\"bproductiv Ad1\" height=\"150\" width=\"150\"></center></a>
					<a href= \"http://clydelettsome.com/blog/bproductiv-redirect2/ \" target=\"_blank\"><center><img src=\"http://clydelettsome.com/blog/images/bproductiv-ad2.png\" alt=\"bproductiv Ad2\" height=\"150\" width=\"150\"></center></a>
				</td>
			</tr>
		</table>";
		$display_block.="
		<br> <br><a href= \"".esc_url(B_PRODUCTIV_PLUGIN_PATH)."/changelog.txt\" target=\"_blank\">Change Log</a> | <a href= \"http://clydelettsome.com/blog/b-productiv-help/\" target=\"_blank\">Help</a>";  
	}
?>