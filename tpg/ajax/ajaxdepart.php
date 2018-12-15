<?php
require_once __DIR__.'/../../config.inc.php';
use TPGwidget\Data\{Lines, Stops};

include '../../tpgdata/vehicules/vehicules.php';

if (!isset($_GET['id'])) { // Si aucun arrêt spécifié
    die("Erreur : Aucun d&eacute;part sp&eacute;cifi&eacute;");
}

$file = 'https://prod.ivtr-od.tpg.ch/v1/GetThermometer.xml?key='.getenv('TPG_API_KEY').'&departureCode=' . $_GET["id"];
$thermometer = @simplexml_load_file($file);

if (!$thermometer) {
    $erreur[] = '<div class="boxinstall"><strong>Erreur :</strong> Serveur TPG indisponible</div>';
}

$line = Lines::get($thermometer->lineCode);
$color = $line['background'];
?>
<div class="navbar">
    <div class="navbar-inner">
        <div class="left">
            <a href="#" class="back link icon-only">
                <i class="icon icon-back"></i>
            </a>
        </div>
        <div class="center sliding"><span class="lineCode <?= $line['text'] === '#000000' ? 'b' : '' ?>"><?= $thermometer->lineCode ?></span> ➜ <?= Stops::format($thermometer->destinationName ?? '') ?></div>
        <div class="right">
            <a href="#" class="open-panel link icon-only"><i class="icon icon-panel"></i></a>
        </div>
        <div class="subnavbar sliding">
            <div class="buttons-row"><a href="#" class="button show-m active">Temps</a><a href="#" class="button show-h">Heure</a></div>
        </div>
    </div>
</div>
<div class="pages">
    <div data-page="depart-<?= $line['background'] ?><?= $line['text'] === '#000000' ? '-b' : '' ?>" class="page page-depart with-subnavbar">
        <div class="page-content">

            <div class="content-block" style="margin: 10px;"></div>

            <?php
            $vehicleNo = filter_input(INPUT_GET, 'vehicleNo', FILTER_VALIDATE_INT); // Sometimes, GetNextDepartures provides a vehicle number while GetThermometer doesn’t
            if (isset($thermometer->vehiculeNo) || $vehicleNo) {
                $vehicule = new Vehicule($thermometer->vehiculeNo ?? $vehicleNo); // Afficher véhicule
                $vehicule->renderCard_iOS();
            }
            ?>

            <div class="card">
                <div class="card-content">
                    <div class="list-block parcours">
                        <ul>
                            <?php $avancee = 'previous';
                            foreach ($thermometer->steps->step as $step) { ?>
                                <li>
                                    <?php
                                    if ($avancee == "current") {
                                        $avancee = "";
                                    }

                                    if (levenshtein($thermometer->stop->stopName, $step->stop->stopName) == 0) {
                                        $avancee = 'current';
                                    }
                                    ?>
                                    <a href="/ajax/page/<?= $step->stop->stopCode ?>/<?= rawurlencode($step->stop->stopName ?? '') ?>" class="item-link item-content <?= $avancee ?>">
                                        <div class="item-media">
                                            <i class="t icon l<?=str_replace('+', '', $thermometer->lineCode) ?>"></i>
                                        </div>
                                        <div class="item-inner">
                                            <div class="item-title"><?= Stops::format($step->stop->stopName ?? '') ?></div>
                                            <div class="item-after">
                                                <span class="h"><?= date('H:i', strtotime($step->timestamp)) ?></span>
                                                <span class="m">
                                                    <?php if (intval($step->arrivalTime)) {
                                                        echo $step->arrivalTime." min";
                                                    } ?>
                                                </span>
                                            </div>
                                        </div>
                                    </a>
                                </li>
                            <?php } ?>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <?php if ($thermometer->disruptions->disruption) { ?>
            <div class="disruptions-data">
                <?php
                $disruptions = [];
                foreach ($thermometer->disruptions->disruption as $disruption) {
                    $disruptions[] = $disruption;
                }
                echo json_encode($disruptions);
                ?>
            </div>
        <?php } ?>
    </div>
</div>
