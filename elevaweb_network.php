<?php
if( !defined('ABSPATH') ){ exit();}
wp_enqueue_style( 'elevaweb', plugins_url('css/elevaweb.css', __FILE__));
$type = '';
$data = '';
?>
<div class="eleva-wrap">
	<div class="col-md-12"><?php echo elevawebHeader("Networks"); ?>
		<div class="elevaweb-content">
			<div class="col-md-10">
				<div class="eleva-round-box">
					<div class="eleva-myposts-content">
						<div class="col-md-12">
							<div class="eleva-profile">
								<?php _e('Networks','elevaweb'); ?>
							</div>
						</div>
						<div class="col-md-12">
							<hr />
							
							<div class="col-md-4">
								<img src="<?php echo plugins_url('images/facebook_circle.png', __FILE__); ?>" width="35" />
								<span><?php _e('Facebook','elevaweb'); ?></span>
							</div>
							<div class="col-md-3">
								<?php
								global $wpdb;
								$results = $wpdb->get_results("SELECT social_name,ID,user_name FROM ".$wpdb->prefix."elevaweb_networks_authentication WHERE social_name = 'Facebook' AND status = 1");
								if($results){
									foreach($results as $result){
										echo '<span data-id="'.$result->ID.'">'.__('Connected To : ','elevaweb').$result->user_name.' <img class="delete_fb_account" src="'.plugins_url('images/delete_icon.png', __FILE__).'" width="15" /></span>';
									}
								}
								//$page_url = urlencode( admin_url("admin.php?page=eleva-network&eleva_type=page") );
								$profile_url = FB_AUTH.'?choose=profile&redirect='.site_url();
								$page_url = FB_AUTH.'?choose=page&redirect='.site_url();
								?>
							</div>
							<div class="col-md-1">
								<input class="button button-primary" onclick="pop_elevaweb('<?php echo $profile_url; ?>','Elevaweb')" type="button" value="<?php _e('+ Profile','elevaweb'); ?>" />
							</div>
							<div class="col-md-2">
								<input class="button button-primary" onclick="pop_elevaweb('<?php echo $page_url; ?>','Elevaweb')" type="button" value="<?php _e('+ Page','elevaweb'); ?>" />
							</div>
						</div>
						<?php /*<div class="col-md-12">
							<hr />
							<div class="col-md-4">
								<img src="<?php echo plugins_url('images/twitter.png', __FILE__); ?>" width="35" />
								<span><?php _e('Twitter','elevaweb'); ?></span>
							</div>
							<div class="col-md-3">
								<?php
								global $wpdb;
								$results_twi = $wpdb->get_results("SELECT social_name,ID,user_name FROM ".$wpdb->prefix."elevaweb_networks_authentication WHERE social_name = 'Twitter'");
								if($results_twi){
									foreach($results_twi as $result_twi){
										echo '<span data-id="'.$result_twi->ID.'">'.__('Connected To : ','elevaweb').$result_twi->user_name.' <img class="edit_twi_account" src="'.plugins_url('images/edit-image.png', __FILE__).'" width="15" /> <img class="delete_twi_account" src="'.plugins_url('images/delete_icon.png', __FILE__).'" width="15" /></span>';
									}
								}
								?>
							</div>
							<div class="col-md-1">
								<input class="button button-primary" id="twitter_profile" type="button" value="<?php _e('+ Profile','elevaweb'); ?>" />
							</div>
						</div>*/ ?>
						<div class="col-md-12">
							<hr />
							<div class="col-md-4">
								<img src="<?php echo plugins_url('images/insta_icon.png', __FILE__); ?>" width="35" />
								<span><?php _e('Instagram','elevaweb'); ?></span>
							</div>
							<div class="col-md-3">
								<?php
								global $wpdb;
								$results = $wpdb->get_results("SELECT social_name,ID,user_name FROM ".$wpdb->prefix."elevaweb_networks_authentication WHERE social_name = 'Instagram' AND status = 1");
								if($results){
									foreach($results as $result){
										echo '<span data-id="'.$result->ID.'">'.__('Connected To : ','elevaweb').$result->user_name.' <img class="edit_insta_account" src="'.plugins_url('images/edit-image.png', __FILE__).'" width="15" /> <img class="delete_insta_account" src="'.plugins_url('images/delete_icon.png', __FILE__).'" width="15" /></span>';
									}
								}
								?>
							</div>
							<div class="col-md-1">
								<input class="button button-primary" id="insta_profile" type="button" value="<?php _e('+ Profile','elevaweb'); ?>" />
							</div>
						</div>
						<div class="col-md-12" id="insta_app_add">
							<p>
								<span class="eleva-profile"><?php _e('Instagram','elevaweb'); ?></span>
							</p>
							<div class="col-md-11">
								<form method="post" id="insta_add_form">
									<p>
										<label>Username : </label>
										<input type="text" name="insta_username" class="elevaweb-input" id="insta_username" />
									</p>
									<p>
										<label>Password : </label>
										<input type="password" name="insta_password" class="elevaweb-input" id="insta_password" />
									</p>
									<p id="insta_error"></p>
									<input type="hidden" name="_wpnonce" value="<?php echo wp_create_nonce("insta_add_profile"); ?>" />
									<input type="hidden" name="action" id="insta_action" value="add_new_insta_app" />
									<p>
										<input class="button button-primary" id="insta_save" type="submit" value="<?php _e('Save Settings','elevaweb'); ?>" />
									</p>
								</form>
							</div>
						</div>
						<div class="col-md-12" id="new_app_add">
							<p>
								<span class="eleva-profile"><?php _e('Facebook','elevaweb'); ?></span>
							</p>
							<div class="col-md-11">
								<form method="post" id="fb_add_form">
									<p>
										<label>Application Name : </label>
										<input type="text" name="fb_app_name" class="elevaweb-input" id="fb_app_name" />
									</p>
									<p>
										<label>Application Id : </label>
										<input type="text" name="fb_app_id" class="elevaweb-input" id="fb_app_id" />
									</p>
									<p>
										<label>Application Secret : </label>
										<input type="text" name="fb_app_secret" class="elevaweb-input" id="fb_app_secret" />
									</p>
									<p id="fb_error"></p>
									<p>
										<input class="button button-primary" id="fb_app_save" type="submit" value="<?php _e('Save Settings & Authorize','elevaweb'); ?>" />
									</p>
									<input type="hidden" name="_wpnonce" value="<?php echo wp_create_nonce("add_fb_profile"); ?>" />
									<input type="hidden" name="action" id="action" value="add_new_fb_app" />
									<input type="hidden" name="app_type" id="app_type" value="profile" />
								</form>	
							</div>
						</div>
						<div class="col-md-12" id="new_twitter_add">
							<p>
								<span class="eleva-profile"><?php _e('Twitter','elevaweb'); ?></span>
							</p>
							<div class="col-md-11">
								<form method="post" id="twitter_add_form">
									<p>
										<label>Twitter Username : </label>
										<input type="text" name="twitter_user_name" class="elevaweb-input" id="twitter_user_name" />
									</p>
									<p>
										<label>API Key : </label>
										<input type="text" name="twitter_api_key" class="elevaweb-input" id="twitter_api_key" />
									</p>
									<p>
										<label>API Secret : </label>
										<input type="text" name="twitter_api_secret" class="elevaweb-input" id="twitter_api_secret" />
									</p>
									<p>
										<label>Access Token : </label>
										<input type="text" name="twitter_access_token" class="elevaweb-input" id="twitter_access_token" />
									</p>
									<p>
										<label>Access Token Secret : </label>
										<input type="text" name="twitter_access_token_secret" class="elevaweb-input" id="twitter_access_token_secret" />
									</p>
									<p id="twitter_error"></p>
									<p>
										<input class="button button-primary" id="twitter_app_save" type="submit" value="<?php _e('Save Settings','elevaweb'); ?>" />
									</p>
									<input type="hidden" name="_wpnonce" value="<?php echo wp_create_nonce("add_twitter_profile"); ?>" />
									<input type="hidden" name="action" value="add_new_twitter" />
									<input type="hidden" name="app_type_twitter" value="profile" />
								</form>	
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>