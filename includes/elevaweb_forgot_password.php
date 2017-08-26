<?php
if( !defined('ABSPATH') ){ exit();}
global $pluginDir;
wp_enqueue_style( 'elevaweb', plugins_url('../css/elevaweb.css', __FILE__));
include $pluginDir."templates/loading.php";
?>
<div class="forgot">
	<div class="form-box">
		<div class="box-header"><?php _e('Elevaweb','elevaweb'); ?></div>
		<form id="forgot_password" method="post" name="Login_Form" class="form-sign">
			<div class="massage"></div>
			<div class="elevaweb-row">
				<span class="elevaweb-label">
					<?php _e('Recover Password','elevaweb'); ?>
				</span>
			</div>
			<div class="elevaweb-row">
				<input type="email" class="elevaweb-input" id="email_id" name="email_id" placeholder="<?php _e('Enter Email','elevaweb') ?>" required="" />
			</div>
			<div class="elevaweb-row">
				<div class="elevaweb-group">
					<button class="btn-elevaweb-login"  name="Submit" value="Login" type="Submit"><?php _e('Recover','elevaweb') ?></button>
					<input type="hidden" name="math" value="forgot"/>
				</div>
			</div>
		</form>
	</div>
	<div class="form-box-footer">
		<?php _e('You will receive an email with new password','elevaweb'); ?> <br /><?php _e('Remember your password? click here to ','elevaweb') ?> <a href="<?php echo admin_url('admin.php?page=elevaweb'); ?>" class="elevaweb-link"><?php _e('Login','elevaweb') ?></a>
	</div>
</div>