<?php
	$userData = $_SESSION['elevaweb_userdata'];
	//print_r($userData);
?>
<form action="<?php echo admin_url('admin-post.php'); ?>" method="post" enctype="multipart/form-data">
	<input type="hidden" value="edit_profile" name="action" />
	<input type="hidden" value="<?php echo $userData->customer_id; ?>" name="id" />
	<input type="hidden" value="<?php echo date("d-m-Y",strtotime($userData->created_on)); ?>" name="register_date" />
	<input type="hidden" value="<?php echo $userData->profile_url; ?>" name="profile_url" />
	<div class="eleva-wrap">
		<?php $header_title = __("Profile",'elevaweb'); ?>
	  <div class="col-md-12"><?php echo elevawebHeader($header_title); ?>
		<div class="elevaweb-content">
			<?php elevaweb_message(); ?>
		  <div class="col-md-10">
			<div class="eleva-round-box">
			<div class="col-md-8">
			  <div class="eleva-profile"><?php _e('User Information','elevaweb'); ?></div>
			</div>
			<div class="col-md-3">
				<div class="hiddenFileInputContainter">
					<?php
					if( isset( $userData->profile_url ) && !empty( $userData->profile_url ) ){
						?>
						<img class="elevaweb_profile_picture" src="<?php echo $userData->profile_url; ?>" />
						<?php
					}else{
					?>
					<img class="elevaweb_profile_picture" src="<?php echo plugins_url('../images/eleva_dummy_user_image.jpg', __FILE__); ?>" />
					<?php } ?>
				</div>
				<input type="file" name="profile_picture" id="profile_picture" accept="image/*" />
			</div>
			  <div class="eleva-profile-content">
				<div class="profle-head"></div>
				<div class="eleva-profile-label"> <?php _e('Name:','elevaweb'); ?> </div>
				<div class="eleva-profile-input">
				  <input type="text" class="elevaweb-input" name="full_name" value="<?php echo $userData->full_name; ?>" required="" />
				</div>
				<div class="eleva-profile-label"> <?php _e('E-Mail:','elevaweb'); ?> </div>
				<div class="eleva-profile-input">
				  <input type="email" class="elevaweb-input" name="email" value="<?php echo $userData->email_id; ?>" required="" />
				</div>
				<div class="eleva-profile-label"> <?php _e('Phone:','elevaweb'); ?> </div>
				<div class="eleva-profile-input">
				  <input type="tel" class="elevaweb-input" name="phone" value="<?php echo $userData->mobile_no; ?>" required="" />
				</div>
				<div class="eleva-profile-label"> <?php _e('Website:','elevaweb'); ?> </div>
				<div class="eleva-profile-input"> <span class="eleva-profile-text"> <?php echo site_url(); ?></span> </div>
				<div class="eleva-profile-label"> <?php _e('Active Services:','elevaweb'); ?> </div>
				<div class="eleva-profile-input"> <span class="eleva-profile-text"><?php _e('Content','elevaweb'); ?></span> </div>
				<div class="eleva-profile-label"> <?php _e('Registration Date:','elevaweb'); ?> </div>
				<div class="eleva-profile-input"> <span class="eleva-profile-text"><?php echo date("d-m-Y",strtotime($userData->created_on)); ?></span> </div>
				<div class="submit">
					<button type="submit" class="button button-primary"><?php _e('Save','elevaweb'); ?></button>
			    </div>
			  </div>
			</div>
		  </div>
		</div>
	  </div>
	</div>
</form>
