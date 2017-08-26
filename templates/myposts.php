<?php
	global $pluginDir;
	if(isset($_REQUEST['action']) && $_REQUEST['action'] == "edit_feed") {
		if(isset($_REQUEST['id'])) {
			$feedData = elevaweb_get_saved_feeddata($_REQUEST['id']);
			include($pluginDir."/templates/edit-post.php");
		}
	}
	else {
?>
<div class="eleva-wrap">
	<?php $header_title = __('My Posts Configuration','elevaweb'); ?>
	<div class="col-md-12"><?php elevawebHeader($header_title); ?>
		<div class="elevaweb-content">
			<div class="col-md-10">
				<div class="eleva-round-box">
						<div class="eleva-myposts-content">
							<?php
							$id = '';
							if(isset($_REQUEST['id'])) {
								$id = $_REQUEST['id'];
							}
							if(isset($_REQUEST['action'])) {
								if($_REQUEST['action'] == "pause_post") {
									if(!empty($id)) {
										elevaweb_pause_post($id);
									}
								}
								else if($_REQUEST['action'] == "resume_post") {
									if(!empty($id)) {
										elevaweb_resume_post($id);
									}
								}
								else if($_REQUEST['action'] == "delete_post") {
									if(!empty($id)) {
										elevaweb_delete_post($id);
									}
								}
							}
							?>
							<div id="poststuff">
								<div id="post-body" class="metabox-holder">
									<div id="post-body-content">
										<?php elevaweb_message(); ?>
										<div class="meta-box-sortables ui-sortable">
											<form method="post">
												<?php
												if(class_exists('My_List_Table')) {
													$myListTable = new My_List_Table();
													$myListTable->prepare_items();
													$myListTable->display();
												}
												?>
											</form>
											<div class="clear"></div>
										</div>
									</div>
									<div class="clear"></div>
								</div>
								<br class="clear">
							</div>
							<div class="clear"></div>
						</div>
					</div>
				</div>
			</div>
			<div class="clear"></div>
		</div>
	</div>
	<div class="clear"></div>
</div>
<div class="clear"></div>
<?php } ?>
<script>
function elevaweb_confirm_action(element) {
	var c = confirm("Do you really want to delete this feed?");
	if(c) {
		window.location = element.getAttribute('data-href');
	}
}
</script>
