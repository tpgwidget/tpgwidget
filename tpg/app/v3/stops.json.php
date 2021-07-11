<?php
require_once __DIR__.'/../../../config.inc.php';
use TPGwidget\Data\{Lines, Stops};

function error() {
    http_response_code(500);
    echo json_encode(['error' => 'Server error']);
}

$fileUrl = 'https://prod.ivtr-od.tpg.ch/v1/GetPhysicalStops.json?key='.getenv('TPG_API_KEY');
$fileContents = @file_get_contents($fileUrl);
if (!$fileContents) {
    error();
}

$stops = json_decode($fileContents, true);
if (!$stops || !isset($stops['stops'])) {
    error();
}

header('Content-type: application/json; charset=utf-8');

const FEATURED_STOPS = ['CVIN', 'BAIR', 'RIVE', 'AERO', 'PLPA', 'JOCT', 'CRGE', 'ESRT', 'COUT', 'HOPI', 'NATI', 'BHET'];
$output = ['featured' => [], 'all' => [], 'error' => null];

// Stops list
$byStopCode = [];
foreach ($stops['stops'] as $stop) {
    $lines = [];
    $geolocation = null;

    $physicalStops = $stop['physicalStops'];

    // Average geolocation
    $average = function($prop) use ($physicalStops) {
        $result = 0;
        $count = 0;

        foreach ($physicalStops as $stop) {
            $value = $stop['coordinates'][$prop] ?? null;
            if (is_null($value)) {
                continue;
            }

            $result += $value;
            $count += 1;
        }

        if ($count === 0) {
            return null;
        }

        return $result / $count;
    };

    $geolocation = [
        'latitude' => $average('latitude'),
        'longitude' => $average('longitude'),
    ];

    if (is_null($geolocation['latitude']) || is_null($geolocation['longitude'])) {
        $geolocation = null;
    }

    // Lines
    $lineCodes = [];
    foreach ($physicalStops as $physicalStop) {
        foreach ($physicalStop['connections'] as $connection) {
            $lineCodes[] = $connection['lineCode'];
        }
    }
    $lineCodes = array_unique($lineCodes);
    sort($lineCodes);
    $lines = array_map(function ($lineCode) {
        return Lines::get($lineCode);
    }, $lineCodes);

    $nameFormatted = Stops::format($stop['stopName'] ?? '');
    $stopData = [
        'id' => $stop['stopCode'] ?? null,
        'name' => [
            'formatted' => $nameFormatted,
            'corrected' => strip_tags($nameFormatted),
            'raw' => $stop['stopName'],
        ],
        'lines' => $lines,
        'geolocation' => $geolocation,
    ];

    $output['all'][] = $stopData;
    $byStopCode[$stop['stopCode'] ?? ''] = $stopData;
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
