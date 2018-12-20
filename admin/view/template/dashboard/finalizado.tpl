<div class="tileg">
  <div class="tileg-heading"><?php echo $heading_title; ?> <span class="pull-right">
    <?php if ($percentage > 0) { ?>
    <i class="fa fa-caret-up"></i>
    <?php } elseif ($percentage < 0) { ?>
    <i class="fa fa-caret-down"></i>
    <?php } ?>
    <?php echo $percentage; ?>% </span></div>
  <div class="tileg-body"><i class="fa fa-check"></i>
    <h2 class="pull-right"><?php echo $total; ?></h2>
  </div>
  <div class="tileg-footer"><a href="<?php echo $finalizado; ?>"><?php echo $text_view; ?></a></div>
</div>
