<?php
require_once __DIR__.'/../../config.inc.php';
use TPGwidget\Data\Stops;

$stops = @simplexml_load_file('https://prod.ivtr-od.tpg.ch/v1/GetStops.xml?key='.getenv('TPG_API_KEY'));
$output = [];

if ($stops) {
    foreach ($stops->stops->stop as $stop) {
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
