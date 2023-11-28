<?php
require_once __DIR__.'/../../config.inc.php';
use TPGwidget\Data\Stops;


//$file = 'http://prod.ivtr-od.tpg.ch/v1/GetStops.xml?key='.getenv('TPG_API_KEY');
//$stops = @simplexml_load_file($file);

// Fall Back To Old API - This marks the final moments of TPGw and Third Party TPG Open Data Apps
$stops = json_decode(file_get_contents("https://preview.genav.ch/api/getStops.json"))->stops;

$output = [];

foreach ($stops as $stop) {
  $output[trim($stop->stopCode)] = Stops::format($stop->stopName);

  // Remove duplicate stops
  unset($output['BERE']);
  unset($output['IZAC']);
  unset($output['SNDT']);
}

uasort($output, function($a, $b) {
    $a = strip_tags($a);
    $b = strip_tags($b);

    return strcoll($a, $b);
});

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET');
header('Content-type: application/json; charset=utf-8');
echo json_encode($output);
