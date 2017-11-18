<?php
require __DIR__.'/../../tpgdata/apikey.php';
$file = 'http://prod.ivtr-od.tpg.ch/v1/GetNextDepartures.xml?key='.$key.'&stopCode=' . $_GET["id"];
$nextDepartures = @simplexml_load_file($file);

include '../../tpgdata/quais.php';
include '../../tpgdata/stops.php';
include '../../tpgdata/vehicules/vehicules.php';
?>

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
            <?
            $lignesAvecTexteBlanc = ['U', 'NV', '1', '3', '4', '5', '6', '7', '8', '9', '10', '11', '14', '15', '18', '21', '22', '23', '25', '31', '32', '33', '35', '36', '41', '44', '46', '47', '51', '52', '54', '56', 'A', 'C', 'E', 'J', 'L', 'NA', 'NC', 'ND', 'NE', 'NO', 'NP', 'NS', 'NT', 'P', 'S', 'TO', 'TT', 'V', 'W', 'X', 'G+', '5+', 'V+', 'C+'];

            echo $depart->connectionWaitingTime;
            if (in_array($depart->connection->lineCode, $lignesAvecTexteBlanc)){
                print '<li class="w l'.str_replace('+', 'plus', $depart->connection->lineCode).'">';
            } else{
                print '<li class="l'.$depart->connection->lineCode.'">';
            }

            if ($depart->waitingTime != 'no more') {
                echo '<a href="/ajax/depart/'.$depart->departureCode.'/" class="item-link item-content">';
            } else {
                echo '<div class="item-content">';
            }
            ?>
            <div class="item-inner">
                <div class="item-title-row">
                    <div class="item-title"><?php print $depart->connection->lineCode ?> ➜ <?php print stopFilter($depart->connection->destinationName); ?></div>
                    <div class="item-after">
                        <?php if ($depart->waitingTime != 'no more') {
                            echo date('H:i', strtotime($depart->timestamp));
                        } ?>
                    </div>
                </div>

                <?php
                quai($nextDepartures->stop->stopCode, $depart->connection->lineCode, $depart->connection->destinationName);
                if ($depart->characteristics != "PMR" && $depart->waitingTime != "no more"){
                    echo '<span class="nopmr"></span>';
                }

                // Disruption
                if (isset($depart->disruptions->disruption)) {
                    echo '<span class="perturbation"></span>';
                }

                // Wi-Fi
                $vehicule = new Vehicule($depart->vehiculeNo);
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
