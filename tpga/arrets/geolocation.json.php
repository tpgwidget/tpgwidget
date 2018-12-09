<?php
require_once __DIR__.'/../../config.inc.php';
header('Content-type: application/json; charset=utf-8');

$file = 'https://prod.ivtr-od.tpg.ch/v1/GetStops.xml?key='.getenv('TPG_API_KEY').'&latitude='.$_GET['latitude'].'&longitude='.$_GET['longitude'];
$stops = @simplexml_load_file($file);

if (!$stops->stops) {
    die('[]');
}

echo '[';

    $firstStop = true;

    foreach ($stops->stops->stop as $stop) {
        if (!$firstStop) {
            echo ', ';
        }

        if ($firstStop) {
            $firstStop = false;
        }
        echo '{"stopName": "'.$stop->stopName.'", "stopCode": "'.$stop->stopCode.'"}';
    }

echo ']';
