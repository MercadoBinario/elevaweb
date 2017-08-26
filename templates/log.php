<div class="eleva-wrap">
	<?php $header_title = __("Last Posts","elevaweb"); ?>
	<div class="col-md-12"><?php echo elevawebHeader($header_title); ?>
		<div class="elevaweb-content">
			<div class="col-md-10">
				<div class="eleva-round-box">
					<div class="eleva-myposts-content eleva-log-content">
						<div id="poststuff">
							<div id="post-body" class="metabox-holder">
								<div id="post-body-content">
									<div class="meta-box-sortables ui-sortable">
										<form method="post">
											<?php
											if(class_exists('My_Log_Table')) {
												$myLogTable = new My_Log_Table();
												$myLogTable->prepare_items();
												$myLogTable->display();
											}
											?>
										</form>
									</div>
								</div>
							</div>
							<br class="clear">
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
