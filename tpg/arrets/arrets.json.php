<?php
require '../../vendor/autoload.php';
use TPGwidget\Data\Stops;

require '../../tpgdata/apikey.php';
$stops = @simplexml_load_file('http://prod.ivtr-od.tpg.ch/v1/GetStops.xml?key='.$key);
$output = [];

if ($stops) {
    foreach ($stops->stops->stop as $stop) {
        $output[] = ['stopName' => Stops::correct($stop->stopName), 'stopCode' => (string)$stop->stopCode];
    }
}

usort($output, function($a, $b) {
    return strcoll($a['stopName'], $b['stopName']);
});

header('Content-Type: application/json');
echo json_encode($output);
