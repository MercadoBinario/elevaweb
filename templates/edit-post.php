<div id="loading"></div>
<form id="eleva-new-post" method="post">
	<input type="hidden" value="elevaweb_save_feed" name="action" />
	<input type="hidden" value="<?php echo wp_create_nonce('elevaweb_save_feed'); ?>" name="_wpnonce" />
	<input type="hidden" value="<?php echo $feedData->ID; ?>" name="feed_id" />
	<div class="eleva-wrap">
		<?php $header_title = __("Profile",'elevaweb'); ?>
	  <div class="col-md-12"><?php echo elevawebHeader($header_title); ?>
		<div class="elevaweb-content">
		  <div id="new_post_error"><?php elevaweb_message(); ?></div>
		  <div class="col-md-5">
			<div class="eleva-round-box">
			  <div class="eleva-profile"><?php _e('Choose Source/Website','elevaweb'); ?></div>
			  <div class="eleva-profile-content">
				<div class="eleva-profile-label"> <?php _e('Source:','elevaweb'); ?><em>*</em> </div>
				<div class="eleva-profile-input">
				  <select class="elevaweb-select semi-square" id="src_feed" name="src_feed" required>
					<option value=""><?php _e('--select feed--','elevaweb'); ?></option>
					<?php
			   foreach($_SESSION['feeds'] as $feed)
			   {
				   $selected = "";
				   if($feedData->feed == $feed) {
						$selected = "selected";
				   }
				   echo '<option value="'.$feed.'" '.$selected.'>'.$feed.'</option>';

				   }

				?>
				  </select>
				  <div class="validation-error"><p><?php _e('Please select the feed.','elevaweb'); ?></p></div>
				</div>
				<div class="eleva-profile-label"> <?php _e('Category:','elevaweb'); ?><em>*</em> </div>
				<div class="eleva-profile-input">
                                  <input type="hidden" id="feed_cat" value="<?php echo $feedData->feed_category; ?>" />
				  <select class="elevaweb-select semi-square" id="src_cat" name="src_cat" required>
					<option value=""><?php _e('--select feed first--','elevaweb'); ?></option>
				  </select>
				   <div class="validation-error"><p><?php _e('Please select the feed category.','elevaweb'); ?></p></div>
				</div>
				<script type="text/javascript">
				jQuery("#src_feed").change(function(){
					var ajaxurl = "<?php echo admin_url("admin-ajax.php");?>";

								var data = {

									"action": "getFeedcatajax",

									"feed": jQuery("#src_feed").val()

								};

								jQuery.post(ajaxurl, data, function(response) {

									if(response){

													jQuery("#src_cat").html(response);
                                                                                                        if(jQuery('#feed_cat').length) {
                                            var feed_cat = jQuery('#feed_cat').val();

                                            jQuery('#src_cat').find('option').each(function() {
                                                if(jQuery(this).text() == feed_cat) {
                                                    jQuery(this).attr('selected','selected');
                                                }
                                            });
                                        }

												}
								   });

				});
				if(jQuery("#src_feed").find('option:selected').length > 0) {
					jQuery("#src_feed").change();
				}
				</script>

				<?php
					$metas = $feedData->feed_meta;
					$feedMeta = unserialize($metas);
				?>

				<div class="eleva-profile"><?php _e('In Your Blog','elevaweb'); ?></div>
				<div class="eleva-profile-label"> <?php _e('Category:','elevaweb'); ?> </div>
				<div class="eleva-profile-input">
				  <select class="elevaweb-select semi-square" name="blog_cat">
					<option value=""><?php _e('--select a destination category--','elevaweb'); ?></option>
					<?php
			   foreach($all_terms as $key=>$value)
			   {
				   $selected = "";
				   if($feedData->post_category == $key) {
						$selected = "selected";
				   }
				   echo '<option value="'.$key.'" '.$selected.'>'.$value.'</option>';

				   }

				?>
				  </select>
                                </div>
				<div class="eleva-profile-label"> <?php _e('Tags:','elevaweb'); ?> </div>
				<div class="eleva-profile-input">
				  <input type="text" class="elevaweb-input" name="eleva_tags" placeholder="<?php _e('Use comma to separate each tag','elevaweb'); ?>" value="<?php echo $feedMeta['tags']; ?>" />
				</div>
				<span style="display:none;">
				<div class="eleva-profile-label"> <?php _e('Title:','elevaweb'); ?> </div>
				<div class="eleva-profile-input">
				  <input type="text" class="elevaweb-input" name="eleva_title" placeholder="<?php _e('Post Title','elevaweb'); ?>" value="<?php echo $feedMeta['change_title']; ?>" />
				</div>
				</span>
				<div class="eleva-profile-label"> <?php _e('Don’t publish posts with these words :','elevaweb'); ?> </div>
				<div class="eleva-profile-input">
				  <textarea name="eleva_neg_word" class="elevaweb-input" rows="3" placeholder="<?php _e('The robot will not post articles it contains one or more or this word (comma separated)','elevaweb'); ?>"><?php
				  if(is_array($feedMeta['negative_keyword'])){
					echo implode(",",$feedMeta['negative_keyword']);
				  }
				  ?></textarea>
				</div>
				<div class="eleva-profile-label"> <?php _e('Publish online posts with these words :','elevaweb'); ?> </div>
				<div class="eleva-profile-input">
				  <textarea name="eleva_pos_word" class="elevaweb-input" rows="3" placeholder="<?php _e('The robot will read the texts and post only the article it contains
	one or mor of this word.(comma separated)','elevaweb'); ?>">
				<?php
				if(is_array($feedMeta['positive_word'])){
					echo implode(",",$feedMeta['positive_word']);
				}
				?></textarea>
				</div>
			  </div>
			</div>
		  </div>
		  <div class="col-md-5">
			<div class="eleva-round-box">
			  <div class="eleva-profile"><?php _e('Images:','elevaweb'); ?></div>
			  <div class="eleva-profile-content">
				<div class="eleva-profile-input">
				  <div class="elevaweb-checkbox">
                                      <?php
                                        $checked = '';
                                        $skipWithoutImages = $feedMeta['skip_without_image'];
                                        if($skipWithoutImages == "1") {
                                            $checked = "checked";
                                        }
                                      ?>
					<input type="radio" name="image_condition" value="skip_without_images" <?php echo $checked; ?>/>
					<span class="elevaweb-checkbox-label"><?php _e('Skip posts without image','elevaweb'); ?></span> </div>
				  <div class="elevaweb-checkbox">
                                       <?php
                                        $checked = '';
                                        $removeImages = $feedMeta['remove_images'];
                                        if($removeImages == "1") {
                                            $checked = "checked";
                                        }
                                       ?>
					<input type="radio" name="image_condition" value="remove_image" <?php echo $checked; ?> />
					<span class="elevaweb-checkbox-label"><?php _e('Remove post image','elevaweb'); ?></span> </div>
				  <div class="elevaweb-checkbox">
                                        <?php
                                        $checked = '';
                                        $add_images = $feedMeta['add_images'];
                                        if($add_images == "1") {
                                            $checked = "checked";
                                        }
                                       ?>
					<input type="radio" name="image_condition" value="add_image" <?php echo $checked; ?> />
					<span class="elevaweb-checkbox-label"><?php _e('Add image','elevaweb'); ?></span> </div>
				</div>
				<div class="eleva-profile-input">
                                    <?php
                                    $imagesUrl = $feedMeta['images_url'];
                                    if(!empty($imagesUrl)) {
                                        $imagesUrl = unserialize($imagesUrl);
                                        if(!empty($imagesUrl)) {
                                            $imagesUrl = implode(PHP_EOL,$imagesUrl);
                                        }
                                        else {
                                            $imagesUrl = '';
                                        }
                                    }
                                    ?>
				  <textarea name="eleva_image_url" class="elevaweb-input" rows="3" placeholder="<?php _e('It will force use these images and ignore source found images
	(image URL comma separated)','elevaweb'); ?>"><?php echo $imagesUrl; ?></textarea>
				</div>
				<?php
					$scheduledDate = $feedData->scheduled_date;
					$scheduledDate = unserialize($scheduledDate);
					$scheduledTime = elevaweb_get_scheduled_time($scheduledDate);
					if($scheduledTime) {
						$scheduledTime = explode(':',$scheduledTime);
					}
				?>
				<div class="eleva-profile"><?php _e('Select Day and time to publish:','elevaweb'); ?><em>*</em></div>
				<div class="eleva-profile-input">
				  <div class="eleva-radio-group">
					<div class="eleva-radio-head">
					  <ul>
						<li><?php _e('Sunday','elevaweb'); ?></li>
						<li><?php _e('Monday','elevaweb'); ?></li>
						<li><?php _e('Tuesday','elevaweb'); ?></li>
						<li><?php _e('Wednesday','elevaweb'); ?></li>
						<li><?php _e('Thursday','elevaweb'); ?></li>
						<li><?php _e('Friday','elevaweb'); ?></li>
						<li><?php _e('Saturday','elevaweb'); ?></li>
					  </ul>
					</div>
					<div class="eleva-radio-button">
					  <ul>
						<li>
							<?php
								$today = date('l');
								$nextSunday = strtotime('next sunday');
								$date = date('Y-m-d',$nextSunday);
								if($today == "Sunday") {
									$date = date('Y-m-d');
								}
								$checkedDate = elevaweb_get_current_day_schedule($scheduledDate,'sunday');
								if($checkedDate) {
									$checked = "checked";
									$date = $checkedDate;
								}
								else {
									$checked = "";
								}
							?>
						<input type="checkbox" name="days[]" id="checkbox-1" value="<?php echo $date; ?>" <?php echo $checked; ?>>
						<label for="checkbox-1"></label>
						</li>
						<li>
							<?php
								$nextMonday = strtotime('next monday');
								$date = date('Y-m-d',$nextMonday);
								if($today == "Monday") {
									$date = date('Y-m-d');
								}
								$checkedDate = elevaweb_get_current_day_schedule($scheduledDate,'monday');
								if($checkedDate) {
									$checked = "checked";
									$date = $checkedDate;
								}
								else {
									$checked = "";
								}
							?>
						<input type="checkbox" name="days[]" id="checkbox-2" value="<?php echo $date; ?>" <?php echo $checked; ?>>
						<label for="checkbox-2"></label>
						</li>
						<li>
							<?php
								$nextTuesday = strtotime('next tuesday');
								$date = date('Y-m-d',$nextTuesday);
								if($today == "Tuesday") {
									$date = date('Y-m-d');
								}
								$checkedDate = elevaweb_get_current_day_schedule($scheduledDate,'tuesday');
								if($checkedDate) {
									$checked = "checked";
									$date = $checkedDate;
								}
								else {
									$checked = "";
								}
							?>
						<input type="checkbox" name="days[]" id="checkbox-3" value="<?php echo $date; ?>" <?php echo $checked; ?>>
						<label for="checkbox-3"></label>
						</li>
						<li>
							<?php
								$nextWednesday = strtotime('next wednesday');
								$date = date('Y-m-d',$nextWednesday);
								if($today == "Wednesday") {
									$date = date('Y-m-d');
								}
								$checkedDate = elevaweb_get_current_day_schedule($scheduledDate,'wednesday');
								if($checkedDate) {
									$checked = "checked";
									$date = $checkedDate;
								}
								else {
									$checked = "";
								}
							?>
						<input type="checkbox" name="days[]" id="checkbox-4" value="<?php echo $date; ?>" <?php echo $checked; ?>>
						<label for="checkbox-4"></label>
						</li>
						<li>
							<?php
								$nextThursday = strtotime('next thursday');
								$date = date('Y-m-d',$nextThursday);
								if($today == "Thursday") {
									$date = date('Y-m-d');
								}
								$checkedDate = elevaweb_get_current_day_schedule($scheduledDate,'thursday');
								if($checkedDate) {
									$checked = "checked";
									$date = $checkedDate;
								}
								else {
									$checked = "";
								}
							?>
						<input type="checkbox" name="days[]" id="checkbox-5" value="<?php echo $date; ?>" <?php echo $checked; ?>>
						<label for="checkbox-5"></label>
						</li>
						<li>
							<?php
								$nextFriday = strtotime('next friday');
								$date = date('Y-m-d',$nextFriday);
								if($today == "Friday") {
									$date = date('Y-m-d');
								}
								$checkedDate = elevaweb_get_current_day_schedule($scheduledDate,'friday');
								if($checkedDate) {
									$checked = "checked";
									$date = $checkedDate;
								}
								else {
									$checked = "";
								}
							?>
						<input type="checkbox" name="days[]" id="checkbox-6" value="<?php echo $date; ?>" <?php echo $checked; ?>>
						<label for="checkbox-6"></label>
						</li>
						<li>
							<?php
								$nextSaturday = strtotime('next saturday');
								$date = date('Y-m-d',$nextSaturday);
								if($today == "Saturday") {
									$date = date('Y-m-d');
								}
								$checkedDate = elevaweb_get_current_day_schedule($scheduledDate,'saturday');
								if($checkedDate) {
									$checked = "checked";
									$date = $checkedDate;
								}
								else {
									$checked = "";
								}
							?>
						<input type="checkbox" name="days[]" id="checkbox-7" value="<?php echo $date; ?>" <?php echo $checked; ?>>
						<label for="checkbox-7"></label>
						</li>


					  </ul>
					</div>
				  </div>
				  <div class="clear"></div>
				  <div class="validation-error"><p><?php _e('Please select the day.','elevaweb'); ?></p></div>
				</div>
				<div class="eleva-radio-button-time">
					<div class="col-md-2">
						<strong><?php _e('Time','elevaweb'); ?>:</strong>
					</div>
					<div class="col-md-7">
						<select name="schedule_hour">
							<option value="-1"><?php _e('Hour','elevaweb'); ?></option>
							<?php
							for($i = 1; $i <= 24; $i++):
								$selected = "";
								if(count($scheduledTime)) {
									if($scheduledTime[0] == date("H", strtotime("$i:00"))) {
										$selected = "selected";
									}
								}
							?>
							<option <?php echo $selected; ?> value="<?= date("H", strtotime("$i:00")); ?>"><?= date("H", strtotime("$i:00")); ?></option>
							<?php endfor; ?>
						</select>
						<select name="schedule_minutes">
							<option value="-1"><?php _e('Minutes','elevaweb'); ?></option>
							<?php
							for($i = 0; $i <= 59; $i++):
								$selected = "";
								if(count($scheduledTime)) {
									if($scheduledTime[1] == date("i", strtotime("00:$i:00"))) {
										$selected = "selected";
									}
								}
							?>
							<option <?php echo $selected; ?> value="<?= date("i", strtotime("00:$i:00")); ?>"><?= date("i", strtotime("00:$i:00")); ?></option>
							<?php endfor; ?>
						</select>
					</div>
					<div class="clear"></div>
					<div class="validation-error"><p><?php _e('Please select the time.','elevaweb'); ?></p></div>
				</div>
				<div class="submit-group">
					<input type="submit" class="button button-primary" name="save_schedule" value="<?php _e('Save','elevaweb'); ?>" />
				</div>
			  </div>
			</div>
		  </div>
		</div>
	  </div>
	</div>
</form>
<script>
try {
	jQuery(document).ready(function() {
		jQuery( "#eleva-edit-post" ).sisyphus({
			autoRelease: false
		});
	});
}
catch(err) {
}
</script>
