<?php
ini_set('memory_limit', '2G');
ini_set("max_execution_time",6000);

$ExportPDF = new \App\Models\ExportPDF();

header('Content-Type: application/pdf');
header('Content-Disposition: attachment; filename="order_'.$data['id'].'.pdf"');
$ExportPDF->order_sheet($data);