<?php ob_start();
/**
 * Display Donate Form
 *
 * @return string Donate Form
 *
 * @since 1.0
 *
*/

global $wpdb;
if(isset($_POST['action']))
{
    if($_POST['action']=='submitdonate')
    {
		$first_name = $_POST['first_name'];
		$last_name = $_POST['last_name'];
		$organization = $_POST['organization'];
		$address = $_POST['address'];
		$city = $_POST['city'];
		$country = $_POST['country'];
		$state = $_POST['state'];
		$zip = $_POST['zip'];
		$phone = $_POST['phone'];
		$email = $_POST['email'];
		$donation_type = $_POST['donation_type'];
		$r_frequency = $_POST['r_frequency'];
		$r_times = $_POST['r_times'];
		$amount = $_POST['amount'];
		$card_type = $_POST['card_type'];
		$x_card_num = $_POST['x_card_num'];
		$exp_month = $_POST['exp_month'];
		$exp_year = $_POST['exp_year'];
		$x_card_code = $_POST['x_card_code'];
		$comment = $_POST['comment'];
		$form_id = $_POST['form_id'];
		$payment_method = $_POST['payment_method'];
		
		include dirname(__FILE__) .'/../anet_php_sdk/AuthorizeNet.php';

		$mysetting = $wpdb->get_results( "SELECT * FROM ".$wpdb->prefix."donate_settings" );
		define("AUTHORIZENET_API_LOGIN_ID", $mysetting[0] -> api_login);
		define("AUTHORIZENET_TRANSACTION_KEY",$mysetting[0] -> key);
		
		if($mysetting[0] -> mod == 0){
			define("AUTHORIZENET_SANDBOX",true);
			define("TEST_REQUEST", true);
		}
		else
		{
			define("AUTHORIZENET_SANDBOX",false);
			define("TEST_REQUEST", false);  
		}

		$REQUEST = $_POST;

		if( authorizepayment($REQUEST) )
		{
			$table_name = $wpdb->prefix."donate";
			$wpdb -> insert($table_name, array(
					'first_name' => $first_name,
					'last_name' => $last_name,
					'organization' => $organization,
					'address' => $address,
					'city' => $city,
					'country' => $country,
					'state' => $state,
					'zip' => $zip,
					'phone' => $phone,
					'email' => $email,
					'donation_type' => $donation_type,
					'amount' => $amount,
					'comment' => $comment,
					'status' => $status,
					'date' => date("Y-m-d H:i:s"),
					'form_id' => $form_id,
				));
			header("Location:".$mysetting[0]->success_url);
			exit;
		}
		else
		{
			header("Location:".$mysetting[0]->fail_url);
			exit;
		}
		
	}
}

function wp_donate_form($atts, $content = null) {
	$attribs = shortcode_atts( array(
        'form_id' => "Donate Form at: ".get_site_url().$_SERVER['REQUEST_URI'],
    ), $atts );
	global $wpdb;

	wp_enqueue_script('jquery');
	if($content){
		ob_start();
?>
	<form method="post" class="wpd_donate_form" name="wpd_donate_form" id="wpd_donate_form_<?php echo sanitize_title($attribs['form_id']);?>" action="<?php echo site_url().$_SERVER['REQUEST_URI'];?>">
		<input type="hidden" name="action" value="submitdonate" />
		<input type="hidden" name="form_id" value="<?php echo $attribs['form_id'];?>" />
		<input type="hidden" name="payment_method" value="os_authnet" />
		<?php echo $content;?>
	</form>
<?php
	$output = apply_filters( 'wp_donate_filter_form', ob_get_contents());
	ob_end_clean();
	}
	else{
		ob_start();
?>
		<form method="post" class="wpd_donate_form" name="wpd_donate_form" id="wpd_donate_form_<?php echo sanitize_title($attribs['form_id']);?>" action="<?php echo site_url().$_SERVER['REQUEST_URI'];?>">
			<input type="hidden" name="action" value="submitdonate" />
			<input type="hidden" name="form_id" value="<?php echo $attribs['form_id'];?>" />
			<table width="100%">
				<tr>
					<td class="title_cell">First name<span class="required">*</span></td>
					<td class="field_cell">
						<input type="text" class="inputbox" name="first_name" id="first_name" value="" />
					</td>
				</tr>
				<tr>			
					<td class="title_cell">Last name<span class="required">*</span></td>
					<td class="field_cell">
						<input type="text" class="inputbox" name="last_name" value="" />
					</td>
				</tr>
				<tr>
					<td class="title_cell">Email<span class="required">*</span></td>
					<td class="field_cell">
						<input type="text" class="inputbox" name="email" value="" />
					</td>
				</tr>
				<tr>
					<td class="title_cell">Organization</td>
					<td class="field_cell">
						<input type="text" class="inputbox" name="organization" value=""/>
					</td>
				</tr>
				<tr>			
					<td class="title_cell">Address</td>
					<td class="field_cell">
						<input type="text" class="inputbox" name="address" value=""/>
					</td>
				</tr>	
				<tr>			
					<td class="title_cell">City</td>
					<td class="field_cell">
						<input type="text" class="inputbox" name="city" value=""/>
					</td>
				</tr>		
				<tr>			
					<td class="title_cell">Country</td>
					<td class="field_cell">
						<select id="country" name="country" onchange="updateStateList();" >
							<option value="">Select country</option>
							<option value="Afghanistan">Afghanistan</option>
							<option value="Albania">Albania</option>
							<option value="Algeria">Algeria</option>
							<option value="American Samoa">American Samoa</option>
							<option value="Andorra">Andorra</option>
							<option value="Angola">Angola</option>
							<option value="Anguilla">Anguilla</option>
							<option value="Antarctica">Antarctica</option>
							<option value="Antigua and Barbuda">Antigua and Barbuda</option>
							<option value="Argentina">Argentina</option>
							<option value="Armenia">Armenia</option>
							<option value="Aruba">Aruba</option>
							<option value="Australia">Australia</option>
							<option value="Austria">Austria</option>
							<option value="Azerbaijan">Azerbaijan</option>
							<option value="Bahamas">Bahamas</option>
							<option value="Bahrain">Bahrain</option>
							<option value="Bangladesh">Bangladesh</option>
							<option value="Barbados">Barbados</option>
							<option value="Belarus">Belarus</option>
							<option value="Belgium">Belgium</option>
							<option value="Belize">Belize</option>
							<option value="Benin">Benin</option>
							<option value="Bermuda">Bermuda</option>
							<option value="Bhutan">Bhutan</option>
							<option value="Bolivia">Bolivia</option>
							<option value="Bosnia and Herzegowina">Bosnia and Herzegowina</option>
							<option value="Botswana">Botswana</option>
							<option value="Bouvet Island">Bouvet Island</option>
							<option value="Brazil">Brazil</option>
							<option value="British Indian Ocean Territory">British Indian Ocean Territory</option>
							<option value="Brunei Darussalam">Brunei Darussalam</option>
							<option value="Bulgaria">Bulgaria</option>
							<option value="Burkina Faso">Burkina Faso</option>
							<option value="Burundi">Burundi</option>
							<option value="Cambodia">Cambodia</option>
							<option value="Cameroon">Cameroon</option>
							<option value="Canada">Canada</option>
							<option value="Canary Islands">Canary Islands</option>
							<option value="Cape Verde">Cape Verde</option>
							<option value="Cayman Islands">Cayman Islands</option>
							<option value="Central African Republic">Central African Republic</option>
							<option value="Chad">Chad</option>
							<option value="Chile">Chile</option>
							<option value="China">China</option>
							<option value="Christmas Island">Christmas Island</option>
							<option value="Cocos (Keeling) Islands">Cocos (Keeling) Islands</option>
							<option value="Colombia">Colombia</option>
							<option value="Comoros">Comoros</option>
							<option value="Congo">Congo</option>
							<option value="Cook Islands">Cook Islands</option>
							<option value="Costa Rica">Costa Rica</option>
							<option value="Cote D'Ivoire">Cote D'Ivoire</option>
							<option value="Croatia">Croatia</option>
							<option value="Cuba">Cuba</option>
							<option value="Cyprus">Cyprus</option>
							<option value="Czech Republic">Czech Republic</option>
							<option value="Denmark">Denmark</option>
							<option value="Djibouti">Djibouti</option>
							<option value="Dominica">Dominica</option>
							<option value="Dominican Republic">Dominican Republic</option>
							<option value="East Timor">East Timor</option>
							<option value="East Timor">East Timor</option>
							<option value="Ecuador">Ecuador</option>
							<option value="Egypt">Egypt</option>
							<option value="El Salvador">El Salvador</option>
							<option value="Equatorial Guinea">Equatorial Guinea</option>
							<option value="Eritrea">Eritrea</option>
							<option value="Estonia">Estonia</option>
							<option value="Ethiopia">Ethiopia</option>
							<option value="Falkland Islands (Malvinas)">Falkland Islands (Malvinas)</option>
							<option value="Faroe Islands">Faroe Islands</option>
							<option value="Fiji">Fiji</option>
							<option value="Finland">Finland</option>
							<option value="France">France</option>
							<option value="France, Metropolitan">France, Metropolitan</option>
							<option value="French Guiana">French Guiana</option>
							<option value="French Polynesia">French Polynesia</option>
							<option value="French Southern Territories">French Southern Territories</option>
							<option value="Gabon">Gabon</option>
							<option value="Gambia">Gambia</option>
							<option value="Georgia">Georgia</option>
							<option value="Germany">Germany</option>
							<option value="Ghana">Ghana</option>
							<option value="Gibraltar">Gibraltar</option>
							<option value="Greece">Greece</option>
							<option value="Greenland">Greenland</option>
							<option value="Grenada">Grenada</option>
							<option value="Guadeloupe">Guadeloupe</option>
							<option value="Guam">Guam</option>
							<option value="Guatemala">Guatemala</option>
							<option value="Guinea">Guinea</option>
							<option value="Guinea-bissau">Guinea-bissau</option>
							<option value="Guyana">Guyana</option>
							<option value="Haiti">Haiti</option>
							<option value="Heard and Mc Donald Islands">Heard and Mc Donald Islands</option>
							<option value="Honduras">Honduras</option>
							<option value="Hong Kong">Hong Kong</option>
							<option value="Hungary">Hungary</option>
							<option value="Iceland">Iceland</option>
							<option value="India">India</option>
							<option value="Indonesia">Indonesia</option>
							<option value="Iran (Islamic Republic of)">Iran (Islamic Republic of)</option>
							<option value="Iraq">Iraq</option>
							<option value="Ireland">Ireland</option>
							<option value="Israel">Israel</option>
							<option value="Italy">Italy</option>
							<option value="Jamaica">Jamaica</option>
							<option value="Japan">Japan</option>
							<option value="Jersey">Jersey</option>
							<option value="Jordan">Jordan</option>
							<option value="Kazakhstan">Kazakhstan</option>
							<option value="Kenya">Kenya</option>
							<option value="Kiribati">Kiribati</option>
							<option value="Korea, Democratic People's Republic of">Korea, Democratic People's Republic of</option>
							<option value="Korea, Republic of">Korea, Republic of</option>
							<option value="Kuwait">Kuwait</option>
							<option value="Kyrgyzstan">Kyrgyzstan</option>
							<option value="Lao People's Democratic Republic">Lao People's Democratic Republic</option>
							<option value="Latvia">Latvia</option>
							<option value="Lebanon">Lebanon</option>
							<option value="Lesotho">Lesotho</option>
							<option value="Liberia">Liberia</option>
							<option value="Libyan Arab Jamahiriya">Libyan Arab Jamahiriya</option>
							<option value="Liechtenstein">Liechtenstein</option>
							<option value="Lithuania">Lithuania</option>
							<option value="Luxembourg">Luxembourg</option>
							<option value="Macau">Macau</option>
							<option value="Macedonia, The Former Yugoslav Republic of">Macedonia, The Former Yugoslav Republic of</option>
							<option value="Madagascar">Madagascar</option>
							<option value="Malawi">Malawi</option>
							<option value="Malaysia">Malaysia</option>
							<option value="Maldives">Maldives</option>
							<option value="Mali">Mali</option>
							<option value="Malta">Malta</option>
							<option value="Marshall Islands">Marshall Islands</option>
							<option value="Martinique">Martinique</option>
							<option value="Mauritania">Mauritania</option>
							<option value="Mauritius">Mauritius</option>
							<option value="Mayotte">Mayotte</option>
							<option value="Mexico">Mexico</option>
							<option value="Micronesia, Federated States of">Micronesia, Federated States of</option>
							<option value="Moldova, Republic of">Moldova, Republic of</option>
							<option value="Monaco">Monaco</option>
							<option value="Mongolia">Mongolia</option>
							<option value="Montenegro">Montenegro</option>
							<option value="Montserrat">Montserrat</option>
							<option value="Morocco">Morocco</option>
							<option value="Mozambique">Mozambique</option>
							<option value="Myanmar">Myanmar</option>
							<option value="Namibia">Namibia</option>
							<option value="Nauru">Nauru</option>
							<option value="Nepal">Nepal</option>
							<option value="Netherlands">Netherlands</option>
							<option value="Netherlands Antilles">Netherlands Antilles</option>
							<option value="New Caledonia">New Caledonia</option>
							<option value="New Zealand">New Zealand</option>
							<option value="Nicaragua">Nicaragua</option>
							<option value="Niger">Niger</option>
							<option value="Nigeria">Nigeria</option>
							<option value="Niue">Niue</option>
							<option value="Norfolk Island">Norfolk Island</option>
							<option value="Northern Mariana Islands">Northern Mariana Islands</option>
							<option value="Norway">Norway</option>
							<option value="Oman">Oman</option>
							<option value="Pakistan">Pakistan</option>
							<option value="Palau">Palau</option>
							<option value="Panama">Panama</option>
							<option value="Papua New Guinea">Papua New Guinea</option>
							<option value="Paraguay">Paraguay</option>
							<option value="Peru">Peru</option>
							<option value="Philippines">Philippines</option>
							<option value="Pitcairn">Pitcairn</option>
							<option value="Poland">Poland</option>
							<option value="Portugal">Portugal</option>
							<option value="Puerto Rico">Puerto Rico</option>
							<option value="Qatar">Qatar</option>
							<option value="Reunion">Reunion</option>
							<option value="Romania">Romania</option>
							<option value="Russian Federation">Russian Federation</option>
							<option value="Rwanda">Rwanda</option>
							<option value="Saint Kitts and Nevis">Saint Kitts and Nevis</option>
							<option value="Saint Lucia">Saint Lucia</option>
							<option value="Saint Vincent and the Grenadines">Saint Vincent and the Grenadines</option>
							<option value="Samoa">Samoa</option>
							<option value="San Marino">San Marino</option>
							<option value="Sao Tome and Principe">Sao Tome and Principe</option>
							<option value="Saudi Arabia">Saudi Arabia</option>
							<option value="Senegal">Senegal</option>
							<option value="Serbia">Serbia</option>
							<option value="Seychelles">Seychelles</option>
							<option value="Sierra Leone">Sierra Leone</option>
							<option value="Singapore">Singapore</option>
							<option value="Slovakia (Slovak Republic)">Slovakia (Slovak Republic)</option>
							<option value="Slovenia">Slovenia</option>
							<option value="Solomon Islands">Solomon Islands</option>
							<option value="Somalia">Somalia</option>
							<option value="South Africa">South Africa</option>
							<option value="South Georgia and the South Sandwich Islands">South Georgia and the South Sandwich Islands</option>
							<option value="Spain">Spain</option>
							<option value="Sri Lanka">Sri Lanka</option>
							<option value="St. Barthelemy">St. Barthelemy</option>
							<option value="St. Eustatius">St. Eustatius</option>
							<option value="St. Helena">St. Helena</option>
							<option value="St. Pierre and Miquelon">St. Pierre and Miquelon</option>
							<option value="Sudan">Sudan</option>
							<option value="Suriname">Suriname</option>
							<option value="Svalbard and Jan Mayen Islands">Svalbard and Jan Mayen Islands</option>
							<option value="Swaziland">Swaziland</option>
							<option value="Sweden">Sweden</option>
							<option value="Switzerland">Switzerland</option>
							<option value="Syrian Arab Republic">Syrian Arab Republic</option>
							<option value="Taiwan">Taiwan</option>
							<option value="Tajikistan">Tajikistan</option>
							<option value="Tanzania, United Republic of">Tanzania, United Republic of</option>
							<option value="Thailand">Thailand</option>
							<option value="The Democratic Republic of Congo">The Democratic Republic of Congo</option>
							<option value="Togo">Togo</option>
							<option value="Tokelau">Tokelau</option>
							<option value="Tonga">Tonga</option>
							<option value="Trinidad and Tobago">Trinidad and Tobago</option>
							<option value="Tunisia">Tunisia</option>
							<option value="Turkey">Turkey</option>
							<option value="Turkmenistan">Turkmenistan</option>
							<option value="Turks and Caicos Islands">Turks and Caicos Islands</option>
							<option value="Tuvalu">Tuvalu</option>
							<option value="Uganda">Uganda</option>
							<option value="Ukraine">Ukraine</option>
							<option value="United Arab Emirates">United Arab Emirates</option>
							<option value="United Kingdom">United Kingdom</option>
							<option value="United States" selected="selected">United States</option>
							<option value="United States Minor Outlying Islands">United States Minor Outlying Islands</option>
							<option value="Uruguay">Uruguay</option>
							<option value="Uzbekistan">Uzbekistan</option>
							<option value="Vanuatu">Vanuatu</option>
							<option value="Vatican City State (Holy See)">Vatican City State (Holy See)</option>
							<option value="Venezuela">Venezuela</option>
							<option value="Viet Nam">Viet Nam</option>
							<option value="Virgin Islands (British)">Virgin Islands (British)</option>
							<option value="Virgin Islands (U.S.)">Virgin Islands (U.S.)</option>
							<option value="Wallis and Futuna Islands">Wallis and Futuna Islands</option>
							<option value="Western Sahara">Western Sahara</option>
							<option value="Yemen">Yemen</option>
							<option value="Zambia">Zambia</option>
							<option value="Zimbabwe">Zimbabwe</option>
						</select>
					</td>
				</tr>	
				<tr>			
					<td class="title_cell">State</td>
					<td class="field_cell">
						<select id="state" name="state" class="">
							<option value="" selected="selected">Select State</option>
							<option value="AK">Alaska</option>
							<option value="AL">Alabama</option>
							<option value="AR">Arkansas</option>
							<option value="AZ">Arizona</option>
							<option value="CA">California</option>
							<option value="CO">Colorado</option>
							<option value="CT">Connecticut</option>
							<option value="DC">District Of Columbia</option>
							<option value="DE">Delaware</option>
							<option value="FL">Florida</option>
							<option value="GA">Georgia</option>
							<option value="HI">Hawaii</option>
							<option value="IA">Iowa</option>
							<option value="ID">Idaho</option>
							<option value="IL">Illinois</option>
							<option value="IN">Indiana</option>
							<option value="KS">Kansas</option>
							<option value="KY">Kentucky</option>
							<option value="LA">Louisiana</option>
							<option value="MA">Massachusetts</option>
							<option value="MD">Maryland</option>
							<option value="ME">Maine</option>
							<option value="MI">Michigan</option>
							<option value="MN">Minnesota</option>
							<option value="MO">Missouri</option>
							<option value="MS">Mississippi</option>
							<option value="MT">Montana</option>
							<option value="NC">North Carolina</option>
							<option value="ND">North Dakota</option>
							<option value="NE">Nebraska</option>
							<option value="NH">New Hampshire</option>
							<option value="NJ">New Jersey</option>
							<option value="NM">New Mexico</option>
							<option value="NV">Nevada</option>
							<option value="NY">New York</option>
							<option value="OH">Ohio</option>
							<option value="OK">Oklahoma</option>
							<option value="OR">Oregon</option>
							<option value="PA">Pennsylvania</option>
							<option value="RI">Rhode Island</option>
							<option value="SC">South Carolina</option>
							<option value="SD">South Dakota</option>
							<option value="TN">Tennessee</option>
							<option value="TX">Texas</option>
							<option value="UT">Utah</option>
							<option value="VA">Virginia</option>
							<option value="VT">Vermont</option>
							<option value="WA">Washington</option>
							<option value="WI">Wisconsin</option>
							<option value="WV">West Virginia</option>
							<option value="WY">Wyoming</option>
						</select>
					</td>
				</tr>
				<tr>
					<td class="title_cell">Zip</td>
					<td class="field_cell">
						<input type="text" class="inputbox" name="zip" value=""/>
					</td>
				</tr>
				<tr>
					<td class="title_cell">Phone</td>
					<td class="field_cell">
						<input type="text" class="inputbox" name="phone" value=""/>
					</td>
				</tr>
				
				<tr>
					<td colspan="2" class="heading"><hr/></td>
				</tr>
				<?php
					$the_query = new WP_Query( array('meta_key' => 'form_id', 'meta_value' => $attribs["form_id"], 'post_type' => 'donationtype') );
					if($the_query->have_posts()){
						echo '<tr><td class="title_cell">Donation Type</td><td class="field_cell">';
						echo '<select name="donation_type">';
						echo '<option value="Custom" data-amount="0">Custom</option>';
						while($the_query -> have_posts()): $the_query -> the_post();?>
							<option value="<?php echo the_title();?>" data-amount="<?php echo get_post_meta(get_the_ID(), "amount", true)?>"><?php echo the_title();?></option>
						<?php endwhile; wp_reset_postdata();?>
						</select>
						<script type="text/javascript">
							jQuery(document).ready(function(){
								jQuery("form.wpd_donate_form select[name=donation_type]").change(function(){
									var that = this;
									jQuery(that).parents("form.wpd_donate_form").find("input[name=amount]").attr("readonly", "readonly").val(jQuery(that).find("option:selected").data("amount"));
									if(jQuery(that).val() == "Custom"){
										jQuery(that).parents("form.wpd_donate_form").find("input[name=amount]").attr("readonly", false);
									}
								}).change();
							});
						</script>
						</td></tr><?php 
					}
				?>
				<tr>
					<td class="title_cell" valign="top">Amount <span style="float:right">$</span></td>
					<td id="amount_container">
						<input type="text" class="inputbox" name="amount" value=""/>
					</td>
				</tr>		
				<tr id="tr_card_type">
					<td class="title_cell">Card type<span class="required">*</span></td>
					<td class="field_cell">
						<select id="card_type" name="card_type" class="inputbox" >
							<option value="Visa">Visa</option>
							<option value="MasterCard">MasterCard</option>
							<option value="Discover">Discover</option>
							<option value="Amex">American Express</option>
						</select>
					</td>
				</tr>
				<tr id="tr_card_number" >
					<td class="title_cell">Credit Card Number<span class="required">*</span></td>
					<td class="field_cell">
						<input type="text" name="x_card_num" class="inputbox" onkeyup="checkNumber(this)" value=""/>
					</td>
				</tr>
				<tr id="tr_exp_date" >
					<td class="title_cell">Expiration Date<span class="required">*</span>
					</td>
					<td class="field_cell">					
						<select name="exp_month" class="inputbox exp_month" >
							<option value="1" <?php if(date('m')=='01'){?>  selected="selected"<?php }?>>01</option>
							<option value="2" <?php if(date('m')=='02'){?>  selected="selected"<?php }?>>02</option>
							<option value="3" <?php if(date('m')=='03'){?>  selected="selected"<?php }?>>03</option>
							<option value="4" <?php if(date('m')=='04'){?>  selected="selected"<?php }?>>04</option>
							<option value="5" <?php if(date('m')=='05'){?>  selected="selected"<?php }?>>05</option>
							<option value="6" <?php if(date('m')=='06'){?>  selected="selected"<?php }?>>06</option>
							<option value="7" <?php if(date('m')=='07'){?>  selected="selected"<?php }?>>07</option>
							<option value="8" <?php if(date('m')=='08'){?>  selected="selected"<?php }?>>08</option>
							<option value="9" <?php if(date('m')=='09'){?>  selected="selected"<?php }?>>09</option>
							<option value="10" <?php if(date('m')=='10'){?>  selected="selected"<?php }?>>10</option>
							<option value="11" <?php if(date('m')=='11'){?>  selected="selected"<?php }?>>11</option>
							<option value="12" <?php if(date('m')=='12'){?>  selected="selected"<?php }?>>12</option>
						</select> / 
						<select id="exp_year" name="exp_year" class="inputbox exp_month" >
							<?php $year = date(Y,time()); $num = 1;
								while ( $num <= 7 ) {
									echo '<option value="' . $year .'">' . $year . '</option>';$year++; $num++;
								}
							?>
						</select>
					</td>
				</tr>
				<tr id="tr_cvv_code" >
					<td class="title_cell">Card (CVV) Code<span class="required">*</span></td>
					<td class="field_cell">
						<input type="text" name="x_card_code" class="inputbox" onKeyUp="checkNumber(this)" value=""/>
					</td>
				</tr>
				<tr>
					<td class="title_cell">Comment</td>
					<td class="field_cell">
						<textarea rows="7" cols="30" name="comment" class="inputbox"></textarea>
					</td>
				</tr>
				<tr>
					<td colspan="2" align="right">
						<input type="button" class="button donate_btn_submit" name="btnSubmit" value="Donate Now" onclick="checkData();">
					</td>
				</tr>
			</table>
			<input type="hidden" name="payment_method" value="os_authnet" /><br/>
			</form>
			<script type="text/javascript">

				var currentCampaign = 0 ;
				function checkData() {
					var form = document.wpd_donate_form;
					var minimumAmount = 1 ;
					var maximumAmount = 100000 ;
								
					if (form.first_name.value == '') {
						alert("Please enter your first name");
						form.first_name.focus();
						return ;
					}						
					if (form.last_name.value=="") {
						alert("Please enter your last name");
						form.last_name.focus();
						return;
					}
					if (form.email.value == '') {
						alert("Please enter your email");
						form.email.focus();
						return;
					}
								
					var emailFilter = /^\w+[\+\.\w-]*@([\w-]+\.)*\w+[\w-]*\.([a-z]{2,4}|\d+)$/i
					var ret = emailFilter.test(form.email.value);
					if (!ret) {
						alert("Please enter a valid email");
						form.email.focus();
						return;
					}									
					var amountValid = false ;
					var amount = 0 ;
					if (form.rd_amount) {
					if (form.rd_amount.length) {
							for (var i = 0 ; i < form.rd_amount.length ; i++) {
								if(form.rd_amount[i].checked == true) {
									amountValid = true ;
									amount = form.rd_amount[i].value ;
								}	
							}	
						} else if (form.rd_amount.checked == true) {
							amountValid = true ;
							amount = form.rd_amount.value ;
						}
															
					}

					if (!amountValid) {							
						if (parseFloat(form.amount.value)) {
							amountValid = true;
							amount = form.amount.value ;	
						}				
					}		
										
														
					if (!amountValid) {
						var msg;
							msg = "Please choose from pre-defined amounts or enter your own amount in the textbox";
						alert(msg);
						return;	
					}			


					if (parseFloat(amount) < minimumAmount) {
						alert("Minimum donation amount allowed is : $" + minimumAmount);
						form.amount.focus();
						form.amount.focus();
						return ;
					}

					if ((maximumAmount >0) && (parseFloat(amount) > maximumAmount)) {
						alert("Maximum donation amount allowed is : $" + maximumAmount);
						form.amount.focus();
						return ;
					}

					var paymentMethod = "";
					paymentMethod = "os_authnet";

					if (form.x_card_num.value == "") {
						alert("Please enter creditcard number");
						form.x_card_num.focus();
						return;
					}
					if (form.x_card_code.value == "") {
						alert("Please enter card code");
						form.x_card_code.focus();
						return ;
					}
					form.submit();
				}

				function checkNumber(txtName)
				{
					var num = txtName.value
					if(isNaN(num))
					{
						alert("Only number is accepted");
						txtName.value = "";
						txtName.focus();
					}
				}

				function clearTextbox() {
					var form = document.donate_form ;
					if (form.amount)
						form.amount.value = '';	
				}
				</script>
<?php
	$output = apply_filters( 'wp_donate_filter_form', ob_get_contents());
	ob_end_clean();
}

	return $output;
}
?>
