<?php
require_once __DIR__.'/../../config.inc.php';
use TPGwidget\Data\Stops;

//$stops = @simplexml_load_file('http://prod.ivtr-od.tpg.ch/v1/GetStops.xml?key='.getenv('TPG_API_KEY'));
// Fall Back To Old API - This marks the final moments of TPGw and Third Party TPG Open Data Apps
$stops = json_decode(file_get_contents("https://preview.genav.ch/api/getStops.json"))->stops;

$output = [];

if ($stops) {
    foreach ($stops as $stop) {
        $output[] = [
            'stopNameDisplay' => Stops::format($stop->stopName),
            'stopNameRaw' => Stops::correct($stop->stopName),
            'stopNameOriginal' => (string)$stop->stopName,
            'stopCode' => (string)$stop->stopCode,
        ];
    }
}

usort($output, function($a, $b) {
    return strcoll($a['stopNameRaw'], $b['stopNameRaw']);
});

header('Content-Type: application/json');
echo json_encode($output);
