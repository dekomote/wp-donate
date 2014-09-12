<?php

function wp_donate_options_page() 
{ global $wpdb;
    ?>
	<?php 
	if($_GET['id']!='' && $_GET['action']=='delete')
	{ 
		$table_name = $wpdb->prefix."donate";
		$wpdb -> delete($table_name, array("id" => $_GET['id']));
		header("Location:".site_url().'/wp-admin/admin.php?page=wp_donate');
		exit;
	}	
	elseif($_GET['id']!='')
	{
	?>
		<h1 class="donate-title">WP Donate</h1>
		  <p>Fork of the wp-donate plugin that does much more and much better. Use the shortcode [donate_form] to display
		  a donate form on your page. You can pass an optional argument form_id which is used in Donation Types and to keep
		  track of which form got the donation. Also, the shortcode can be used with a content [donate_form]field_template[/donate_form].
		  <b>field_template</b> should be a template with input fields and at bare minimum should have fields named: first_name, last_name, 
		  email, amount, card_type, x_card_num, exp_month, exp_year and x_card_code. Submit button not included. The form tag and the hidden inputs
		  needed for transaction and tracking are included. You just need to add labels and inputs. Use <b>[donate_response]</b> on the success and failure pages.</p>
		<div id="wp-donate-tabs">
		  <div id="wp-donate-tab-donorlist">
			<table class="wp-donate-donorlist" width="50%">
			  <thead>					 
				  <tr>
					<?php global $wpdb;
					$myrows = $wpdb->get_results( "SELECT * FROM ".$wpdb->prefix."donate where id='".$_GET['id']."'" );						
					foreach($myrows as $myrows_value)
					{
					?>
					<tr>
						<td width="125"><b>First Name :</b></td>
						<td><?php echo $myrows_value->first_name;?></td>
					</tr>
					<tr>
						<td><b>Last Name :</b></td>							
						<td><?php echo $myrows_value->last_name;?></td>
					</tr>
					<tr>
						<td><b>Organization :</b></td>							
						<td><?php echo $myrows_value->organization;?></td>
					</tr>
					<tr>
						<td><b>Address :</b></td>							
						<td><?php echo $myrows_value->address;?></td>
					</tr>
					<tr>
						<td><b>City :</b></td>							
						<td><?php echo $myrows_value->city;?></td>
					</tr>
					<tr>
						<td><b>Country :</b></td>							
						<td><?php echo $myrows_value->country;?></td>
					</tr>
					<tr>
						<td><b>State :</b></td>							
						<td><?php echo $myrows_value->state;?></td>
					</tr>
					<tr>
						<td><b>Zip : </b></td>							
						<td><?php echo $myrows_value->zip;?></td>
					</tr>
					<tr>
						<td><b>Phone :</b></td>							
						<td><?php echo $myrows_value->phone;?></td>
					</tr>
					<tr>
						<td><b>Email :</b></td>							
						<td><?php echo $myrows_value->email;?></td>
					</tr>
					<tr>
						<td><b>Donation Type :</b></td>							
						<td><?php echo $myrows_value->donation_type;?></td>
					</tr>
					<tr>
						<td><b>Form ID :</b></td>							
						<td><?php echo $myrows_value->form_id;?></td>
					</tr>
					<tr>
						<td><b>Amount :</b></td>							
						<td>$<?php echo $myrows_value->amount;?></td>
					</tr>
					<tr>
						<td><b>Comment :</b></td>							
						<td><?php echo $myrows_value->comment;?></td>
					</tr>
					
					<tr>
						<td><b>Date :</b></td>							
						<td align="left"><?php echo $myrows_value->date;?></td>
					</tr>
					<tr>	
						<td><b>Action : </b></td>
						<td align="left"><?php if($myrows_value->status==1){echo "Complete";} else {echo "Pending";}?></td>
					</tr>
					<?php } ?>
					<tr>
						<td><input type="button" onclick=location.href='<?php echo site_url();?>/wp-admin/admin.php?page=wp_donate' value="Back" /></td>
					</tr>
				  </tr>
			  </thead>
			</table>
		</div>
		</div>
	<?php
	}
	else
	{
	?>
    <script type="text/javascript">
        jQuery(function() {
            jQuery("#wp-donate-tabs").tabs();
        });
    </script>

    <div id="wp-donate-tabs">
        <h1 class="donate-title">WP Donate</h1>
		  <p>Fork of the wp-donate plugin that does much more and much better. Use the shortcode [donate_form] to display
		  a donate form on your page. You can pass an optional argument form_id which is used in Donation Types and to keep
		  track of which form got the donation. Also, the shortcode can be used with a content [donate_form]field_template[/donate_form].
		  <b>field_template</b> should be a template with input fields and at bare minimum should have fields named: first_name, last_name, 
		  email, amount, card_type, x_card_num, exp_month, exp_year and x_card_code. Submit button not included. The form tag and the hidden inputs
		  needed for transaction and tracking are included. You just need to add labels and inputs. Use <b>[donate_response]</b> on the success and failure pages.</p>
        <ul id="wp-donate-tabs-nav">
            <li><a href="#wp-donate-tab-donorlist">Donor List</a></li>
            <li><a href="#wp-donate-tab-settings">Settings</a></li>
        </ul>
        <div style="clear:both"></div>
        <div id="wp-donate-tab-donorlist">
            <table class="wp-donate-donorlist" width="100%">
              <?php 
              global $wpdb;
				$myrows = $wpdb->get_results( "SELECT * FROM ".$wpdb->prefix."donate");
				if(count($myrows)>0)
				{
              ?>
              <thead>
				  <tr class="wp-donate-absolute">
                  <th align="left">Person</th>
                  <th align="left">Email</th>
                  <th align="left">Amount</th>
                  <th align="left">Date</th>
                  <th align="left">Comment</th>
                  <th align="left">Action</th>
              </tr>
				<?php 
					foreach($myrows as $myrows_value)
					{
					?>
					<tr>
						<td><a href="<?php echo site_url();?>/wp-admin/admin.php?page=wp_donate&id=<?php echo $myrows_value->id;?>"><?php echo $myrows_value->first_name.' '.$myrows_value->last_name ;?></a></td>
						<td><?php echo $myrows_value->email;?></td>
						<td>$<?php echo $myrows_value->amount;?></td>
						<td><?php echo $myrows_value->date;?></td>
						<td><?php echo $myrows_value->comment;?></td>
						<td><a onclick="return confirm('Are you sure?')" href="<?php echo site_url();?>/wp-admin/admin.php?page=wp_donate&action=delete&id=<?php echo $myrows_value->id;?>">Delete</a></td>
					</tr>
					<?php 
					} 
					?>
              </thead>
              <?php 
				}
				else
				{
				echo "No Record's Found.";	
				}
              ?>
            </table>
        </div>
        <div id="wp-donate-tab-settings">			
			<?php
			global $wpdb;
			$mysetting = $wpdb->get_results( "SELECT * FROM ".$wpdb->prefix."donate_settings" );						
			?>
			<form action="<?php echo site_url();?>/wp-admin/admin.php?page=wp_donate" method="post" name="setting" id="setting">
				<input type="hidden" name="setting" value="1" />
				<table class="form-table">
					<tr>
						<th><label>Authorize.net Mode:</label></th>
						<td><select name="authnet_mode" id="paramsauthnet_mode">
							<option value="0" <?php if($mysetting[0]->mod==0){?> selected="selected" <?php }?> >Test Mode</option>
							<option <?php if($mysetting[0]->mod==1){?> selected="selected" <?php }?> value="1">Live Mode</option>
						</select></td>
					</tr>
					<tr>
						<th><label>API Login:</label></th>
						<td><input type="text" value="<?php echo $mysetting[0]->api_login;?>" id="paramsx_login" name="x_login"></td>
					</tr>
					<tr>
						<th><label>Transaction Key:</label></th>
						<td><input type="text" value="<?php echo $mysetting[0]->key;?>" id="paramsx_tran_key" name="x_tran_key"></td>
					</tr>
					<tr>
						<th><label>Success Url:</label></th>
						<td><input type="text" value="<?php echo $mysetting[0]->success_url;?>" id="paramsx_success_url" name="success_url"></td>
					</tr>
					<tr>
						<th><label>Fail Url:</label></th>
						<td><input type="text" value="<?php echo $mysetting[0]->fail_url;?>" id="paramsx_fail_url" name="fail_url"></td>
					</tr>
					<tr>
						<th></th>
						<td><input type="submit" value="Submit" /></td>
					</tr>
				</table>
				</ul>
            </form>
        </div>
        
    </div>
<?php
	}
}



?>
