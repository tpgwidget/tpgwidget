<?php
require_once __DIR__.'/../../../config.inc.php';
use TPGwidget\Data\{Lines, Stops};

function error() {
    http_response_code(500);
    echo json_encode(['error' => 'Server error']);
    die();
}

//$fileUrl = 'http://prod.ivtr-od.tpg.ch/v1/GetPhysicalStops.json?key='.getenv('TPG_API_KEY');

// Fall Back To Old API - This marks the final moments of TPGw and Third Party TPG Open Data Apps
$fileUrl = "http://prod.ivtr.tpg.ch/GetTousArrets.json?transporteur=All";

$fileContents = @file_get_contents($fileUrl);
if (!$fileContents) {
    error();
}

$stops = json_decode(file_get_contents("http://prod.ivtr.tpg.ch/GetTousArrets.json?transporteur=All"))->connexions->connexion;
if (!$stops) {
    error();
}

header('Content-type: application/json; charset=utf-8');

const FEATURED_STOPS = ['CVIN', 'BAIR', 'RIVE', 'AERO', 'COUT', 'PLPA', 'NATI', 'JOCT', 'CRGE', 'ESRT', 'HOPI', 'BHET'];
$output = ['featured' => [], 'all' => [], 'error' => null];

// Stops list
$byStopCode = [];
foreach ($stops as $stop) {
    $lines = [];

    // Lines
    $lineCodes = explode(',', strval($stop->lignes ?? $stop->ligne));
    sort($lineCodes);
    $lines = array_map(function ($lineCode) {
        return Lines::get($lineCode);
    }, $lineCodes);

    $nameFormatted = Stops::format($stop->nomArret ?? '');
    $stopData = [
        'id' => $stop->codeArret ?? null,
        'name' => [
            'formatted' => $nameFormatted,
            'corrected' => strip_tags($nameFormatted),
            'raw' => $stop->nomArret,
        ],
        'lines' => $lines,
    ];

    $output['all'][] = $stopData;
    $byStopCode[$stop->codeArret ?? ''] = $stopData;
}

// Sort stops
uasort($output['all'], function($a, $b) {
    return strcmp(strip_tags($a['name']['formatted']), strip_tags($b['name']['formatted']));
});
$output['all'] = array_values($output['all']);

// Featured stops
foreach (FEATURED_STOPS as $featured) {
    if (array_key_exists($featured, $byStopCode)) {
        $output['featured'][] = $byStopCode[$featured];
    }
}

echo json_encode($output);
