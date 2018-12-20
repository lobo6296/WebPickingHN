<div class="table-responsive">
  <table class="table table-bordered table-hover">
    <thead>
      <tr>
        <td class="text-right"><?php echo $column_correlativo; ?></td>
        <td class="text-left"><?php echo $column_cod_prueba; ?></td>
        <td class="text-right"><?php echo $column_fecha; ?></td>
        <td class="text-left"><?php echo $column_resultado; ?></td>
      </tr>
    </thead>
    <tbody>
      <?php if ($histories) { ?>
      <?php foreach ($histories as $history) { ?>
      <tr>
        <td class="text-right"><?php echo $history['correlativo']; ?></td>
        <td class="text-left"><?php echo $history['cod_prueba']; ?></td>
        <td class="text-right"><?php echo $history['fecha']; ?></td>
        <td class="text-left"><?php echo $history['resultado']; ?></td>
      </tr>
      <?php } ?>
      <?php } else { ?>
      <tr>
        <td class="text-center" colspan="4"><?php echo $text_no_results; ?></td>
      </tr>
      <?php } ?>
    </tbody>
  </table>
</div>
<div class="row">
  <div class="col-sm-6 text-left"><?php echo $pagination; ?></div>
  <div class="col-sm-6 text-right"><?php echo $results; ?></div>
</div>
