<?php
if( !defined('ABSPATH') ){ exit();}
global $pluginDir;
wp_enqueue_style( 'elevaweb', plugins_url('../css/elevaweb.css', __FILE__));
include $pluginDir."templates/loading.php";
?>
<div class="change">
	<div class="form-box">
		<div class="box-header"><?php _e('Elevaweb','elevaweb'); ?></div>
		<form action="<?php echo admin_url().'admin.php?page=elevaweb';?>" method="post" name="Login_Form" class="form-sign">
			<?php if(isset($_GET['message'])){ ?>
				<div class="massage"><?php printf( __( '%s', 'elevaweb' ),$_GET['message']); ?></div>
			<?php } ?>
			<div class="elevaweb-row">
				<span class="elevaweb-label">
					<?php _e('Change Password','elevaweb'); ?>
				</span>
			</div>
			<div class="elevaweb-row">
				<input type="text" class="elevaweb-input" name="password" placeholder="<?php _e('Old Password','elevaweb') ?>" required="" />
			</div>
			<div class="elevaweb-row">
				<input type="text" class="elevaweb-input" name="new_password" placeholder="<?php _e('New Password','elevaweb') ?>" required="" />
			</div>
			<div class="elevaweb-row">
				<div class="elevaweb-group">
					<button class="btn-elevaweb-login"  name="Submit" value="Login" type="Submit"><?php _e('Change Password','elevaweb') ?></button>
					<input type="hidden" name="math" value="change"/>
					<input type="hidden" name="customerId" value="<?php echo $_SESSION['customerId']; ?>"/>
				</div>
			</div>
		</form>
	</div>
</div>