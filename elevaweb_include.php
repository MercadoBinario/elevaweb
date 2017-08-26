<?php
function elevaweb_auth(){
	if( !session_id() )
        session_start();
	if(!isset($_POST['math'])){
		return;
	}
	
	if(sanitize_text_field($_POST['math']) == 'login'){ $remote_url = 'http://elevaweb-wp.azurewebsites.net/application/htdocs/api/login';}
	if(sanitize_text_field($_POST['math']) == 'reg'){ $remote_url = 'http://elevaweb-wp.azurewebsites.net/application/htdocs/api/registration'; }
	if(sanitize_text_field($_POST['math']) == 'forgot'){ $remote_url = 'http://elevaweb-wp.azurewebsites.net/application/htdocs/api/forgot'; }
	if(sanitize_text_field($_POST['math']) == 'change'){ $remote_url = 'http://elevaweb-wp.azurewebsites.net/application/htdocs/api/changepassword'; }
		
	$request = $_POST;
	unset($request['submit']);
	unset($request['math']);
	$request['is_active'] = 1;
	$response = wp_safe_remote_post( $remote_url, array(
		'method' => 'POST',
		'timeout' => 45,
		'redirection' => 5,
		'httpversion' => '1.0',
		'blocking' => true,
		'headers' => array(),
		'body' => $request,
		'cookies' => array()
		)
	);
	$results = json_decode($response['body']);
	
	if($results->success == 1 && sanitize_text_field($_POST['math']) == 'login' && $results->is_active == 1 )
	{
		if($results->data->changepassword == 1)
		{
			$_SESSION['elevaweb_login'] = 1;
			$_SESSION['elevaweb_userdata'] = $results->data;
			$_SESSION['customerId']= $results->data->customer_id;

			$url = admin_url().'admin.php?page=elevaweb';
			echo'<script> window.location="'.$url.'"; </script> ';
		}else{
			$url = admin_url().'admin.php?page=elevaweb';
			$_SESSION['elevaweb_login'] = 1;
			$_SESSION['elevaweb_userdata'] = $results->data;
			$_SESSION['customerId']= $results->data->customer_id;
		    echo'<script> window.location="'.$url.'"; </script> ';
		}
	}else if(sanitize_text_field($_POST['math']) == 'login'){
		if($results->success == 1) {
			$_SESSION['elevaweb_login'] = 1;
			$_SESSION['elevaweb_userdata'] = $results->data;
			$_SESSION['customerId']= $results->data->customer_id;

			$url = admin_url().'admin.php?page=elevaweb';
			echo'<script> window.location="'.$url.'"; </script> ';
		}else{
			$url = admin_url().'admin.php?page=elevaweb&message='.urlencode($results->message);
			echo'<script> window.location="'.$url.'"; </script> ';
		}

	}
	if($results->success==1 && sanitize_text_field($_POST['math']) == 'reg')
	{
		$url = admin_url().'admin.php?page=elevaweb';
		$_SESSION['elevaweb_login'] = 1;
		$_SESSION['elevaweb_userdata'] = $results->data;
		echo'<script> window.location="'.$url.'"; </script> ';
	}else if(sanitize_text_field($_POST['math']) == 'reg'){
		$url = admin_url().'admin.php?page=elevaweb-register&message='.urlencode($results->message);
		echo'<script> window.location="'.$url.'"; </script>';
	}
	if(sanitize_text_field($_POST['math']) == 'forgot'){
		$url = admin_url().'admin.php?page=elevaweb-forgotpassword&message='.urlencode($results->message);
		echo'<script> window.location="'.$url.'"; </script> ';
	}
	if($results->success==1 && sanitize_text_field($_POST['math']) == 'change')
	{
		$url = admin_url().'admin.php?page=eleva-changepassword&message='.urlencode($results->message);
		echo'<script> window.location="'.$url.'"; </script> ';
	}elseif(sanitize_text_field($_POST['math']) == 'change'){
		$url = admin_url().'admin.php?page=eleva-changepassword&message='.urlencode($results->message);
		echo'<script> window.location="'.$url.'"; </script> ';
	}
}
function elevaweb_register(){
	global $pluginDir;
	include($pluginDir."/includes/elevaweb_register.php");
}
function elevaweb_tool(){
	global $pluginDir;
	echo elevaweb_side_menu();
	include($pluginDir."/templates/tools.php");
}

function elevaweb_profile() {
	global $pluginDir;
	echo elevaweb_side_menu();
	include($pluginDir."/templates/profile.php");
}

function elevaweb_post_config() {
	global $pluginDir;
	$post_cat = get_terms('category', array( 'hide_empty' => false ) );
	foreach($post_cat as $term){
		$all_terms[$term->term_id]=$term->name;
	}
	unset($all_terms[1]);
	elevaweb_get_api_feed();
	echo elevaweb_side_menu();
	include($pluginDir."/templates/myposts.php");
}

function elevaweb_log() {
	global $pluginDir;
	echo elevaweb_side_menu();
	include($pluginDir."/templates/log.php");
}

function elevaweb_new_post() {
	global $pluginDir;
	$post_cat = get_terms('category', array( 'hide_empty' => false ) );
	foreach($post_cat as $term){
		$all_terms[$term->term_id] = $term->name;
	}
	unset($all_terms[1]);
	elevaweb_get_api_feed();
	echo elevaweb_side_menu();
	include($pluginDir."/templates/new-post.php");
}

function elevaweb_welcome(){
	global $pluginDir;
	include($pluginDir."/includes/elevaweb_welcome.php");
}
function elevaweb_forgot_password(){
	global $pluginDir;
	include($pluginDir."/includes/elevaweb_forgot_password.php");
}
function elevaweb_change_password(){
	global $pluginDir;
	include($pluginDir."/includes/elevaweb_change_password.php");
}
function elevaweb_side_menu(){
	global $pluginDir;
	include $pluginDir."templates/loading.php";
	wp_enqueue_style( 'elevaweb', plugins_url('css/elevaweb.css', __FILE__));
	wp_enqueue_script( 'elevaweb-autosave', plugins_url('js/sisyphus.js', __FILE__));
}
function elevawebHeader( $heading='' ){
?>
<div class="elevaweb-header">
  <div class="col-md-7"><span class="eleva-heading"><?php echo $heading; ?></span></div>
</div>
<?php
}
function elevaweb_get_api_feed() {
	if( !session_id() )
        session_start();
	$request = array('user-agent'=>'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.13) Gecko/20080311 Firefox/2.0.0.13','sslverify'=>false);
	
	$results = wp_safe_remote_post( 'http://elevaweb-wp.azurewebsites.net/application/htdocs/api/getFeedList', array(
		'method' => 'POST',
		'timeout' => 45,
		'redirection' => 5,
		'httpversion' => '1.0',
		'blocking' => true,
		'headers' => array(),
		'body' => $request,
		'cookies' => array()
		)
	);
	$results = json_decode($results['body']);

	if($results->data){
		foreach($results->data as $feed){
			$feeds[] = $feed->feed_name;
			$feed_web[$feed->feed_name] = $feed->website_url;
			foreach($feed->feeds_path_category as $feed_extra){
				$feed_path[$feed->feed_name][]=$feed_extra->feed_path;
				$feed_url[$feed->feed_name][]= $feed->website_url.$feed_extra->feed_path;
				$feed_cat[$feed->feed_name][]=$feed_extra->feed_category;
			}

			$feed_rule[$feed->feed_name]['rule1_type']=$feed->rule1_type;
			$feed_rule[$feed->feed_name]['rule1_type_value']=$feed->rule1_type_value;
			$feed_rule[$feed->feed_name]['rule1_is_single']=$feed->rule1_is_single;
			$feed_rule[$feed->feed_name]['rule1_is_inner']=$feed->rule1_is_inner;
			$feed_rule[$feed->feed_name]['rule2_type']=$feed->rule2_type;
			$feed_rule[$feed->feed_name]['rule2_type_value']=$feed->rule2_type_value;
			$feed_rule[$feed->feed_name]['rule2_is_single']=$feed->rule2_is_single;
			$feed_rule[$feed->feed_name]['rule2_is_inner']=$feed->rule2_is_inner;
			$feed_rule[$feed->feed_name]['feed_template']=htmlentities($feed->feed_template);
			$feed_rule[$feed->feed_name]['is_strip_parts']=$feed->is_strip_parts;
			$feed_rule[$feed->feed_name]['strip1_type']=$feed->strip1_type;
			$feed_rule[$feed->feed_name]['strip1_value']=$feed->strip1_value;
			$feed_rule[$feed->feed_name]['strip2_type']=$feed->strip2_type;
			$feed_rule[$feed->feed_name]['strip2_value']=$feed->strip2_value;
		}
		$_SESSION['feeds'] = $feeds;
		$_SESSION['feed_rule'] = $feed_rule;
		$_SESSION['feed_web'] = $feed_web;
		$_SESSION['feed_path'] = $feed_path;
		$_SESSION['feed_url'] = $feed_url;
		$_SESSION['feed_cat'] = $feed_cat;
	}
}

function elevaweb_get_feed_catajax(){
	if( !session_id() )
        session_start();
	$data.='<option value="">'.__('--Select Category--','elevaweb').'</option>';
	foreach($_SESSION['feed_cat'][sanitize_text_field($_POST['feed'])] as $key => $cats){
		$data.='<option value="'.$cats.'|'.$key.'">'.$cats.'</option>';
	}
	echo $data;
	die();
}
add_action( 'wp_ajax_getFeedcatajax', 'elevaweb_get_feed_catajax' );
add_action( 'wp_ajax_nopriv_getFeedcatajax', 'elevaweb_get_feed_catajax' );
add_action('wp_ajax_elevaweb_save_feed','elevaweb_save_feed');
function elevaweb_save_feed() {
	if( !session_id() )
        session_start();
	if ( !wp_verify_nonce( $_REQUEST['_wpnonce'], 'elevaweb_save_feed' ) ) {
		$out = array("Error"=>1,"Msg"=>__("Something Went Wrong","elevaweb"));
		echo json_encode( $out );
		exit;
	}
	global $wpdb;
	$feedId = 0;
	if(isset($_REQUEST['feed_id'])) {
		$feedId = $_REQUEST['feed_id'];
	}
	$src_feed = $_REQUEST['src_feed'];
	$feed_cat = $_REQUEST['src_cat'];
	$source_feed_url = $_SESSION['feed_url'];
	$feed_cat_id = 0;
	if(!empty($feed_cat)) {
		$feed_cat = explode('|',$feed_cat);
		$feed_cat_id = $feed_cat[1];
		$feed_cat = $feed_cat[0];
	}
	$post_category = '';
	$post_tags = '';
	if(isset($_REQUEST['blog_cat'])) {
		$post_category = $_REQUEST['blog_cat'];
	}
	if(isset($_REQUEST['eleva_tags'])) {
		$post_tags = $_REQUEST['eleva_tags'];
		$post_tags = explode(',',$post_tags);
	}
	$feed_url = $_SESSION['feed_url'][$src_feed][$feed_cat_id];
	$feed_rule = $_SESSION['feed_rule'][$src_feed];

	// Rule 1
	$getContentType = $_SESSION['feed_rule'][$src_feed]['rule1_type'];
	$contentClass = $_SESSION['feed_rule'][$src_feed]['rule1_type_value'];
	$contentIsSingle = $_SESSION['feed_rule'][$src_feed]['rule1_is_single'];
	$contentIsInner = $_SESSION['feed_rule'][$src_feed]['rule1_is_inner'];

	// Rule 2
	$getContentType2 = $_SESSION['feed_rule'][$src_feed]['rule2_type'];
	$contentClass2 = $_SESSION['feed_rule'][$src_feed]['rule2_type_value'];
	$contentIsSingle2 = $_SESSION['feed_rule'][$src_feed]['rule2_is_single'];
	$contentIsInner2 = $_SESSION['feed_rule'][$src_feed]['rule2_is_inner'];

	$feed_url = str_replace('https','http',$feed_url);
	$feedData = elevaweb_feed_get($feed_url);
	if(!$feedData){
		$out = array("Error"=>1,"Msg"=>__("Your Domain has been forbidden.","elevaweb"));
		echo json_encode( $out );
		exit;
	}
	if(is_array($feedData)) {
		$scheduledTime = '';
		if(isset($_REQUEST['schedule_hour']) && isset($_REQUEST['schedule_minutes'])) {
			if($_REQUEST['schedule_hour'] != "-1" && $_REQUEST['schedule_minutes'] != "-1") {
				$scheduledTime = $_REQUEST['schedule_hour'].":".$_REQUEST['schedule_minutes'];
			}
		}
		$scheduledDate = $_REQUEST['days'];
		if(!empty($scheduledDate)) {
			$scheduledDateNew = array();
			if(!empty($scheduledTime)) {
				foreach($scheduledDate as $date) {
					$scheduledDateNew[] = $date." ".$scheduledTime;
				}
			}
			if(count($scheduledDateNew) > 0) {
				$scheduledDate = $scheduledDateNew;
			}
			$scheduledDate = serialize($scheduledDate);
		}
		else {
			$date = date('Y-m-d');
			$scheduledDate = array($date);
			$scheduledDateNew = array();
			if(!empty($scheduledTime)) {
				foreach($scheduledDate as $date) {
					$scheduledDateNew[] = $date." ".$scheduledTime;
				}
			}
			if(count($scheduledDateNew) > 0) {
				$scheduledDate = $scheduledDateNew;
			}
			$scheduledDate = serialize($scheduledDate);
		}

		$positivekeyword = '';
		$negativekeyword = '';
		if(isset($_REQUEST['eleva_pos_word'])) {
			$positivekeyword = $_REQUEST['eleva_pos_word'];
			if(!empty($positivekeyword)) {
				if(strpos($positivekeyword,',') !== false) {
					$positivekeyword = explode(',',$positivekeyword);
				}
				else {
					$positivekeyword = array($positivekeyword);
				}
			}
		}
		if(isset($_REQUEST['eleva_neg_word'])) {
			$negativekeyword = $_REQUEST['eleva_neg_word'];
			if(!empty($negativekeyword)) {
				if(strpos($negativekeyword,',') !== false) {
					$negativekeyword = explode(',',$negativekeyword);
				}
				else {
					$negativekeyword = array($negativekeyword);
				}
			}
		}
		$postTitle = '';
		$postContent = '';
		$skipFeed = false;
		$skipFeedImg = false;
		$elevaTitle = '';
		$postScheduleCounter = 0;
		$postNotScheduleCounter = 0;
		if(isset($_REQUEST['eleva_title'])) {
			$elevaTitle = $_REQUEST['eleva_title'];
		}

		$skipWithoutImages = '';
		$removePostImages = '';
		if(isset($_REQUEST['image_condition']) && $_REQUEST['image_condition'] == "skip_without_images") {
			$skipWithoutImages = true;
		}
		if(isset($_REQUEST['image_condition']) && $_REQUEST['image_condition'] == "remove_image") {
			$removePostImages = true;
		}

		$addImages = '';
		$imagesUrls = '';
		if(isset($_REQUEST['image_condition']) && $_REQUEST['image_condition'] == "add_image") {
				$addImages = $_REQUEST['add_images'];
				if(isset($_REQUEST['eleva_image_url'])) {
					$imagesUrls = $_REQUEST['eleva_image_url'];
					if(!empty($imagesUrls)) {
						//$imagesUrls = explode(PHP_EOL,$imagesUrls);
						$imagesUrls = explode(',',$imagesUrls);
						/* if(!is_array($imagesUrls)) {
							$imagesUrls = explode(',',$imagesUrls);
						}else {
							$imagesUrls = $imagesUrls;
						} */
					}
				}
		}
		
		$postTags = implode(',',$post_tags);

		$metaFields = array('positive_word' => $positivekeyword, 'negative_keyword' => $negativekeyword, 'skip_without_image' => $skipWithoutImages, 'remove_images' => $removePostImages, 'change_title' => $_REQUEST['eleva_title'], 'tags' => $postTags);

		if(count($imagesUrls) && !empty($imagesUrls) && $imagesUrls && !empty($imagesUrls)) {
				$metaFields['add_images'] = 1;
				$metaFields['images_url'] = serialize($imagesUrls);
		}

		$metaFields = serialize($metaFields);

		if($feedData) {
			
			foreach($feedData as $feed) {
				//$save_type = sanitize_text_field($_POST['save_type']);
				if((isset( $_REQUEST['save_type'] ) && $_REQUEST['save_type'] == "save_schedule" ) || $feedId){

					$post_id = 0;
					$prefix = $wpdb->prefix;
					$feed_rule_ser = serialize($feed_rule);
					if(!$feedId){

						$sql = "INSERT INTO {$wpdb->prefix}elevaweb_scheduled_post(feed,feed_url,feed_rule,feed_meta,feed_category,post_category,scheduled_date,status,running) VALUES('{$src_feed}','{$feed_url}','{$feed_rule_ser}','{$metaFields}','{$feed_cat}','{$post_category}','{$scheduledDate}',1,1);";

						$insert = $wpdb->query($sql);

						if($wpdb->rows_affected == 1) {
							$postScheduleCounter++;
							$_SESSION['elevaweb_message'] = "Your post has been scheduled. You can check it in myposts";
				
							$out = array("Error"=>0,"Msg"=>__("Your post has been scheduled. You can check it in myposts","elevaweb"));
							echo json_encode( $out );
							exit;
						}else{
							continue;
							$postNotScheduleCounter++;
							$_SESSION['elevaweb_message'] = "Error while processing your request. Please try again.";
							$out = array("Error"=>1,"Msg"=>__("Error while processing your request. Please try again.","elevaweb"));
							echo json_encode( $out );
							exit;
						}
					}else{
						$sql = "UPDATE {$wpdb->prefix}elevaweb_scheduled_post SET feed = '{$src_feed}',feed_url = '{$feed_url}',feed_rule = '{$feed_rule_ser}',feed_meta = '{$metaFields}',feed_category = '{$feed_cat}',post_category = '{$post_category}',scheduled_date = '{$scheduledDate}' WHERE ID = {$feedId}";

						$update = $wpdb->query($sql);

						if($wpdb->rows_affected == 1){
							$_SESSION['elevaweb_message'] = "Your post has been scheduled. You can check it in myposts";
							
							$out = array("Error"=>0,"Msg"=>__("Your post has been scheduled. You can check it in myposts","elevaweb"));
							echo json_encode( $out );
							exit;
						}else{
							$_SESSION['elevaweb_message'] = "Error while processing your request. Please try again.";
							
							$out = array("Error"=>1,"Msg"=>__("Error while processing your request. Please try again.","elevaweb"));
							echo json_encode( $out );
							exit;
						}
					}
				}else if((isset( $_REQUEST['save_type'] ) && $_REQUEST['save_type'] == "save_schedule_published" )) {
					
					$feed_rule_ser = serialize($feed_rule);

					$linkContent = '';
					$postContent = '';
					$linkData = '';
					$postFound = 0;
					$linkData = wp_remote_request($feed->link);
					if ( is_wp_error( $linkData ) ) {
						$error_message = $linkData->get_error_message();
						
						wp_die($error_message." at line number 170");
					}
					
					$linkContent = wp_remote_retrieve_body($linkData);

					$linkContent = preg_replace('#<script(.*?)>(.*?)</script>#is', '', $linkContent);
					
					$skipFeed = false;
					$skipFeedImg = false;
					$dom = new DOMDocument();
					$dom->preserveWhiteSpace = false;
					$dom->formatOutput       = true;
					@$dom->loadHTML(mb_convert_encoding($linkContent, 'HTML-ENTITIES', 'UTF-8'));

					$xpath = new DOMXPath($dom);
					$postTitle = $feed->title;
					$pTitle = $feed->title;
					if($getContentType == "0") {
						$tags = $dom->getElementById($contentClass);
						if($tags) {
							foreach ($tags as $tag) {
								$postContent .= elevaweb_get_inner_html($tag);
								if($contentIsSingle) {
									break;
								}
							}
						}
					}
					else if($getContentType == "1") {
						$classname = $contentClass;
						$tags = $xpath->query("//*[contains(concat(' ', normalize-space(@class), ' '), ' $classname ')]");
						foreach ($tags as $tag) {
							$postContent .= elevaweb_get_inner_html($tag);
							if($contentIsSingle) {
								break;
							}
						}
					}
					else if($getContentType == "2") {
						$tags = $xpath->query($contentClass);
						foreach ($tags as $tag) {
							$postContent .= elevaweb_get_inner_html($tag);
							if($contentIsSingle) {
								break;
							}
						}
					}

					if(!empty($contentClass2) && !empty($getContentType2)) {
						if($getContentType2 == "0") {
							$tags = $dom->getElementById($contentClass2);
							if($tags) {
								foreach ($tags as $tag) {
									$postContent .= elevaweb_get_inner_html($tag);
									if($contentIsSingle) {
										break;
									}
								}
							}
						}
						else if($getContentType2 == "1") {
							$classname = $contentClass2;
							$tags = $xpath->query("//*[contains(concat(' ', normalize-space(@class), ' '), ' $classname ')]");
							foreach ($tags as $tag) {
								$postContent .= elevaweb_get_inner_html($tag);
								if($contentIsSingle) {
									break;
								}
							}
						}
						else if($getContentType2 == "2") {
							$tags = $xpath->query($contentClass2);
							foreach ($tags as $tag) {
								$postContent .= elevaweb_get_inner_html($tag);
								if($contentIsSingle) {
									break;
								}
							}
						}
					}
					
					if(!empty($elevaTitle)) {
						$postTitle = $elevaTitle.$postTitle;
					}
					$skipFeed = false;
					if(!empty($positivekeyword)) {
						if(elevaweb_positive_keyword($postTitle,$positivekeyword) || elevaweb_positive_keyword($postContent,$positivekeyword)) {
							$skipFeed = false;
						}else{
							$skipFeed = true;
						}
					}

					if(!empty($negativekeyword)) {
						if(elevaweb_negative_keyword($postTitle,$negativekeyword) || elevaweb_negative_keyword($postContent,$negativekeyword)) {
							$skipFeed = true;
						}else {
							$skipFeed = false;
						}
					}

					if(!empty($skipWithoutImages) && $skipWithoutImages) {
						$res = elevaweb_find_images($postContent);
						if($res == 0 || $res == false) {
							$skipFeedImg = true;
						}
					}
					else {
						$skipFeedImg = false;
					}
					if($removePostImages && !empty($removePostImages)) {
						$postContentWithoutImages = elevaweb_remove_images($postContent);
						if($postContentWithoutImages) {
							$postContent = $postContentWithoutImages;
						}
					}
					
					//var_dump($postContent);

					$postContentWithLocalImg = elevaweb_upload_images_to_wp($postContent);

					if($postContentWithLocalImg && !is_null($postContentWithLocalImg)) {
						$postContent = $postContentWithLocalImg;
					}
					
					
					if($feed_rule['is_strip_parts'] == "1") {
						$feed_strip1_type = $feed_rule['strip1_type'];
						$feed_strip1_value = $feed_rule['strip1_value'];
						$feed_strip2_type = $feed_rule['strip2_type'];
						$feed_strip2_value = $feed_rule['strip2_value'];
						$strippedContent1 = elevaweb_strip_content($postContent,$feed_strip1_type,$feed_strip1_value);
						$strippedContent2 = elevaweb_strip_content($postContent,$feed_strip2_type,$feed_strip2_value);
						$newContent = '';
						if($strippedContent1 !== false) {
							$newContent = $strippedContent1;
						}
						if($strippedContent2 !== false) {
							$newContent .= $strippedContent2;
						}

						if(!empty($newContent)) {
							$postContent = $newContent;
						}
					}

					$postContent = strip_tags($postContent,'<b><p><i><img><ul><li><h1><h2><h3><h4><h5><h6>');
					if(strlen($postContent) > 2000) {
						$postContent = elevaweb_truncate($postContent,2000,array('html'=>true));
					}

					if(!empty($postTitle) && !empty($postContent) && $skipFeed === false && strlen($postContent) > 2000){
						
						if(is_object($postTitle)) {
							$key = 0;
							$postTitle = (array) $postTitle;
							$postTitle = $postTitle[0];
						}

						$new_title = utf8_decode( $pTitle );
						$post_id = 0;

						$post = $wpdb->get_var( "SELECT COUNT(*) FROM $wpdb->posts WHERE post_name = '".sanitize_title( $new_title )."' AND post_type = 'post'" );

						if(!$post){
							if($removePostImages != true){
								$ele_feed = $_REQUEST['src_feed'];
								if(strtolower( $ele_feed ) == "endeavor" || strripos( $ele_feed, "Marketing Digital") != ""){
									if(strripos( $ele_feed, "Marketing Digital") != ""){
										
										if(!empty($feed->link)){
											$fea_image = elevaweb_get_og_image_from_url( $feed->link[0] );
											if( $fea_image ){
												$ij = elevaweb_add_image_to_post( array( utf8_decode( $fea_image ) ) );
												$postContent = $ij.$postContent;
											}
										}
									}else{
										if(!empty($feed->link)){
											$fea_image = elevaweb_get_og_image_from_url( $feed->link );

											if( $fea_image ){
												$ij = elevaweb_add_image_to_post( array( utf8_decode( $fea_image ) ) );
												$postContent = $ij.$postContent;
											}
										}
									}
								}
							}
							if(!empty($imagesUrls)) {
								if(is_array($imagesUrls)){
									if(count($imagesUrls)) {
										$postContent = elevaweb_replace_uploadimages_to_wp($postContent,$imagesUrls);
									}
								}
							}
							$postContent .= "<p><a href='".$feed->link."' rel='nofollow'>".__('Click here to view full post','elevaweb')."</a></p>";

							$args = array(
								'post_author' => get_current_user_id(),
								'post_title' => $postTitle,
								'post_content' => $postContent,
								'post_name' => sanitize_title( $new_title ),
								'post_status' => 'publish',
								'post_type' => 'post'
							);
							
							$post_id = wp_insert_post($args);
							
							if($post_id){
								update_post_meta($post_id,'custom_canonical_url',"$feed->link");
							}

							if(!empty($post_category)) {
								wp_set_post_categories($post_id,array($post_category));
							}
							if(!empty($post_tags)) {
								if(is_array($post_tags)) {
									wp_set_post_tags($post_id,$post_tags);
								}
								else {
									wp_set_post_tags($post_id,array($post_tags));
								}
							}
							
							$published_date = get_the_date('Y-m-d H:i:s',$post_id);
							$userData = $_SESSION['elevaweb_userdata'];
							// LOG the post
							$SQL = "INSERT INTO {$wpdb->prefix}elevaweb_scheduled_post_log(feed,feed_url,post_title,post_id,original_post_url,feed_category,post_category,published_date,author_id) VALUES('{$src_feed}','{$feed_url}','{$postTitle}','{$post_id}','{$feed->link}','{$feed_cat}','{$post_category}','{$published_date}',{$userData->customer_id})";
							$wpdb->query($SQL);

							$sql = "INSERT INTO {$wpdb->prefix}elevaweb_scheduled_post(feed,feed_url,feed_rule,feed_meta,feed_category,post_category,scheduled_date,status,running) VALUES('{$src_feed}','{$feed_url}','{$feed_rule_ser}','{$metaFields}','{$feed_cat}','{$post_category}','{$scheduledDate}',1,1);";

							
							$insert = $wpdb->query($sql);

							if($post_id) {
								$_SESSION['elevaweb_message'] = "Post is saved successfully. <a href='".get_permalink($post_id)."'>Click here to view the post.</a>";
								
								$out = array("Error"=>0,"Msg"=>__("Post is saved successfully. <a href='".get_permalink($post_id)."'>Click here to view the post.</a>","elevaweb"));
								echo json_encode( $out );
								exit;
							}
							else {
								continue;
							}
							$postFound++;
							die;
						}
						else {
							continue;
						}
					}
				}
			}
			if($postFound == 0) {
				$out = array("Error"=>1,"Msg"=>__("Sorry! No post found.","elevaweb"));
				echo json_encode( $out );
				exit;
			}
		}
	}else {
		$_SESSION['elevaweb_message'] = __("Sorry! No post found.","elevaweb");
		$_SESSION['elevaweb_error'] = true;
		if ( wp_get_referer() ){
			wp_safe_redirect( wp_get_referer() );
		}
		die;
	}
}

function elevaweb_pause_post( $id = 0 ) {
	if( !session_id() )
        session_start();
	global $wpdb;
	if($id) {
		$SQL = "SELECT * FROM {$wpdb->prefix}elevaweb_scheduled_post WHERE ID={$id}";
		$result = $wpdb->get_results($SQL);
		if($wpdb->num_rows > 0) {
			$SQL = "UPDATE {$wpdb->prefix}elevaweb_scheduled_post SET running=0 WHERE ID={$id}";
			$update = $wpdb->query($SQL);
			if($update) {
				$_SESSION['elevaweb_message'] = __("1 feed has been paused.","elevaweb");
			}
			else {
				$_SESSION['elevaweb_message'] = __("Failed to update the feed.","elevaweb");
			}
		}
		else {
			$_SESSION['elevaweb_message'] = __("Failed to update the feed.","elevaweb");
		}
		wp_safe_redirect( admin_url('admin.php?page=eleva-post-config') );
		exit;
	}
}

function elevaweb_resume_post( $id = 0 ) {
	if( !session_id() )
        session_start();
	global $wpdb;
	if($id) {
		$SQL = "SELECT * FROM {$wpdb->prefix}elevaweb_scheduled_post WHERE ID={$id}";
		$result = $wpdb->get_results($SQL);
		if($wpdb->num_rows > 0) {
			$SQL = "UPDATE {$wpdb->prefix}elevaweb_scheduled_post SET running=1 WHERE ID={$id}";
			$update = $wpdb->query($SQL);
			if($update) {
				$_SESSION['elevaweb_message'] = __("1 feed has been resumed.","elevaweb");
			}
			else {
				$_SESSION['elevaweb_message'] = __("Failed to update the feed.","elevaweb");
			}
		}
		else {
			$_SESSION['elevaweb_message'] = __("Failed to update the feed.","elevaweb");
		}
		wp_safe_redirect( admin_url('admin.php?page=eleva-post-config') );
		exit;
	}
}

function elevaweb_delete_post( $id = 0 ) {
	if( !session_id() )
        session_start();
	global $wpdb;
	if($id) {
		$SQL = "SELECT * FROM {$wpdb->prefix}elevaweb_scheduled_post WHERE ID={$id}";
		$result = $wpdb->get_results($SQL);
		if($wpdb->num_rows > 0) {
			$SQL = "DELETE FROM {$wpdb->prefix}elevaweb_scheduled_post WHERE ID={$id}";
			$update = $wpdb->query($SQL);
			if($update) {
				$_SESSION['elevaweb_message'] = __("1 feed has been deleted.","elevaweb");
			}
			else {
				$_SESSION['elevaweb_message'] = __("Failed to delete the feed.","elevaweb");
			}
		}
		else {
			$_SESSION['elevaweb_message'] = __("Failed to delete the feed.","elevaweb");
		}
		wp_safe_redirect( admin_url('admin.php?page=eleva-post-config') );
		exit;
	}
}

function elevaweb_get_saved_feeddata( $id = 0 ) {
	global $wpdb;
	if($id) {
		$sql = "SELECT * FROM {$wpdb->prefix}elevaweb_scheduled_post WHERE ID = {$id}";
		$results = $wpdb->get_row($sql);
		if($wpdb->num_rows) {
			return $results;
		}
	}
}

function elevaweb_get_current_day_schedule( $dateArray = '', $day = '' ) {
	if(!empty($dateArray) && !empty($day)) {
		foreach($dateArray as $date) {
			$currentDay = strtolower(date('l',strtotime($date)));
			if($currentDay == $day) {
				return date('Y-m-d',strtotime($date));
			}
			else {
				continue;
			}
		}
	}
	return false;
}

function elevaweb_get_scheduled_time($scheduledDate = '') {
	if($scheduledDate) {
		foreach($scheduledDate as $date) {
			$time = explode(' ',$date);
			if(isset($time[1])) {
				return $time[1];
			}
		}
	}
	return false;
}
add_action('admin_post_edit_profile','elevaweb_edit_profile');
function elevaweb_edit_profile() {
	if( !session_id() )
        session_start();
	$name = $_REQUEST['full_name'];
	$email = $_REQUEST['email'];
	$phone = $_REQUEST['phone'];
	$register_date = $_REQUEST['register_date'];
	$profile_url = $_REQUEST['profile_url'];
	if(isset($_FILES['profile_picture']) && !empty($_FILES['profile_picture'])){
		$picture = base64_encode( file_get_contents($_FILES['profile_picture']['tmp_name']) );
	}else{
		$picture = '';
	}

	$website = site_url();
	$is_active = 1;
	$payment_status = 1;
	$id = $_REQUEST['id'];
	$args = array (
		'body' => array('full_name' => $name, 'email_id' => $email, 'mobile_no' => $phone, 'website' => $website, 'is_active' => $is_active, 'payment_status' => $payment_status, 'id' => $id, 'profile_picture' => $picture, 'register_date' => $register_date, 'profile_url' => $profile_url)
	);
	$response = wp_remote_post('http://elevaweb-wp.azurewebsites.net/application/htdocs/api/editRegistration',$args);
	if ( is_wp_error( $response ) ) {
	   $error_message = $response->get_error_message();
	   echo "Something went wrong: $error_message";
	} else {
	   $body = wp_remote_retrieve_body($response);
		
	   if($body) {
			$resp = json_decode($body);
			
			$_SESSION['elevaweb_message'] = $resp->message;
			if(isset($resp->success)) {
			   if($resp->success == "1") {
					$_SESSION['elevaweb_userdata'] = $resp->data;
			   }
			}
			if( wp_get_referer() ){
				wp_safe_redirect( wp_get_referer() );
			}
			die;
	   }
	}
}