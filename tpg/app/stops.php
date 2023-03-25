<?php
require_once __DIR__.'/../../config.inc.php';
use TPGwidget\Data\Stops;

header('Content-type: application/json; charset=utf-8');

//$file = 'http://prod.ivtr-od.tpg.ch/v1/GetStops.xml?key='.getenv('TPG_API_KEY');
//$stops = @simplexml_load_file($file);

// Fall Back To Old API - This marks the final moments of TPGw and Third Party TPG Open Data Apps
$stops = json_decode(file_get_contents("http://prod.ivtr.tpg.ch/GetTousArrets.json?transporteur=All"))->connexions->connexion;

$output = [];

foreach ($stops as $stop) {
    $output[trim($stop->codeArret)] = Stops::format($stop->nomArret);

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

echo json_encode($output);
