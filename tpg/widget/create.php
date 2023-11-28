<?php
require_once __DIR__.'/../../config.inc.php';

// Validate the stop code
if (!preg_match('/^[A-Z0-9]{4}$/', $_GET['stop'])) {
    die("Erreur : Aucun arrêt spécifié");
}

// Load the stops from the TPG API
//$stops = simplexml_load_file('http://prod.ivtr-od.tpg.ch/v1/GetStops.xml?key='.getenv('TPG_API_KEY'));

// Fall Back To Old API - This marks the final moments of TPGw and Third Party TPG Open Data Apps
$stops = json_decode(file_get_contents("https://preview.genav.ch/api/getStops.json"))->stops;


// Find the widget name
$name = '';
foreach ($stops as $stop) {
    if ($stop->stopCode == $_GET['stop']) {
        $name = $stop->stopName;
    }
}

// Insert the widget in the database
$req = $bdd->prepare('INSERT INTO widgets(stop, name) VALUES(:stop, :name)');
$req->execute([
    'stop' => $_GET['stop'],
    'name' => $name
]);

// Get the widget ID, i.e. 001234
$widgetID = str_pad($bdd->lastInsertId(), 6, '0', STR_PAD_LEFT);

// Redirect to the widget
header("Location: /$widgetID/");
