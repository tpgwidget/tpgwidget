<?php
require_once __DIR__.'/../../config.inc.php';
use TPGwidget\Data\{Lines, Stops};

include '../../tpgdata/vehicules/vehicules.php';

if (!isset($_GET['id'])) { // Si aucun arrêt spécifié
    die("Erreur : Aucun d&eacute;part sp&eacute;cifi&eacute;");
}

//$file = 'http://prod.ivtr-od.tpg.ch/v1/GetThermometer.xml?key='.getenv('TPG_API_KEY').'&departureCode=' . $_GET["id"];
//$thermometer = @simplexml_load_file($file);

// Fall Back To Old API - This marks the final moments of TPGw and Third Party TPG Open Data Apps
$thermometer = json_decode(file_get_contents("https://preview.genav.ch/api/getThermometer.json?departureCode=" . $_GET["id"]))->thermometer;

if (!$thermometer) { ?>
    <div class="page" data-page="error">
        <div class="navbar">
            <div class="navbar-inner">
                <div class="left">
                    <a href="#" class="back link icon-only">
                        <i class="icon icon-back"></i>
                    </a>
                </div>
                <div class="center">Erreur</div>
            </div>
        </div>
        <div class="page-content">
            <div class="content-block">
                <p>Impossible de contacter les serveurs TPG</p>
            </div>
        </div>
    </div>
<?php } else {
    $nextDeps = json_decode(file_get_contents("https://preview.genav.ch/api/getNextDepartures.json?stopCode=" . $thermometer->stop->stopCode))->nextDepartures;
    $departureData = [
        "ligne"=>"",
        "vehiculeNo"=>""
    ];
    foreach ($nextDeps->departures as $prochainDepart) {
        if (($prochainDepart->departureCode??"") == $_GET['id']) {
            $departureData = [
                "ligne"=>$prochainDepart->connection->lineCode,
                "vehiculeNo"=>$prochainDepart->vehiculeNo
            ];
            break;
        }
    }
$line = Lines::get($departureData['ligne']);
$color = $line['background'];
?>
<div data-page="depart-<?= $color ?>" class="page page-depart <?= $line['text'] === '#000000' ? 'b' : '' ?>">
    <div class="navbar" style="background-color: <?= $color ?>">
      <div class="navbar-inner">
        <div class="left">
          <a href="#" class="back link icon-only">
            <i class="icon icon-back"></i>
           </a>
        </div>
        <div class="center"><span class="lineCode <?= $line['text'] === '#000000' ? 'b' : '' ?>"><?= $departureData['ligne'] ?></span> ➜ <?= Stops::format($thermometer->destinationName ?? '') ?></div>
      </div>
    </div>
    <div class="toolbar tabbar" style="background-color: <?= $color ?>">
        <div class="toolbar-inner">
            <a href="#tab-1" class="tab-link show-m active">Temps</a>
            <a href="#tab-2" class="tab-link show-h">Heure</a>
        </div>
    </div>
    <div class="page-content">
        <div class="tabs">
            <div id="tab-1" class="tab active"></div>
            <div id="tab-2" class="tab active"></div>
        </div>

        <div class="content-block" style="margin: 10px;">
          </div>

        <?php
        $vehicleNo = filter_input(INPUT_GET, 'vehicleNo', FILTER_VALIDATE_INT); // Sometimes, GetNextDepartures provides a vehicle number while GetThermometer doesn’t
        if (isset($departureData['vehicleNo']) || $vehicleNo) {
            $vehicule = new Vehicule($departureData['vehicleNo'] ?? $vehicleNo); // Afficher véhicule
            $vehicule->renderCard_Android();
        }
        ?>

        <div class="card"><div class="card-content">
        <div class="list-block parcours">
        <ul>
            <?php $avancee = 'previous'; ?>
            <?php
            $translations = [
                "PNEF"=>"PNEV",
                "CRCD"=>"CRCO",
            ];
            foreach ($thermometer->steps as $step) {
                if (array_key_exists($step->stop->stopCode, $translations))
                    $step->stop->stopCode = $translations[$step->stop->stopCode];?>
                <li>
                    <?php
                    if ($avancee == "current") {
                        $avancee = "";
                    }

                    if (levenshtein($thermometer->stop->stopName, $step->stop->stopName) == 0) {
                        $avancee = 'current';
                    }
                    ?>
                    <a href="/ajax/page/<?= $step->stop->stopCode ?>/<?= rawurlencode(str_replace('/', '_', $step->stop->stopName ?? '')) ?>" class="item-link item-content <?= $avancee ?>">
                        <div class="item-media">
                            <i class="t icon l<?= str_replace('+', '', $departureData['ligne']) ?>"></i>
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
      </div></div></div>
      </div>
      <?php if (is_array($thermometer->deviation)) { ?>
          <div class="pdata">
             <div class="accordion-list">
                <?php
                $nombreDePerturbations = 0;

                 foreach ($thermometer->deviation as $disruption) {
                     $nombreDePerturbations++;
                     ?>
                     <div class="accordion-item <?= ($nombreDePerturbations === 1) ? 'accordion-item-expanded' : '' ?>">
                         <div class="accordion-item-toggle">
                            <div class="toggle-icon"></div>
                             ⚠︎ <?= $disruption->nature ?>
                         </div>
                         <div class="accordion-item-content">
                             <?= $disruption->consequence ?>
                         </div>
                     </div>
                 <?php } ?>
             </div>
        <?php } ?>
      </div>
</div>
<?php }
