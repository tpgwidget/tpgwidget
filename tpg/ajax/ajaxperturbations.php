<?php
require __DIR__.'/../../config.inc.php';
use TPGwidget\Data\Lines;
$disruptions = @simplexml_load_file('http://prod.ivtr-od.tpg.ch/v1/GetDisruptions.xml?key='.getenv('TPG_API_KEY'));

if (!$disruptions) {
	$serveurTPGIndisponible = true;
}

$allLines = array_keys(Lines::all());

$perturbations = 0;
foreach ($disruptions->disruptions->disruption as $disruption) {
    if (in_array($disruption->lineCode, $allLines)) { ?>
    <div class="card">
      <div class="card-content">
        <div class="card-content-inner">
              <div class="perturbation-header">
                  <?php
                      $perturbations++;
                      echo '<span class="picto-ligne ';
                      echo 'l'.$disruption->lineCode.' ';
                      echo 's'.$disruption->lineCode.' ';

                      if (Lines::get($disruption->lineCode)['text'] === '#FFFFFF') {
                          echo 'w';
                      }

                      echo '">'.$disruption->lineCode;
                      echo '</span>';
                  ?>
                  <header><?= $disruption->nature ?></header>
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
