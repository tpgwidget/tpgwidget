<?php
require_once __DIR__.'/../../config.inc.php';
use TPGwidget\Data\{Lines, Stops};

$file = 'https://prod.ivtr-od.tpg.ch/v1/GetNextDepartures.xml?key='.getenv('TPG_API_KEY').'&stopCode=' . $_GET["id"];
$nextDepartures = @simplexml_load_file($file);

include '../../tpgdata/quais.php';
include '../../tpgdata/vehicules/vehicules.php';
?>
<div style="background-color: #006633; color: #fff; padding: 16px 10px; padding-left: 80px; position: relative; overflow: hidden">
    <h4 style="margin: 0">Retour à la normale (18.09.2020)</h4>
    <p style="margin: 8px 0">
        Le problème de ces derniers jours concernant les horaires semble être corrigé.
    </p>
    <p style="margin: 0">
        Merci pour votre utilisation de TPGwidget et bon voyage.
    </p>

    <span style="position: absolute; font-size: 72px; top: 0; left: 10px; color: rgba(0, 0, 0, .3)">✓︎</span>
</div>
<?php if ($nextDepartures) { ?>
<div class="list-block media-list departures">
    <ul>
        <?php if (file_exists(__DIR__.'/../../tpgdata/plans/connection/'.$nextDepartures->stop->stopCode.'.pdf')) { ?>
            <li class="w l32">
                <a class="item-link item-content external" target="_blank" href="https://tpgdata.nicolapps.ch/plans/connection/<?=$nextDepartures->stop->stopCode?>.pdf">
                    <div class="item-inner">
                        <div class="item-title-row">
                            <div class="item-title">Plan de connexions</div>
                        </div>
                    </div>
                </a>
            </li>
        <?php }

        foreach ($nextDepartures->departures->departure as $depart) { ?>
            <?php
            echo $depart->connectionWaitingTime;
            if (Lines::get($depart->connection->lineCode)['text'] === '#FFFFFF') {
                print '<li class="w l'.str_replace('+', 'plus', $depart->connection->lineCode).'">';
            } else{
                print '<li class="l'.$depart->connection->lineCode.'">';
            }

            if ($depart->waitingTime != 'no more') {
                echo '<a href="/ajax/depart/'.$depart->departureCode.'/?vehicleNo='.urlencode($depart->vehiculeNo).'" class="item-link item-content">';
            } else {
                echo '<div class="item-content">';
            }
            ?>
            <div class="item-inner">
                <div class="item-title-row">
                    <div class="item-title"><?= $depart->connection->lineCode ?> → <?= Stops::format($depart->connection->destinationName) ?></div>
                    <div class="item-after">
                        <?php if ($depart->waitingTime != 'no more') {
                            echo date('H:i', strtotime($depart->timestamp));
                        } ?>
                    </div>
                </div>

                <?php
                quai($nextDepartures->stop->stopCode, $depart->connection->lineCode, $depart->connection->destinationName);
                if ($depart->characteristics != "PMR" && $depart->waitingTime != "no more") {
                    echo '<span class="nopmr"></span>';
                }

                // Disruption
                if (isset($depart->disruptions->disruption)) {
                    echo '<span class="perturbation"></span>';
                }

                // Wi-Fi
                $vehicule = new Vehicule($depart->vehiculeNo ?? '');
                if ($vehicule->wifi) {
                    echo '<img class="departure-wifi" src="/resources/img/wifi.svg" alt="Wi-Fi gratuit">';
                }

                // Waiting time
                echo '<div class="temps">';
                switch ($depart->waitingTime) {
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
                        print $depart->waitingTime.' minutes';
                        break;
                }
                echo '</div>';
                ?>
            </div>
        <?php
        if ($depart->waitingTime != 'no more') {
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
