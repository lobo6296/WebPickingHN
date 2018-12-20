<?php
function pdf($data, $name) {
		
    $pdf = new DOMPDF;
    $pdf->load_html($data);
    $pdf->render();
    $pdf->stream($name.".pdf");
}
?>
