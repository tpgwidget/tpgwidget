<?php
require_once __DIR__.'/../../config.inc.php';
use TPGwidget\Data\{Lines, Stops};

//$file = 'http://prod.ivtr-od.tpg.ch/v1/GetNextDepartures.xml?key='.getenv('TPG_API_KEY').'&stopCode=' . $_GET["id"];
//$nextDepartures = @simplexml_load_file($file);

// Fall Back To Old API - This marks the final moments of TPGw and Third Party TPG Open Data Apps
$nextDepartures = json_decode(file_get_contents("http://prod.ivtr.tpg.ch/GetProchainsDepartsTriHeure.json?codeArret=". $_GET["id"]))->prochainsDeparts;

include '../../tpgdata/quais.php';
include '../../tpgdata/vehicules/vehicules.php';
?>

<?php if ($nextDepartures) { ?>
<div class="list-block media-list list-departures">
    <ul>
        <?php if (file_exists(__DIR__.'/../../tpgdata/plans/connection/'.$nextDepartures->codeArret.'.pdf')) { ?>
            <li class="w l35">
                <a class="item-link item-content external" target="_blank" href="https://tpgdata.nicolapps.ch/plans/connection/<?=$nextDepartures->codeArret?>.pdf">
                    <div class="item-inner">
                        <div class="item-title-row">
                            <div class="item-title">Plan de connexions</div>
                        </div>
                    </div>
                </a>
            </li>
        <?php } ?>

        <?php foreach ($nextDepartures->prochainDepart as $depart) { ?>
            <?php
//            echo $depart->connectionWaitingTime; // NotSupported?
            if (Lines::get($depart->ligne)['text'] === '#FFFFFF') {
                print '<li class="w l'.str_replace('+', 'plus', $depart->ligne).'">';
            } else {
                print '<li class="l'.$depart->ligne.'">';
            }

            if ($depart->attente != "no more") {
                echo '<a href="/ajax/depart/'.$depart->horaireRef.'/?vehicleNo='.urlencode($depart->vehiculeNo??'').'" class="item-link item-content">';
            } else {
                echo '<div class="item-content">';
            }
            ?>
                <div class="item-inner">
                    <div class="item-title-row">
                        <div class="item-title"><?= $depart->ligne ?> ➜ <?= Stops::format($depart->destination) ?></div>
                        <div class="item-after">
                            <?php if ($depart->attente != "no more") {
                                echo date("H:i", strtotime($depart->heureArrivee));
                            } ?>
                        </div>
                    </div>

                    <?php
                    // Quai
                    quai($nextDepartures->codeArret, $depart->ligne, $depart->destination);

                    // PMR
                    if (($depart->caracteristique??'') != 'PMR' && $depart->attente != "no more") {
                        echo '<span class="nopmr"></span>';
                    }

                    // Disruption indicator
                    if (isset($depart->perturbation)) {
                        print '<span class="perturbation"></span>';
                    }

                    // Waiting time
                    echo '<div class="temps">';

                    if (($depart->fiabilite??'') === 'T') {
                        echo '~';
                    }

                    switch ($depart->attente) {
                        case '0':
                            print 'À l’arrêt';
                            break;
                        case '1':
                            echo '1 minute';
                            break;
                        case 'no more':
                            echo '-';
                        break;
                        case '&gt;1h':
                            echo '+ d’une heure';
                            break;
                        default:
                            print $depart->attente.' minutes';
                            break;
                    }
                    echo '</div>';
                    ?>
                </div>
            <?php
            if ($depart->attente != 'no more') {
                echo '</a>';
            } else {
                echo '</div>';
            }
            ?>
        </li>
        <?php } ?>
    </ul>
</div>
<?php } else { ?>
<p><strong>Erreur</strong> : Impossible de contacter les serveurs des TPG.</p>
<?php } ?>
