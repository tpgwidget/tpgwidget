<?php
require_once __DIR__.'/../../config.inc.php';
use TPGwidget\Data\{Lines, Stops};

$depart = $_POST['depart'];
$arrivee = $_POST['arrivee'];
$datehour = $_POST['dateheure'];

$url =  'https://transport.opendata.ch/v1/connections'.
        '?from='.urlencode(Stops::tpgToSbb($depart)).
        "&to=".urlencode(Stops::tpgToSbb($arrivee));

if ($_POST['dateheure']) {
    $url .= '&date='.date('Y-m-d', strtotime($_POST['dateheure']));
    $url .= '&time='.date('H:i', strtotime($_POST['dateheure']));
}

$url .= '&isArrivalTime='.$_POST['isArrivalTime'];

$file = file_get_contents($url);
$json = json_decode($file);

function indiceDeLigne($section) {
    if ($section->journey->operator === 'TPG') {
        $indiceDeLigne = $section->journey->number;

        if (Lines::get($indiceDeLigne)['text'] === '#FFFFFF') {
            $w = 'w';
        } else {
            $w = '';
        }

        echo '<span class="picto-ligne l'.$indiceDeLigne.' '.$w.'">';
        echo $indiceDeLigne;
        echo '</span>';
    } elseif ($section->journey->operator == 'SBB') {
        $name = explode(' ', $section->journey->name)[0];
        if ($name === 'RE') {
            echo '<span class="picto-ligne lRE">RE</span>';
        } else {
            echo '<span class="picto-ligne l9 w">';
            echo $name;
            echo '</span>';
        }
    } else {
        echo '<span class="picto-ligne">';
        echo $section->journey->name;
        echo '</span>';
    }
}

/**
 * @return string e.g. ff6600
 */
function getLineColor($section) {
    if ($section->journey && $section->journey->operator == 'TPG') {
        $indiceDeLigne = $section->journey->number;
        return Lines::get($indiceDeLigne)['background'];
    } elseif ($section->journey && $section->journey->operator == 'SBB') {
        return '#cc0033';
    }

    return '#222222';
}
?>
<div data-page="trajets" class="page page-trajets">
    <div class="navbar">
        <div class="navbar-inner">
            <div class="left"><a href="#" class="back link icon-only"><i class="icon icon-back"></i></a></div>
            <div class="center">Trajets</div>
        </div>
    </div>
    <div class="page-content">
        <?php if (count($json->connections) > 0) { ?>
            <div class="swiper-container">
                <div class="swiper-wrapper">
                    <?php
                    if ($_POST['isArrivalTime'] == '1') {
                        $json->connections = array_reverse($json->connections);
                    }

                    foreach ($json->connections as $trajet) {
                    ?>
                        <div class="swiper-slide">

                            <!-- Header -->
                            <header>
                                <?php
                                /* GENERATION CARTE TRAJET */

                                // Génerer un lien Google Maps
                                $mapURL = 'https://maps.googleapis.com/maps/api/staticmap';
                                $mapURL .= '?size=SCREENWIDTHx200';
                                $mapURL .= '&scale=2';
                                $mapURL .= '&key=AIzaSyBsOJdiR3CuJrhqIQF7yI-lR-ToqTwJLO8';

                                // Marqueurs
                                $coordinate = $trajet->from->station->coordinate;
                                $mapURL .= '&markers=color:blue|label:A|'.$coordinate->x.','.$coordinate->y;
                                $coordinate = $trajet->to->station->coordinate;
                                $mapURL .= '&markers=color:blue|label:B|'.$coordinate->x.','.$coordinate->y;

                                // Lignes
                                foreach ($trajet->sections as $section) {
                                    $couleur = ltrim(getLineColor($section), '#');

                                    $mapURL .= '&path=weight:7|color:0x'.$couleur.'cc';

                                    if ($section->journey) {
                                        foreach ($section->journey->passList as $arret) {
                                            $mapURL .= '|'.$arret->station->coordinate->x.','.$arret->station->coordinate->y;
                                        }
                                    }
                                }

                                ?>

                                <img class="trajet-map" src="<?= $mapURL ?>" alt="Carte de votre trajet">

                                <?php
                                $dateTrajet = date('d.m.Y', $trajet->from->departureTimestamp);
                                $dateToday = date('d.m.Y');

                                if ($dateTrajet !== $dateToday) {
                                    echo '<p class="date">Itinéraire valable le '.$dateTrajet.'</p>';
                                }
                                ?>

                                <h1>
                                    <?php
                                        echo date('H:i', $trajet->from->departureTimestamp);
                                        echo ' – ';
                                        echo date('H:i', $trajet->to->arrivalTimestamp);

                                        echo ' (';
                                            $duree = $trajet->to->arrivalTimestamp - $trajet->from->departureTimestamp;
                                            echo floor($duree / 60);
                                        echo ' minutes)';
                                    ?>
                                </h1>

                                <!-- Résumé de trajet -->
                                <div class="resume"><?php
                                    foreach ($trajet->sections as $section) {
                                        if ($section->journey) {
                                            indiceDeLigne($section);
                                        }
                                    }
                                ?></div>
                            </header>

                            <!-- Trajet -->
                            <?php foreach ($trajet->sections as $section) { ?>
                                <div class="card">
                                    <?php if ($section->journey) { ?>
                                    <div class="card-header">
                                        <?php
                                        indiceDeLigne($section);

                                        // Mesurer le temps
                                        $timestampDepart = $section->departure->departureTimestamp;
                                        $timestampArrivee = $section->arrival->arrivalTimestamp;

                                        $intervalleArrets = round(($timestampArrivee - $timestampDepart) / 60);

                                        if ($intervalleArrets >= 2) {
                                            $intervalleArrets .= ' minutes';
                                        } else {
                                            $intervalleArrets .= ' minute';
                                        }

                                        // Retirer le premier et le dernier arrêt de la passList
                                        unset($section->journey->passList[0]);
                                        array_pop($section->journey->passList);
                                        ?>
                                        <span class="destination">
                                            <span style="color: <?= getLineColor($section) ?>">→</span> <?= Stops::sbbToTpg($section->journey->to) ?>
                                        </span>
                                        <?php
                                        switch ($section->journey->category) {
                                            case 'B':
                                                echo '<i>🚌</i>';
                                                break;
                                            case 'T':
                                                echo '<i>🚋</i>';
                                                break;
                                            case 'S': case 'R': case 'IR': case 'RE':
                                                echo '<i>🚆</i>';
                                                break;
                                        }
                                        ?>
                                    </div>
                                    <div class="card-content">
                                        <div class="list-block">
                                            <ul>
                                                <li>
                                                    <div class="item-content">
                                                        <div class="item-media">
                                                            <i class="icon t" style="background-color: <?= getLineColor($section) ?>"></i>
                                                        </div>
                                                        <div class="item-inner">
                                                            <div class="item-title"><?= Stops::sbbToTpg($section->departure->station->name) ?></div>
                                                            <div class="item-after"><?= date('H:i', $section->departure->departureTimestamp) ?></div>
                                                        </div>
                                                    </div>
                                                </li>

                                                <li class="accordion-item">
                                                    <?php $stopCount = count($section->journey->passList); ?>
                                                    <?php if ($stopCount > 0) { ?>
                                                        <a href="#" class="item-content item-link">
                                                            <span class="item-media">
                                                                <i class="icon t icon-resume" style="background-color: <?= getLineColor($section) ?>"></i>
                                                            </span>
                                                    <?php } else { ?>
                                                        <div class="item-content">
                                                            <span class="item-media">
                                                                <i class="icon t icon-resume no-stops" style="background-color: <?= getLineColor($section) ?>"></i>
                                                            </span>
                                                    <?php } ?>
                                                        <span class="item-inner">
                                                            <span class="item-title"><em>
                                                                <?php
                                                                    if ($stopCount == 0) {
                                                                        echo 'Aucun arrêt';
                                                                    } elseif ($stopCount == 1) {
                                                                        echo '1 arrêt';
                                                                    } else {
                                                                        echo $stopCount.' arrêts';
                                                                    }
                                                                ?>
                                                            </em></span>
                                                            <span class="item-after"><?= $intervalleArrets ?></span>
                                                        </span>
                                                    <?php if ($stopCount > 0) { ?>
                                                        </a>
                                                    <?php } else { ?>
                                                        </div>
                                                    <?php } ?>
                                                    <div class="accordion-item-content">
                                                        <div class="list-block">
                                                            <ul>
                                                                <?php foreach ($section->journey->passList as $stop) { ?>
                                                                <li>
                                                                    <div class="item-content">
                                                                        <div class="item-media">
                                                                            <i class="icon t" style="background-color: <?= getLineColor($section) ?>"></i>
                                                                        </div>
                                                                        <div class="item-inner">
                                                                            <div class="item-title"><?= Stops::sbbToTpg($stop->station->name) ?></div>
                                                                            <div class="item-after"><?= date('H:i', $stop->departureTimestamp) ?></div>
                                                                        </div>
                                                                    </div>
                                                                </li>
                                                                <?php } ?>
                                                            </ul>
                                                        </div>
                                                    </div>
                                                </li>

                                                <li>
                                                    <div class="item-content">
                                                        <div class="item-media">
                                                            <i class="icon t" style="background-color: <?= getLineColor($section) ?>"></i>
                                                        </div>
                                                        <div class="item-inner">
                                                            <div class="item-title"><?= Stops::sbbToTpg($section->arrival->station->name) ?></div>
                                                            <div class="item-after"><?= date('H:i', $section->arrival->arrivalTimestamp) ?></div>
                                                        </div>
                                                    </div>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                    <?php } elseif ($section->walk) { // Marche ?>
                                        <!-- Marche -->
                                        <div class="card-header">
                                            <i class="icon icon-marche"></i>
                                            <strong class="destination">
                                                <?php
                                                    echo 'Marcher ';

                                                    $temps = floor(($section->arrival->arrivalTimestamp - $section->departure->departureTimestamp) / 60);
                                                    echo $temps;

                                                    if ($temps > 1) {
                                                        echo ' minutes';
                                                    } else {
                                                        echo ' minute';
                                                    }
                                                ?>
                                            </strong>
                                        </div>
                                        <div class="card-content">
                                            <div class="list-block">
                                                <ul>
                                                    <li>
                                                        <div class="item-content">
                                                            <div class="item-media">
                                                                <i class="icon t l35"></i>
                                                            </div>
                                                            <div class="item-inner">
                                                                <div class="item-title"><?= Stops::sbbToTpg($section->departure->station->name) ?></div>
                                                                <div class="item-after"><?= date('H:i', $section->departure->departureTimestamp) ?></div>
                                                            </div>
                                                        </div>
                                                    </li>
                                                    <li>
                                                        <div class="item-content">
                                                            <div class="item-media">
                                                                <i class="icon t l35"></i>
                                                            </div>
                                                            <div class="item-inner">
                                                                <div class="item-title"><?= Stops::sbbToTpg($section->arrival->station->name) ?></div>
                                                                <div class="item-after"><?= date('H:i', $section->arrival->arrivalTimestamp) ?></div>
                                                            </div>
                                                        </div>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    <?php } ?>
                                </div>
                            <?php } ?>
                        </div>
                    <?php } ?>
                </div>
            </div>
        <?php } else { // Aucun trajet trouvé ?>
            <div class="content-block">
                <div class="itineraire-erreur">
                    <?php if (trim($arrivee) == '') { // Pas de lieu d'arrivée ?>
                        <img src="/resources/img/itineraire_where.png" alt="Où souhaitez-vous aller ?">
                        <h1>Où souhaitez-vous aller ?</h1>
                        <p>Vous n'avez sélectionné aucun lieu d'arrivée.</p>
                        <a href="#" class="back button button-big button-fill">Retour</a>
                    <?php } else { ?>
                        <img src="/resources/img/itineraire_erreur.png" alt="Aucun itinéraire trouvé">
                        <h1>Aucun itinéraire trouvé</h1>
                        <p>Aucun itinéraire n'a été trouvé. Essayez de modifier les termes de votre recherche.</p>
                        <a href="#" class="back button button-big button-fill">Réessayer</a>
                    <?php } ?>
                </div>
            </div>
        <?php } ?>
    </div>
    <?php if (count($json->connections) > 0) { ?>
        <div class="swiper-container-horizontal toolbar toolbar-bottom">
            <div class="swiper-pagination swiper-pagination-bullets"></div>
        </div>
    <?php } ?>
</div>
