<?php
require_once __DIR__.'/../../config.inc.php';
use TPGwidget\Data\Stops;

header('Content-type: application/json; charset=utf-8');

$file = 'https://prod.ivtr-od.tpg.ch/v1/GetStops.xml?key='.getenv('TPG_API_KEY');
$stops = @simplexml_load_file($file);

$output = [];

foreach ($stops->stops->stop as $stop) {
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

echo json_encode($output);
