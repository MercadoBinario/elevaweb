<?php
//var_dump($_SESSION['elevaweb_error']);
if(!isset($_SESSION['elevaweb_error'])) {
?>
<script>
jQuery(document).ready(function() {
	if(jQuery( "#eleva-new-post" ).length) {
		localStorage.clear();
	}
	else if(jQuery( "#eleva-edit-post" ).length) {
		localStorage.clear();
	}
});
</script>
<?php
}
?>
<div id="loading"></div>
<form id="eleva-new-post" method="post">
	<input type="hidden" value="elevaweb_save_feed" name="action" />
	<input type="hidden" value="<?php echo wp_create_nonce('elevaweb_save_feed'); ?>" name="_wpnonce" />
	<div class="eleva-wrap">
		<?php $header_title = __('New Auto Post','elevaweb'); ?>
	  <div class="col-md-12"><?php echo elevawebHeader($header_title); ?>
		<div class="elevaweb-content">
		  <div id="new_post_error"><?php elevaweb_message(); ?></div>
		  <div class="col-md-5">
			<div class="eleva-round-box">
			  <div class="eleva-profile"><?php _e('Choose Source/Website','elevaweb'); ?></div>
			  <div class="eleva-profile-content">
				<div class="eleva-profile-label"> <?php _e('Source:','elevaweb'); ?><em>*</em> </div>
				<div class="eleva-profile-input">
				  <select class="elevaweb-select semi-square" id="src_feed" name="src_feed">
					<option value=""><?php _e('--select feed--','elevaweb'); ?></option>
					<?php
					foreach($_SESSION['feeds'] as $feed)
					{
						echo '<option value="'.$feed.'">'.$feed.'</option>';
					}
					?>
				  </select>
				  <div class="validation-error"><p><?php _e('Please select the feed.','elevaweb'); ?></p></div>
				</div>
				<div class="eleva-profile-label"> <?php _e('Category:','elevaweb'); ?><em>*</em> </div>
				<div class="eleva-profile-input">
				  <select class="elevaweb-select semi-square" id="src_cat" name="src_cat">
					<option value=""><?php _e('--select feed first--','elevaweb'); ?></option>
				  </select>
				  <div class="validation-error"><p><?php _e('Please select the feed category.','elevaweb'); ?></p></div>
				</div>
				<div class="eleva-profile"><?php _e('In Your Blog','elevaweb'); ?></div>
				<div class="eleva-profile-label"> <?php _e('Category:','elevaweb'); ?> </div>
				<div class="eleva-profile-input">
				  <select class="elevaweb-select semi-square" name="blog_cat">
					<option value=""><?php _e('--select a destination category--','elevaweb'); ?></option>
					<?php
					foreach($all_terms as $key=>$value)
					{
						echo '<option value="'.$key.'">'.$value.'</option>';
					}
					?>
				  </select>
				</div>
				<div class="eleva-profile-label"> <?php _e('Tags:','elevaweb'); ?> </div>
				<div class="eleva-profile-input">
				  <input type="text" class="elevaweb-input" name="eleva_tags" placeholder="Use comma to separate each tag" />
				</div>
				<span style="display:none;">
				<div class="eleva-profile-label"> <?php _e('Title: ','elevaweb'); ?></div>
				<div class="eleva-profile-input">
				  <input type="text" class="elevaweb-input" name="eleva_title" placeholder="Post Tilte" required="" />
				</div>
				</span>
				<div class="eleva-profile-label"> <?php _e('Don’t publish posts with these words :','elevaweb'); ?> </div>
				<div class="eleva-profile-input">
				  <textarea name="eleva_neg_word" class="elevaweb-input" rows="3" placeholder="Elevaweb robot will not publish posts with chosen words. Use comma to insert more than one word.
"></textarea>
				</div>

				<div class="eleva-profile-label"> <?php _e('Publish online posts with these words :','elevaweb'); ?>  </div>
				<div class="eleva-profile-input">
				  <textarea name="eleva_pos_word" class="elevaweb-input" rows="3" placeholder="Elevaweb robot will publish only posts with these words. Use comma to insert more than one word.
"></textarea>
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
					<input type="radio" name="image_condition" value="skip_without_images" checked />
					<span class="elevaweb-checkbox-label"><?php _e('Skip posts without image','elevaweb'); ?></span> </div>
				  <div class="elevaweb-checkbox">
					<input type="radio" name="image_condition" value="remove_image"/>
					<span class="elevaweb-checkbox-label"><?php _e('Remove post image','elevaweb'); ?></span> </div>
				  <div class="elevaweb-checkbox">
					<input type="radio" name="image_condition" id="add_image" value="add_image"/>
					<span class="elevaweb-checkbox-label"><?php _e('Add image','elevaweb'); ?></span> </div>
				</div>
				<div class="eleva-profile-input">
				  <textarea name="eleva_image_url" class="elevaweb-input" rows="3" placeholder="<?php _e('It will force use these images and ignore source found images
	(image URL comma separated)','elevaweb'); ?>"></textarea>
				</div>
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
							?>
						<input type="checkbox" name="days[]" id="checkbox-1" value="<?php echo $date; ?>">
						<label for="checkbox-1"></label>
						</li>
						<li>
							<?php
								$nextMonday = strtotime('next monday');
								$date = date('Y-m-d',$nextMonday);
								if($today == "Monday") {
									$date = date('Y-m-d');
								}
							?>
						<input type="checkbox" name="days[]" id="checkbox-2" value="<?php echo $date; ?>" >
						<label for="checkbox-2"></label>
						</li>
						<li>
							<?php
								$nextTuesday = strtotime('next tuesday');
								$date = date('Y-m-d',$nextTuesday);
								if($today == "Tuesday") {
									$date = date('Y-m-d');
								}
							?>
						<input type="checkbox" name="days[]" id="checkbox-3" value="<?php echo $date; ?>" >
						<label for="checkbox-3"></label>
						</li>
						<li>
							<?php
								$nextWednesday = strtotime('next wednesday');
								$date = date('Y-m-d',$nextWednesday);
								if($today == "Wednesday") {
									$date = date('Y-m-d');
								}
							?>
						<input type="checkbox" name="days[]" id="checkbox-4" value="<?php echo $date; ?>" >
						<label for="checkbox-4"></label>
						</li>
						<li>
							<?php
								$nextThursday = strtotime('next thursday');
								$date = date('Y-m-d',$nextThursday);
								if($today == "Thursday") {
									$date = date('Y-m-d');
								}
							?>
						<input type="checkbox" name="days[]" id="checkbox-5" value="<?php echo $date; ?>" >
						<label for="checkbox-5"></label>
						</li>
						<li>
							<?php
								$nextFriday = strtotime('next friday');
								$date = date('Y-m-d',$nextFriday);
								if($today == "Friday") {
									$date = date('Y-m-d');
								}
							?>
						<input type="checkbox" name="days[]" id="checkbox-6" value="<?php echo $date; ?>">
						<label for="checkbox-6"></label>
						</li>
						<li>
							<?php
								$nextSaturday = strtotime('next saturday');
								$date = date('Y-m-d',$nextSaturday);
								if($today == "Saturday") {
									$date = date('Y-m-d');
								}
							?>
						<input type="checkbox" name="days[]" id="checkbox-7" value="<?php echo $date; ?>" >
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
						<strong><?php _e('Hour','elevaweb'); ?>:</strong>
					</div>
					<div class="col-md-7">
						<select name="schedule_hour" required>
							<option value="-1"><?php _e('Hour','elevaweb'); ?></option>
							<?php for($i = 1; $i <= 24; $i++): ?>
							<option <?php if($i == 7){ echo 'selected'; } ?> value="<?= date("H", strtotime("$i:00")); ?>"><?= date("H", strtotime("$i:00")); ?></option>
							<?php endfor; ?>
						</select>
						<select name="schedule_minutes" required>
							<option value="00"><?php _e('00','elevaweb'); ?></option>
							<?php for($i = 0; $i <= 59; $i++): ?>
							<option value="<?= date("i", strtotime("00:$i:00")); ?>"><?= date("i", strtotime("00:$i:00")); ?></option>
							<?php endfor; ?>
						</select>
					</div>
					<div class="clear"></div>
					<div class="validation-error"><p><?php _e('Please select the time.','elevaweb'); ?></p></div>
				</div>
				<input type="hidden" name="save_type" id="save_type" value="" />
				<div class="submit-group">
					<button type="button" class="button button-primary" id="save_schedule" name="save_schedule"><?php _e('Save','elevaweb'); ?></button>
					<button type="button" class="button button-primary" id="save_schedule_published" name="save_schedule_published"><?php _e('Save and Publish','elevaweb'); ?></button>
				</div>
			  </div>
			</div>
		  </div>
		</div>
	  </div>
	  <div class="clear"></div>
	</div>
</form>
