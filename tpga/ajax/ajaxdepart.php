<?php
include '../../tpgdata/vehicules/vehicules.php';
include '../../tpgdata/lignes.php';

if(!isset($_GET['id'])){ // Si aucun arrêt spécifié
    die("Erreur : Aucun d&eacute;part sp&eacute;cifi&eacute;");
}

require '../../tpgdata/apikey.php';
$file = 'http://prod.ivtr-od.tpg.ch/v1/GetThermometer.xml?key='.$key.'&departureCode=' . $_GET["id"];
$thermometer = @simplexml_load_file($file);

if(!$thermometer){
    $erreur[] = '<div class="boxinstall"><strong>Erreur :</strong> Serveur TPG indisponible</div>';
}

include '../../tpgdata/stops.php';

if(in_array($thermometer->lineCode, $lignesAvecTexteNoir)) {
    $b = 'b';
}

$color = lineColor($thermometer->lineCode);

?>
<div data-page="depart-<?= lineColor($thermometer->lineCode) ?>" class="page page-depart <?=$b?>">
    <div class="navbar" style="background-color:#<?=$color?>">
      <div class="navbar-inner">
        <div class="left">
          <a href="#" class="back link icon-only">
            <i class="icon icon-back"></i>
           </a>
        </div>
        <div class="center"><span class="lineCode <?=$b?>"><?=$thermometer->lineCode?></span> ➜ <?=stopFilter($thermometer->destinationName)?></div>
      </div>
    </div>
    <div class="toolbar tabbar" style="background-color:#<?=$color?>">
        <div class="toolbar-inner">
            <a href="#tab-1" class="tab-link show-m active">Temps</a>
            <a href="#tab-2" class="tab-link show-h">Heure</a>
        </div>
    </div>
    <?
      if(in_array($thermometer->lineCode, $lignesAvecTexteNoir)) {
          $b = "-b";
      }
    ?>
    <div class="page-content">
        <div class="tabs">
            <div id="tab-1" class="tab active"></div>
            <div id="tab-2" class="tab active"></div>
        </div>

        <div class="content-block" style="margin: 10px;">
          </div>

          <?
            if(isset($thermometer->vehiculeNo)){
                $vehicule = new Vehicule($thermometer->vehiculeNo); // Afficher véhicule
                $vehicule->renderCard_Android();
            }
          ?>

        <div class="card"><div class="card-content">
        <div class="list-block parcours">
        <ul>
            <? $avancee = 'previous';
              foreach ($thermometer->steps->step as $step) { ?>
              <li>
              <?
                if($avancee == "current") {
                    $avancee = "";
                }

                if(levenshtein($thermometer->stop->stopName, $step->stop->stopName) == 0) {
                    $avancee = 'current';
                }
              ?>
                  <a href="/ajax/page/<?=$step->stop->stopCode?>/<?=rawurlencode($step->stop->stopName)?>" class="item-link item-content <?=$avancee?>">
                    <div class="item-media">
                        <i class="t icon l<?=str_replace('+', '', $thermometer->lineCode) ?>"></i>
                  </div>
                  <div class="item-inner">
                    <div class="item-title"><?=stopFilter($step->stop->stopName)?></div>
                    <div class="item-after">
                        <span class="h"><?=date("H:i", strtotime($step->timestamp))?></span>
                        <span class="m">
                            <? if(intval($step->arrivalTime)) {
                                echo $step->arrivalTime." min";
                            } ?>
                        </span>
                      </div>
                  </div>
                </a>
              </li>
            <? } ?>
        </ul>
      </div></div></div>
      </div>
      <? if($thermometer->disruptions) { ?>
          <div class="pdata">
             <div class="accordion-list">
                <?
                 $nombreDePerturbations = 0;

                 foreach($thermometer->disruptions->disruption as $disruption) {
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
                 <? } ?>
             </div>
        <? } ?>
      </div>
</div>
