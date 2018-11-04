<?php
require_once __DIR__.'/../../config.inc.php';
use TPGwidget\Data\Stops;

$file = 'http://prod.ivtr-od.tpg.ch/v1/GetStops.xml?key='.getenv('TPG_API_KEY');
$stops = @simplexml_load_file($file);

$output = array();

foreach ($stops->stops->stop as $stop) {
  $output[trim($stop->stopCode)] = Stops::correct($stop->stopName);

  // Remove duplicate stops
  unset($output['BERE']);
  unset($output['IZAC']);
  unset($output['SNDT']);
}

asort($output);

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET');
header('Content-type: application/json; charset=utf-8');
echo json_encode($output);
