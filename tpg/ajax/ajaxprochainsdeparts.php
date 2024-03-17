<?php
require_once __DIR__.'/../../config.inc.php';
use TPGwidget\Data\{Lines, Stops};

//$file = 'http://prod.ivtr-od.tpg.ch/v1/GetNextDepartures.xml?key='.getenv('TPG_API_KEY').'&stopCode=' . $_GET["id"];
//$nextDepartures = @simplexml_load_file($file);

// Fall Back To Old API - This marks the final moments of TPGw and Third Party TPG Open Data Apps
$nextDepartures = json_decode(file_get_contents("https://preview.genav.ch/api/getNextDepartures.json?stopCode=". $_GET["id"]))->nextDepartures;

include '../../tpgdata/quais.php';
include '../../tpgdata/vehicules/vehicules.php';
?>

<?php if ($nextDepartures) { ?>
<div class="list-block media-list departures">
    <ul>
        <?php if (file_exists(__DIR__.'/../../tpgdata/plans/connection/'.$nextDepartures->stop->stopCode.'.pdf')) { // Would be good to actually have the maps :P ?>
            <li class="w l35">
                <a class="item-link item-content external" target="_blank" href="https://tpgdata.nicolapps.ch/plans/connection/<?=$nextDepartures->stop->stopCode?>.pdf">
                    <div class="item-inner">
                        <div class="item-title-row">
                            <div class="item-title">Plan de connexions</div>
                        </div>
                    </div>
                </a>
            </li>
        <?php }

        foreach ($nextDepartures->departures as $depart) { ?>
            <?php
//            echo $depart->connectionWaitingTime; // NotSupported?
            if (Lines::get($depart->connection->lineCode)['text'] === '#FFFFFF') {
                print '<li class="w l'.str_replace('+', 'plus', $depart->connection->lineCode).'">';
            } else{
                print '<li class="l'.$depart->connection->lineCode.'">';
            }

            if ($depart->waitingTime != 'no more') {
                echo '<a href="/ajax/depart/'.$depart->departureCode.'/?vehicleNo='.urlencode($depart->vehiculeNo??'').'" class="item-link item-content">';
            } else {
                echo '<div class="item-content">';
            }
            ?>
            <div class="item-inner">
                <div class="item-title-row">
                    <div class="item-title">
                        <?= $depart->ligne ?> → <?= Stops::format($depart->destination) ?>
                    </div>
                    <div class="item-after">
                        <?php if ($depart->waitingTime != 'no more') {
                            echo date('H:i', strtotime($depart->timestamp));
                        } ?>
                    </div>
                </div>

                <?php
                quai($nextDepartures->stop->stopCode, $depart->connection->lineCode, $depart->connection->destinationName);
                if (($depart->characteristics??'') != "PMR" && $depart->waitingTime != "no more") {
                    echo '<span class="nopmr"></span>';
                }

                // Disruption
                if (isset($depart->disruption)) {
                    echo '<span class="perturbation"></span>';
                }

                // Waiting time
                echo '<div class="temps">';

                if (($depart->reliability??'') === 'T') {
                    echo '~';
                }

                switch ($depart->waitingTime) {
                    case '00':
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
        <?php // The number of "no more" checks you do... :P
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
