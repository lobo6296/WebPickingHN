<div class="tilew">
  <div class="tilew-heading"><?php echo $heading_title; ?> <span class="pull-right">
    <?php if ($percentage > 0) { ?>
    <i class="fa fa-caret-up"></i>
    <?php } elseif ($percentage < 0) { ?>
    <i class="fa fa-caret-down"></i>
    <?php } ?>
    <?php echo $percentage; ?>% </span></div>
  <div class="tilew-body"><i class="fa fa-exclamation-triangle"></i>
    <h2 class="pull-right"><?php echo $total; ?></h2>
  </div>
  <div class="tilew-footer"><a href="<?php echo $demorado; ?>"><?php echo $text_view; ?></a></div>
</div>
