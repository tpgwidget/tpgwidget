<?php
// Test against GéNav's Database
if (php_sapi_name() != "cli")
    return;
require_once "vehicules.php";
$vehs = json_decode(file_get_contents("https://app.genav.ch/hosted_app-V2/vehicles.json"));
$missingVehs = [];
$missingInfo = [];

foreach ($vehs as $id=>$veh){
    $v = new Vehicule($id);
    if ($v->unknown)
        $missingVehs[] = $id . " - " . $veh->Brand . " " . $veh->Model;
    if (
        !isset($v->year) ||
        !isset($v->img_author) ||
        !isset($v->img_link) ||
        $v->img == "" ||
        $v->icon == "" ||
        $v->icon == "notfound" ||
        $v->icon == "soustraitant" ||
        $v->places_assises == null ||
        $v->places_debout == null ||
        $v->places_totales == null
    )
        $missingInfo[] = $id . " - " . $veh->Brand . " " . $veh->Model;
}

echo "Missing Vehicles: ";
print_r($missingVehs);
echo "Missing Information: ";
print_r($missingInfo);
