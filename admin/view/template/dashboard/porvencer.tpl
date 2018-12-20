<div class="tilea">
  <div class="tilea-heading"><?php echo $heading_title; ?> <span class="pull-right">
    <?php if ($percentage > 0) { ?>
    <i class="fa fa-caret-up"></i>
    <?php } elseif ($percentage < 0) { ?>
    <i class="fa fa-caret-down"></i>
    <?php } ?>
    <?php echo $percentage; ?>% </span></div>
  <div class="tilea-body"><i class="fa fa-hourglass-half"></i>
    <h2 class="pull-right"><?php echo $total; ?></h2>
  </div>
  <div class="tilea-footer"><a href="<?php echo $porvencer; ?>"><?php echo $text_view; ?></a></div>
</div>
