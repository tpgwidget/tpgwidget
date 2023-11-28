<?php
require_once __DIR__.'/../../config.inc.php';
use TPGwidget\Data\Stops;

//$file = 'http://prod.ivtr-od.tpg.ch/v1/GetStops.xml?key='.getenv('TPG_API_KEY').'&latitude='.($_GET['latitude'] ?? '').'&longitude='.($_GET['longitude'] ?? '');
//$stops = @simplexml_load_file($file);

// Fall Back To Old API - This marks the final moments of TPGw and Third Party TPG Open Data Apps
$stops = json_decode(file_get_contents("https://preview.genav.ch/api/getNearbyStops.json?accuracy=64".'&latitude='.($_GET['latitude'] ?? '').'&longitude='.($_GET['longitude'] ?? '')))->stops;

$output = [];
if ($stops) {
    foreach ($stops as $stop) {
        $output[] = [
            'stopNameOriginal' => (string)$stop->stopName,
            'stopNameDisplay' => Stops::format($stop->stopName),
            'stopCode' => (string)$stop->stopCode,
        ];
    }
}

header('Content-Type: application/json');
echo json_encode($output);
