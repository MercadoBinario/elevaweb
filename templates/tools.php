<div class="eleva-wrap">
  <?php $header_title = __("Tools",'elevaweb'); ?>
<div class="col-md-12"><?php elevawebHeader($header_title); ?>
  <div class="elevaweb-content">
    <div class="col-md-10">
      <div class="eleva-round-box">
        <div class="e-row">
          <a href="<?php echo admin_url(); ?>admin.php?page=eleva-new-post"><div class="eleva-active"><img src="<?php echo plugins_url('/images/eleva-pencil.png', __DIR__);?>" title="<?php _e('Create a New auto Post','elevaweb'); ?>" /></div></a>
          <?php /*<a href="<?php echo admin_url('admin.php?page=eleva-network'); ?>"><div class="eleva-active"><img src="<?php echo plugins_url('/images/elev-like.png', __DIR__);?>"/></div></a>
          <div class="eleva-off"><img src="<?php echo plugins_url('/images/elev-comp.png', __DIR__);?>"/></div>*/ ?>
          <div class="eleva-off"><img src="<?php echo plugins_url('/images/elev-lock.png', __DIR__);?>"/></div>
          <div class="eleva-off"><img src="<?php echo plugins_url('/images/elev-lock.png', __DIR__);?>"/></div>
          <div class="eleva-off"><img src="<?php echo plugins_url('/images/elev-lock.png', __DIR__);?>"/></div>
        </div>
        <div class="e-row">
          <div class="eleva-off"><img src="<?php echo plugins_url('/images/elev-lock.png', __DIR__);?>"/></div>
          <div class="eleva-off"><img src="<?php echo plugins_url('/images/elev-lock.png', __DIR__);?>"/></div>
          <div class="eleva-off"><img src="<?php echo plugins_url('/images/elev-lock.png', __DIR__);?>"/></div>
          <div class="eleva-off"><img src="<?php echo plugins_url('/images/elev-lock.png', __DIR__);?>"/></div>
        </div>
      </div>
    </div>
  </div>
</div>
