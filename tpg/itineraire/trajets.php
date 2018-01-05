<?
include 'tpgcff.php';
include '../../tpgdata/lignes.php';

$depart = $_POST['depart'];
$arrivee = $_POST['arrivee'];
$datehour = $_POST['dateheure'];

$url =  'https://transport.opendata.ch/v1/connections'.
        '?from='.urlencode(showCffName($depart)).
        "&to=".urlencode(showCffName($arrivee));

if($_POST['dateheure']){
    $url .= '&date='.date('Y-m-d', strtotime($_POST['dateheure']));
    $url .= '&time='.date('H:i', strtotime($_POST['dateheure']));
}

$url .= '&isArrivalTime='.$_POST['isArrivalTime'];

$file = file_get_contents($url);
$json = json_decode($file);

?>

<div class="navbar">
  <div class="navbar-inner">
    <div class="left"><a href="#" class="back link"> <i class="icon icon-back"></i><span>Itinéraire</span></a></div>
    <div class="center sliding">Trajets</div>
    <div class="right">
      <a href="#" class="open-panel link icon-only"><i class="icon icon-panel"></i></a>
    </div>
  </div>
</div>
<div class="pages">
  <div data-page="trajets" class="page page-trajets">
    <div class="page-content">
        <? if(count($json->connections) > 0) { ?>
            <div class="swiper-container">
                <div class="swiper-wrapper">
                    <?
                    if($_POST['isArrivalTime'] == '1') {
                        $json->connections = array_reverse($json->connections);
                    }

                    foreach ($json->connections as $trajet) {
                    ?>
                        <div class="swiper-slide">

                            <!-- Header -->
                            <header>
                                <?
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
                                foreach($trajet->sections as $section) {
                                    if($section->journey && $section->journey->operator == 'TPG') {
                                        $indiceDeLigne = $section->journey->number;
                                        $couleur = lineColor($indiceDeLigne);
                                    } elseif($section->journey && $section->journey->operator == 'SBB') {
                                        $couleur = 'cc0033';
                                    } else {
                                        $couleur = '222222';
                                    }

                                    $mapURL .= '&path=weight:7|color:0x'.$couleur.'cc';

                                    if($section->journey){
                                        foreach($section->journey->passList as $arret){
                                            $mapURL .= '|'.$arret->station->coordinate->x.','.$arret->station->coordinate->y;
                                        }
                                    }
                                }

                                ?>

                                <img class="trajet-map" src="<?= $mapURL ?>" alt="Carte de votre trajet">

                                <?
                                $dateTrajet = date('d.m.Y', $trajet->from->departureTimestamp);
                                $dateToday = date('d.m.Y');

                                if($dateTrajet !== $dateToday){
                                    echo '<p class="date">Itinéraire valable le '.$dateTrajet.'</p>';
                                }
                                ?>

                                <h1>
                                    <?
                                        echo date('H:i', $trajet->from->departureTimestamp);
                                        echo ' – ';
                                        echo date('H:i', $trajet->to->arrivalTimestamp);

                                        echo ' (';
                                            $duree = $trajet->to->arrivalTimestamp - $trajet->from->departureTimestamp;
                                            echo floor($duree / 60);
                                        echo ' minutes)';
                                    ?>
                                </h1>

                                <div class="resume"><?
                                    foreach ($trajet->sections as $section) {
                                        if($section->journey){
                                            if($section->journey->operator == 'TPG') {
                                                $indiceDeLigne = $section->journey->number;

                                                if(!in_array($indiceDeLigne, $lignesAvecTexteNoir)){
                                                    $w = 'w';
                                                } else {
                                                    $w = '';
                                                }

                                                echo '<span class="picto-ligne l'.$indiceDeLigne.' '.$w.'">';
                                                    echo $indiceDeLigne;
                                                echo '</span>';
                                            } elseif($section->journey->operator == 'SBB') {
                                                $indiceDeLigne = 4;

                                                echo '<span class="picto-ligne l4 w">';
                                                    echo explode(' ', $section->journey->name)[0];
                                                echo '</span>';
                                            } else {
                                                $indiceDeLigne = 35;

                                                echo '<span class="picto-ligne">';
                                                    echo $section->journey->name;
                                                echo '</span>';
                                            }
                                        }
                                    }
                                ?></div>
                            </header>

                            <!-- Trajet -->
                            <? foreach($trajet->sections as $section) { ?>
                                <div class="card">
                                    <? if($section->journey) { ?>
                                    <div class="card-header">
                                        <?
                                        if($section->journey->operator == 'TPG') {
                                            $indiceDeLigne = $section->journey->number;

                                            if(!in_array($indiceDeLigne, $lignesAvecTexteNoir)){
                                                $w = 'w';
                                            } else {
                                                $w = '';
                                            }

                                            echo '<span class="picto-ligne l'.$indiceDeLigne.' '.$w.'">';
                                                echo $indiceDeLigne;
                                            echo '</span>';
                                        } elseif($section->journey->operator == 'SBB') {
                                            $indiceDeLigne = 4;

                                            echo '<span class="picto-ligne l4 w">';
                                                echo explode(' ', $section->journey->name)[0];
                                            echo '</span>';
                                        } else {
                                            $indiceDeLigne = 35;

                                            echo '<span class="picto-ligne">';
                                                echo $section->journey->name;
                                            echo '</span>';
                                        }

                                        // Mesurer le temps
                                        $timestampDepart = $section->departure->departureTimestamp;
                                        $timestampArrivee = $section->arrival->arrivalTimestamp;

                                        $intervalleArrets = round(($timestampArrivee - $timestampDepart) / 60);

                                        if($intervalleArrets >= 2) {
                                            $intervalleArrets .= ' minutes';
                                        } else {
                                            $intervalleArrets .= ' minute';
                                        }

                                        // Retirer le premier et le dernier arrêt de la passList
                                        unset($section->journey->passList[0]);
                                        array_pop($section->journey->passList);
                                        ?>
                                        <span class="destination">
                                            <span style="color: #<?= lineColor($indiceDeLigne) ?>">➜</span> <?= showTpgName($section->journey->to) ?>
                                        </span>
                                        <?php
                                        switch ($section->journey->category) {
                                            case 'NFB':
                                                echo '<i>🚌</i>';
                                                break;
                                            case 'NFO':
                                                echo '<i>🚎</i>';
                                                break;
                                            case 'NFT':
                                                echo '<i>🚋</i>';
                                                break;
                                            case 'R': case 'IR':
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
                                                            <i class="icon t l<?= $indiceDeLigne ?>"></i>
                                                        </div>
                                                        <div class="item-inner">
                                                            <div class="item-title"><?= showTpgName($section->departure->station->name) ?></div>
                                                            <div class="item-after"><?= date('H:i', $section->departure->departureTimestamp) ?></div>
                                                        </div>
                                                    </div>
                                                </li>

                                                <li class="accordion-item">
                                                    <? $stopCount = count($section->journey->passList); ?>
                                                    <? if($stopCount > 0) { ?>
                                                        <a href="#" class="item-content item-link">
                                                            <span class="item-media">
                                                                <i class="icon t icon-resume l<?= $indiceDeLigne ?>"></i>
                                                            </span>
                                                    <? } else { ?>
                                                        <div class="item-content">
                                                            <span class="item-media">
                                                                <i class="icon t icon-resume no-stops l<?= $indiceDeLigne ?>"></i>
                                                            </span>
                                                    <? } ?>
                                                        <span class="item-inner">
                                                            <span class="item-title"><em>
                                                                <?
                                                                    if($stopCount == 0){
                                                                        echo 'Aucun arrêt';
                                                                    } elseif ($stopCount == 1){
                                                                        echo '1 arrêt';
                                                                    } else {
                                                                        echo $stopCount.' arrêts';
                                                                    }
                                                                ?>
                                                            </em></span>
                                                            <span class="item-after"><?= $intervalleArrets ?></span>
                                                        </span>
                                                    <? if($stopCount > 0) { ?>
                                                        </a>
                                                    <? } else { ?>
                                                        </div>
                                                    <? } ?>
                                                    <div class="accordion-item-content">
                                                        <div class="list-block">
                                                            <ul>
                                                                <? foreach($section->journey->passList as $stop){ ?>
                                                                <li>
                                                                    <div class="item-content">
                                                                        <div class="item-media">
                                                                            <i class="icon t l<?= $indiceDeLigne ?>"></i>
                                                                        </div>
                                                                        <div class="item-inner">
                                                                            <div class="item-title"><?= showTpgName($stop->station->name) ?></div>
                                                                            <div class="item-after"><?= date('H:i', $stop->departureTimestamp) ?></div>
                                                                        </div>
                                                                    </div>
                                                                </li>
                                                                <? } ?>
                                                            </ul>
                                                        </div>
                                                    </div>
                                                </li>

                                                <li>
                                                    <div class="item-content">
                                                        <div class="item-media">
                                                            <i class="icon t l<?= $indiceDeLigne ?>"></i>
                                                        </div>
                                                        <div class="item-inner">
                                                            <div class="item-title"><?= showTpgName($section->arrival->station->name) ?></div>
                                                            <div class="item-after"><?= date('H:i', $section->arrival->arrivalTimestamp) ?></div>
                                                        </div>
                                                    </div>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                    <? } elseif($section->walk) { // Marche ?>
                                        <!-- Marche -->
                                        <div class="card-header">
                                            <i class="icon icon-marche"></i>
                                            <strong class="destination">
                                                <?
                                                    echo 'Marcher ';

                                                    $temps = floor(($section->arrival->arrivalTimestamp - $section->departure->departureTimestamp) / 60);
                                                    echo $temps;

                                                    if($temps > 1){
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
                                                                <div class="item-title"><?= showTpgName($section->departure->station->name) ?></div>
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
                                                                <div class="item-title"><?= showTpgName($section->arrival->station->name) ?></div>
                                                                <div class="item-after"><?= date('H:i', $section->arrival->arrivalTimestamp) ?></div>
                                                            </div>
                                                        </div>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    <? } ?>
                                </div>
                            <? } ?>
                        </div>
                    <? } ?>
                </div>
            </div>
        <? } else { // Aucun trajet trouvé ?>
            <div class="content-block">
                <div class="itineraire-erreur">
                    <? if(trim($arrivee) == '') { // Pas de lieu d'arrivée ?>
                        <img src="/resources/img/itineraire_where.png" alt="Où souhaitez-vous aller ?">
                        <h1>Où souhaitez-vous aller ?</h1>
                        <p>Vous n'avez sélectionné aucun lieu d'arrivée.</p>
                        <a href="#" class="back button">Retour</a>
                    <? } else { ?>
                        <img src="/resources/img/itineraire_erreur.png" alt="Aucun itinéraire trouvé">
                        <h1>Aucun itinéraire trouvé</h1>
                        <p>Aucun itinéraire n'a été trouvé. Essayez de modifier les termes de votre recherche.</p>
                        <a href="#" class="back button">Réessayer</a>
                    <? } ?>
                </div>
            </div>
        <? } ?>
    </div>
    <? if(count($json->connections) > 0) { ?>
        <div class="swiper-container-horizontal toolbar">
            <div class="swiper-pagination"></div>
        </div>
    <? } ?>
  </div>
</div>
