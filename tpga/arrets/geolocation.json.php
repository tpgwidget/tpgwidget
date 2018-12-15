<?php
require_once __DIR__.'/../../config.inc.php';
use TPGwidget\Data\Stops;

$file = 'https://prod.ivtr-od.tpg.ch/v1/GetStops.xml?key='.getenv('TPG_API_KEY').'&latitude='.($_GET['latitude'] ?? '').'&longitude='.($_GET['longitude'] ?? '');
$stops = @simplexml_load_file($file);

$output = [];
if ($stops) {
    foreach ($stops->stops->stop as $stop) {
        $output[] = [
            'stopNameOriginal' => $stop->stopName,
            'stopNameDisplay' => Stops::format($stop->stopName),
            'stopCode' => (string)$stop->stopCode,
        ];
    }
}

header('Content-Type: application/json');
echo json_encode($output);
