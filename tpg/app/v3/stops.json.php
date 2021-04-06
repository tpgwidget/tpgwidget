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

$output = ['featured' => [], 'all' => []];
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

    $output['all'][] = [
        'id' => $stop['stopCode'] ?? null,
        'nameFormatted' => Stops::format($stop['stopName'] ?? ''),
        'nameRaw' => $stop['stopName'],
        'lines' => $lines,
        'geolocation' => $geolocation,
    ];
}

uasort($output, function($a, $b) {
    return strcmp(strip_tags($a['nameFormatted']), strip_tags($b['nameFormatted']));
});

echo json_encode(array_values($output));
