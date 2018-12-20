<div class="tilev">
  <div class="tilev-heading"><?php echo $heading_title; ?> <span class="pull-right">
    <?php if ($percentage > 0) { ?>
    <i class="fa fa-caret-up"></i>
    <?php } elseif ($percentage < 0) { ?>
    <i class="fa fa-caret-down"></i>
    <?php } ?>
    <?php echo $percentage; ?>% </span></div>
  <div class="tilev-body"><i class="fa fa-cogs"></i>
    <h2 class="pull-right"><?php echo $total; ?></h2>
  </div>
  <div class="tilev-footer"><a href="<?php echo $enproceso; ?>"><?php echo $text_view; ?></a></div>
</div>
