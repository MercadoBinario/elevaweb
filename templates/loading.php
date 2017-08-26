<?php
	global $pluginUrl;
?>
<div class="elevaweb-loader">
	<img src="<?php echo $pluginUrl; ?>/images/loading.gif" alt="" />
</div>
<script>
try {
	jQuery(window).load(function() {
		jQuery('.elevaweb-loader').hide();
	});
	jQuery( window ).unload(function() {
		jQuery('.elevaweb-loader').show();
	});
}
catch(err) {}
</script>
