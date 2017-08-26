<?php
if( !defined('ABSPATH') ){ exit();}
global $pluginDir;
wp_enqueue_style( 'elevaweb', plugins_url('../css/elevaweb.css', __FILE__));
include $pluginDir."templates/loading.php";
?>
<div class="form-box login-box">
	<div class="box-header"><?php _e('Elevaweb','elevaweb'); ?></div>
	<form action="<?php echo admin_url('admin.php?page=elevaweb'); ?>" method="post" name="Login_Form" class="form-sign">
		<?php if($_GET['message']){?>
			<div class="massage"><?php printf( __( '%s', 'elevaweb' ),$_GET['message']);?></div>
		<?php } ?>
		<div class="elevaweb-row">
			<input type="text" class="elevaweb-input" name="email_id" placeholder="<?php echo  __('E-mail','elevaweb') ?>" required="" />
		</div>
		<div class="elevaweb-row">
			<input type="password" class="elevaweb-input" name="password" placeholder="<?php echo  __('Password','elevaweb') ?>" required=""/>
		</div>
		<div class="elevaweb-row">
			<div class="elevaweb-group">
				<button class="btn-elevaweb-login"  name="Submit" value="Login" type="Submit"><?php echo  __('Login','elevaweb') ?></button>
				<input type="hidden" name="math" value="login"/>
				<input type="hidden" name="site_address" value="<?php echo site_url(); ?>"/>
				<a href="<?php echo admin_url('admin.php?page=elevaweb-register'); ?>" class="btn-elevaweb-register"><?php echo  __('Create Account','elevaweb') ?></a>
			</div>
		</div>
	</form>
</div>
<div class="form-box-footer">
	<?php _e('Forgot your password? Click here to ','elevaweb'); ?><a href="<?php echo admin_url('admin.php?page=elevaweb-forgotpassword');?>" class="elevaweb-link"><?php _e('Recover','elevaweb'); ?></a> <br /><?php _e('You wil receive an e-mail with new password','elevaweb'); ?>
</div>