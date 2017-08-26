<?php
if( !defined('ABSPATH') ){ exit();}
global $pluginDir;
wp_enqueue_script('jquery-ui-datepicker');
wp_enqueue_style('jquery-ui-css', 'http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.2/themes/smoothness/jquery-ui.css');
wp_enqueue_style( 'elevaweb', plugins_url('../css/elevaweb.css', __FILE__));
include $pluginDir."templates/loading.php";
?>
<div class="create-account">
	<div class="text-center"><?php _e('Start by registering an account to start using Elevaweb and automate your production of content. Just enter your full name, email, and password. "','elevaweb'); ?></div>
	<div class="form-box">

	<div class="box-header"><?php _e('Elevaweb','elevaweb'); ?></div>
		<form action="<?php echo admin_url().'admin.php?page=elevaweb';?>" method="post" name="Login_Form" class="form-sign">
			<?php if(isset($_GET['message'])){ ?>
				<div class="massage"><?php printf( __( '%s', 'elevaweb' ),$_GET['message']);?></div>
			<?php } ?>
			<div class="elevaweb-row">
				<input type="text" class="elevaweb-input" name="full_name" placeholder="<?php echo  __('Full Name','elevaweb') ?>" required="" />
			</div>
			<div class="elevaweb-row">
				<input type="email" class="elevaweb-input" name="email_id" placeholder="<?php echo  __('Email','elevaweb') ?>" required="" />
			</div>
			<div class="elevaweb-row">
				<input type="password" class="elevaweb-input" name="password" placeholder="<?php echo  __('Password','elevaweb') ?>" required=""/>
			</div>
			<div class="elevaweb-row">
				<div class="elevaweb-group">
					<button class="btn-elevaweb-login"  name="Submit" value="Login" type="Submit"><?php echo  __('Create Account','elevaweb') ?></button>
					<input type="hidden" name="math" value="reg"/>
					<input type="hidden" name="website" value="<?php echo site_url();?>">
				</div>
			</div>
		</form>
	</div>
	<div class="form-box-footer"><?php _e('Do you have an account?','elevaweb'); ?> <a style="width: 110px;" href="<?php echo admin_url('admin.php?page=elevaweb'); ?>" class="elevaweb-link"><?php _e('click here','elevaweb') ?></a></div>
</div>