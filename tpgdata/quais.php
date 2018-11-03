<?php
function qprint($quai) {
    echo '<div class="quai">'.htmlentities($quai).'</div>';
}

function quai($stopCode, $departureLine, $departureDestination) {
    switch ($stopCode) {
        case 'ESRT':
            switch($departureDestination) {
                case 'Lancy-Hubert': case 'Rive':
                    qprint('1');
                    break;
                case 'Cressy': case 'Onex': case 'Onex-Salle communale': case 'Jardin Botanique':
                    qprint('2');
                    break;
                case 'Aéroport':
                    if ($departureLine == '23') {
                        qprint('3');
                    } elseif ($departureLine == "28") {
                        qprint('2');
                    }
                case 'Chancy-Douane':
                    if ($departureLine == "K") {
                        qprint('3');
                    } elseif ($departureLine == "NJ") {
                        qprint('2');
                    }
                    break;
                case 'Gare des Eaux-Vives': case 'Carouge-Tours': case 'Le Rolliet': case 'ZIPLO': case 'Ziplo':  case 'Pougny-Gare': case 'Avusy':
                    qprint('3');
                    break;
                case 'Nations': case 'Cité Lignon': case 'Aéroport-P47':
                    qprint('4');
                    break;
                default:
                    if ($departureLine == "14") { // inclure tous les parcours de 14 + rentrées de dépôt
                        qprint('1');
                    }
                    break;
            }
            break;
    }
}
