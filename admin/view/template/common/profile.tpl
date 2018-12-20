<div id="profile">
  <div>
    <?php if ($image) { ?>
    <img src="<?php echo $image; ?>" alt="<?php echo $firstname; ?> <?php echo $lastname; ?>" title="<?php echo $username; ?>" class="img-thumbnail" />
    <?php } else { ?>
    
    <?php } ?>
  </div>
  <div>
    <h4><?php echo "Menu Principal" ?> 
	    <!-- <?php echo $lastname; ?> -->
	</h4>
    <small><?php echo $user_group; ?></small>
  </div>
</div>
