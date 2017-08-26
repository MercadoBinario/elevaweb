<?php
if( !defined('ABSPATH') ){ exit();}
if(count($_POST)){
	elevaweb_auth();
}
echo elevaweb_side_menu();
$dash_text = __("Dashboard","elevaweb");
?>
<div class="eleva-wrap">
	<div class="col-md-12"><?php echo elevawebHeader( $dash_text ); ?>
		<div class="elevaweb-content">
			<div class="col-md-5">
				<div class="eleva-round-box">
					<div class="eleva-last-post-content">
						<p><?php _e('Welcome to Elevaweb, now you can auto post in your website.','elevaweb'); ?></p>
						<p><?php _e('To create your first Blog Post automation click on the pencil (right) and select which content do you want in your website.','elevaweb'); ?></p>
						<p><?php _e('There we will explain you how to do that. If you prefer, can watch this
						step by step video (link > <a href="https://goo.gl/CMSr5w)" target="_blank">https://goo.gl/CMSr5w)</a>.','elevaweb'); ?></p>
						<?php _e('Thanks"','elevaweb'); ?>
					</div>
				<div class="eleva-last-post"><?php _e('Last Post','elevaweb'); ?></div>
				<div class="eleva-last-post-content">
					<?php
						global $wpdb;
						$sql = "SELECT post_id,post_category FROM {$wpdb->prefix}elevaweb_scheduled_post_log ORDER BY ID DESC LIMIT 0,5";
						$results = $wpdb->get_results($sql);
						if($wpdb->num_rows > 0):
						?>
						<ul>
						<?php
						foreach($results as $result):
							$postData = get_post( $result->post_id );
							if(!$postData){
								continue;
							}
						?>
							<li>
								<div class="e-row">
									<div class="e-post-title"><a href="<?php echo get_permalink($result->post_id); ?>"><?php echo $postData->post_title; ?></a></div>
								</div>
								<div class="e-row">
									<?php $pubDate = get_the_date('d-m-Y',$result->post_id); ?>
									<div class="e-post-date"><?php echo $pubDate; ?></div>
									<?php
									$cat = '';
									$id = $result->post_category;
									if(!empty($id)){
										$cat = get_cat_name($id);
									}
									if(!empty($cat)):
									?>
									<div class="e-post-cat"><?php echo $cat; ?></div>
									<?php endif; ?>
								</div>
								<div class="e-row">
									<?php
									$content = $postData->post_content;
									$content = strip_tags($content);
									?>
									<div class="e-post-content"><?php echo elevaweb_truncate($content,150,array('html'=>true)); ?></div>
								</div>
							</li>
						<?php endforeach; ?>
						</ul>
					<?php endif; ?>
				</div>
				</div>
			</div>
			<div class="col-md-5">
				<div class="eleva-round-box">
					<div class="e-row">
						<a href="<?php echo admin_url('admin.php?page=eleva-new-post'); ?>">
							<div class="eleva-active">
								<img src="<?php echo plugins_url('../images/eleva-pencil.png', __FILE__);?>" title="<?php _e('Create a New auto Post','elevaweb'); ?>" />
							</div>
						</a>
						<div class="eleva-off">
							<img src="<?php echo plugins_url('../images/elev-lock.png', __FILE__);?>"/>
						</div>
					</div>
					<div class="e-row">
						<div class="eleva-off">
							<img src="<?php echo plugins_url('../images/elev-lock.png', __FILE__); ?>" />
						</div>
						<div class="eleva-off">
							<img src="<?php echo plugins_url('../images/elev-lock.png', __FILE__); ?>"/>
						</div>
					</div>
					<div class="e-row">
						<div class="eleva-off">
							<img src="<?php echo plugins_url('../images/elev-lock.png', __FILE__);?>" />
						</div>
						<div class="eleva-off">
							<img src="<?php echo plugins_url('../images/elev-lock.png', __FILE__);?>" />
						</div>
					</div>
					<div class="e-row">
						<div class="eleva-off">
							<img src="<?php echo plugins_url('../images/elev-lock.png', __FILE__); ?>" />
						</div>
						<div class="eleva-off">
							<img src="<?php echo plugins_url('../images/elev-lock.png', __FILE__); ?>" />
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>