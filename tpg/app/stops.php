<?php
require '../../tpgdata/stops.php';
header('Content-type: application/json; charset=utf-8');

require '../../tpgdata/apikey.php';
$file = 'http://prod.ivtr-od.tpg.ch/v1/GetStops.xml?key='.$key;
$stops = @simplexml_load_file($file);

$output = array();

foreach($stops->stops->stop as $stop) {
  $output[trim($stop->stopCode)] = stopFilter($stop->stopName);

  // Remove duplicate stops
  unset($output['BERE']);
  unset($output['IZAC']);
  unset($output['SNDT']);
}

asort($output);

echo json_encode($output);
