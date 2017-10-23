<?php

require '../../tpgdata/apikey.php';
$file = 'http://prod.ivtr-od.tpg.ch/v1/GetNextDepartures.xml?key='.$key.'&stopCode=' . $_GET["id"];
$nextDepartures = simplexml_load_file($file);

include '../../tpgdata/quais.php';
include '../../tpgdata/stops.php';

if(!$nextDepartures){
    $serveurTPGIndisponible = true;
}

?>
<div class="list-block media-list" id="departures">
    <ul>
        <?php
        $arretsPlan = ["AERO", "AIRE", "ALPE", "ACAC", "SEFE", "AMDO", "ARME", "AUGS", "ATHE", "AAIN", "BHET", "BXPL", "BLDO", "FRRE", "BOHT", "BMON", "CRGE", "CHRM", "CBAC", "CHNE", "CIRQ", "CLIG", "CTNT", "COUT", "CLAP", "CCHA", "CRCO", "CCOL", "CROI", "DELC", "CBRI", "EMED", "LOCH", "FOSA", "EPIN", "INDT", "FDUS", "PLGE", "GZIM", "GEBE", "HTOU", "ISAM", "HOPI", "JARB", "JOCT", "JALP", "ESRT", "GRVI", "LAUB", "MACH", "MRCH", "SLSI", "MYRN", "MLRD", "MTBL", "MUSE", "MOIL", "ONEX", "NATI", "PLEY", "PALE", "PVEY", "FAVR", "PLEV", "PEDO", "ETOI", "PEIL", "PRSM", "PNEV", "PBAL", "PONT", "PRBE", "PTLA", "PUMA", "PLPA", "QARV", "RIEU", "SERV", "SENV", "STGE", "STND", "SALV", "SEYM", "VRNT", "VTRN", "SVEY", "TRSI", "TCAR", "TRTI", "UNIM", "VXGA", "VIDO", "VLON", "RIVE", "GOUL", "RENF", "AREN", "BAIR", "CVIN", "GSAC", "MIDE", "PXPO", "TLIG"];

        if(in_array($nextDepartures->stop->stopCode, $arretsPlan)) { ?>
            <li class="w l32">
                <a class="item-link item-content external" target="_blank" href="http://cdn.nicolapps.ch/plansconnexion/<?=$nextDepartures->stop->stopCode?>.pdf">
                    <div class="item-inner">
                        <div class="item-title-row">
                            <div class="item-title">Plan de connexions</div>
                        </div>
                    </div>
                </a>
            </li>
            <? }

            foreach ($nextDepartures->departures->departure as $depart) { ?>
                <?
                $lignesAvecTexteBlanc = array("U", "NV", "1", "3", "4", "5", "6", "7", "8", "9", "10", "11", "14", "15", "18", "21", "22", "23", "25", "31", "32", "33", "35", "36", "41", "44", "46", "47", "51", "52", "54", "56", "A", "C", "E", "J", "L", "NA", "NC", "ND", "NE", "NO", "NP", "NS", "NT", "P", "S", "TO", "TT", "V", "W", "X", "G+", "5+", 'V+', 'C+');

                echo $depart->connectionWaitingTime;
                if(in_array($depart->connection->lineCode, $lignesAvecTexteBlanc)){
                    print '<li class="w l'.str_replace('+', 'plus', $depart->connection->lineCode).'">';
                } else{
                    print '<li class="l'.$depart->connection->lineCode.'">';
                }



                if($depart->waitingTime != "no more"){
                    echo '<a href="/ajax/depart/'.$depart->departureCode.'/" class="item-link item-content">';
                } else {
                    echo '<div class="item-content">';
                }
                ?>
                <div class="item-inner">
                    <div class="item-title-row">
                        <div class="item-title"><? print $depart->connection->lineCode ?> ➜ <? print stopFilter($depart->connection->destinationName); ?></div>
                        <div class="item-after">
                            <? if($depart->waitingTime != "no more") {
                                echo date("H:i", strtotime($depart->timestamp));
                            } ?>
                        </div>
                    </div>

                    <?

                    quai($nextDepartures->stop->stopCode, $depart->connection->lineCode, $depart->connection->destinationName);
                    if($depart->characteristics != "PMR" && $depart->waitingTime != "no more"){
                        echo '<span class="nopmr"></span>';
                    }

                    if(isset($depart->disruptions->disruption)) {
                        print '<span class="perturbation"></span>';
                    }

                    switch ($depart->waitingTime) {
                        case '0':
                        print '<div class="temps">';
                        print "À l'arrêt";
                        print '</div>';
                        break;
                        case '1':
                        print '<div class="temps minute">';
                        print 1;
                        print '</div>';
                        break;
                        case 'no more':
                        print '<div class="temps">';
                        print "-";
                        print '</div>';
                        break;
                        case '&gt;1h':
                        print '<div class="temps">';
                        print "+ d'une heure";
                        print '</div>';
                        break;
                        default:
                        print '<div class="temps minutes">';
                        print $depart->waitingTime;
                        print '</div>';
                        break;
                    }

                    ?>
                </div>
            </a>
        </li>
        <? } ?>

    </ul>
</div>
