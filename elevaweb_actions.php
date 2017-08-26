<?php
if( !defined('ABSPATH') ){ exit();}
function elevaweb_add_og_tags_in_post() {
	global $post;
	$post_id = $post->ID;
    ?>
        <meta property="og:title" content="<?php echo get_the_title( $post_id ); ?>" />
		<meta property="og:description" content="<?php echo wp_trim_words(strip_tags( $post->post_content ), 160); ?>" />
		<meta property="og:type" content="article" />
		<meta property="og:url" content="<?php echo get_permalink( $post_id ); ?>" />
		<meta property="og:site_name" content="<?php echo site_url(); ?>" />
		<meta property="og:image" content="<?php echo get_the_post_thumbnail_url( $post_id ); ?>" />
		<?php
		$canonical = get_post_meta($post_id,'custom_canonical_url',true);
		if( get_post_type( $post_id ) == "post" && !empty( $canonical )){
			?>
			<link rel="canonical" href="<?php echo $canonical; ?>" />
			<?php
		}else{
		?>
		<link rel="canonical" href="<?php echo get_permalink( $post_id ); ?>" />
    <?php
		}
}
add_action('wp_head', 'elevaweb_add_og_tags_in_post');
add_action('init', function () {
    remove_action('wp_head', 'rel_canonical');
}, 15);
add_action( 'wp_ajax_reset_password_user', 'elevaweb_reset_password_user' );
function elevaweb_reset_password_user(){
	$email = sanitize_email($_POST['email_id']);
	if( isset( $email ) && sanitize_email( $_POST['email_id'] != "" ) ){
		$request = $_POST;
		$request['is_active'] = 1;	 
				
		$request['user-agent'] = 'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.13) Gecko/20080311 Firefox/2.0.0.13';
		$request['sslverify'] = false;
		
		$results = wp_safe_remote_post( 'http://elevaweb-wp.azurewebsites.net/application/htdocs/api/forgot', array(
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
		if( $results->success == 1 ){
			echo json_encode(array("success"=>1,"message"=>__("Your password has been reset,Please check your email for your new temporary password.!","elevaweb")));
		}else{
			echo json_encode(array("success"=>1,"message"=>__("Email id is not registered.","elevaweb")));
		}
		exit;
	}
}