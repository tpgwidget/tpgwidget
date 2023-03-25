<?php
require __DIR__.'/../../config.inc.php';
use TPGwidget\Data\Lines;

//$file = 'http://prod.ivtr-od.tpg.ch/v1/GetDisruptions.xml?key='.getenv('TPG_API_KEY');
//$disruptions = @simplexml_load_file($file);

// Fall Back To Old API - This marks the final moments of TPGw and Third Party TPG Open Data Apps
$disruptions = json_decode(file_get_contents("http://prod.ivtr.tpg.ch/GetPerturbations.json"))->perturbations;

$allLines = array_keys(Lines::all());

$perturbations = 0;
foreach ($disruptions->perturbation as $disruption) {
    if (in_array($disruption->ligne, $allLines)) { ?>
    <div class="card">
      <div class="card-content">
        <div class="card-content-inner">
              <div class="perturbation-header">
                  <?php
                      $perturbations++;
                      echo '<span class="picto-ligne ';
                      echo 'l'.$disruption->ligne.' ';
                      echo 's'.$disruption->ligne.' ';

                      if (Lines::get($disruption->ligne)['text'] === '#FFFFFF') {
                          echo 'w';
                      }

                      echo '">'.$disruption->ligne;
                      echo '</span>';
                  ?>
                  <header><?=$disruption->nature?></header>
                  <?php
                      if ($disruption->place != "") {
                          echo '<small>'.$disruption->place.'</small>';
                      } else {
                          echo "&nbsp;";
                      }
                  ?>
              </div>
              <p><?= $disruption->consequence ?></p>
        </div>
      </div>
    </div>
<?php }
} if (!$perturbations) { ?>
    <div class="card">
      <div class="card-content">
        <div class="card-content-inner">
            <div class="graym">
                <span class="smileyBien"></span>
                <h2>Aucune perturbation sur le réseau !</h2>
            </div>
        </div>
      </div>
    </div>
<?php } ?>
