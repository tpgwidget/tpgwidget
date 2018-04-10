<?php
/**********************************
* FONCTION stopFilter(stop);      *
***********************************
* Paramètres :					  *
* - stop : Nom d'une destination  *
*		(par exemple : Palettes)  *
*								  *
* Renvoie la destination avec une *
* bonne mise en forme : accents,  *
* majuscules, ...				  *
**********************************/

function stopFilter($stop) {

    $stop = trim($stop);

	switch($stop) {
	case 'Gare Eaux-Vives':
		return 'Gare des Eaux-Vives';
	case 'Ziplo':
		return 'ZIPLO';
	case 'De stael':
		return 'De-Staël <small>(dépôt)</small>';
    case 'Palettes - armes':
		return 'Palettes - Armes';
    case 'Jar.-Botanique':
		return 'Jardin Botanique';
    case 'Bachet':
    	return 'Bachet-de-Pesay';
    case 'Palettes bachet':
        return 'Palettes - Bachet';
	case 'Onex-cite':
		return 'Onex-Cité';
	case 'Lignon tours':
		return 'Lignon-Tours';
	case 'Carouge':
		return 'Carouge-Rondeau';
	case 'Carouge (Rondeau)':
		return 'Carouge-Rondeau';
	case 'Thonex-vallard':
		return 'Thônex-Vallard';
	case 'Th-Vallard-Dne':
		return 'Thônex-Vallard';
	case 'Place de neuve':
		return 'Place de Neuve';
	case 'Champel':
		return 'Crêts-de-Champel';
	case 'Neydens-vitam':
		return 'Neydens-Vitam';
	case 'Vernier':
		return 'Vernier-Village';
	case 'Champel':
		return 'Crêts-de-Champel';
	case 'P+R Etoile':
		return 'P+R Étoile';
	case 'Oms':
		return 'OMS';
	case 'P+r veigy':
		return 'P+R Veigy';
	case 'P bernex':
		return 'P+R Bernex';
	case 'Puplinge-mairie':
		return 'Puplinge-Mairie';
	case 'Veyrier-Tournet.':
		return 'Veyrier-Tournettes';
    case 'Palettes-Bachet':
        return 'Palettes - Bachet <small>(dépôt)</small>';
    case 'Cs la becassiere':
        return 'CS La Bécassière';
    case 'Annemasse-gare':
        return 'Annemasse-Gare';
    case 'Annemasse gare':
        return 'Annemasse-G. EXPRESS';
    case 'Ecole medecine':
        return 'École-de-Médecine';
    case 'Hopital la tour':
        return 'Hôpital de la Tour';
    case 'Loëx-hôpital':
        return 'Loëx-Hôpital';
    case 'C.o Renard':
        return 'CO Renard';
    case 'Co renard':
        return 'CO Renard';
    case 'Challex-la halle':
        return 'P+R Challex-La Halle';
    case 'Sezenove':
        return 'Sézenove';
    case 'Emile zola':
        return 'Émile Zola';
    case 'Hopitaux':
        return 'Hôpitaux';
    case 'Lancy-hubert':
        return 'Lancy-Hubert';
    case 'Lancy - hubert':
        return 'Lancy-Hubert';
    case 'Coll.Claparède':
        return 'Collège Claparède';
    case 'Pl. Eaux-Vives':
        return 'Place des Eaux-Vives';
    case 'La plaine-gare':
        return 'La Plaine-Gare';
    case 'Gd-Saconnex-Place':
        return 'Grand-Saconnex-Place';
    case 'Gd-Saconnex-Douane':
        return 'Grand-Saconnex-Douane';
    case 'Gd-Saconnex-Mairie':
        return 'Grand-Saconnex-Mairie';
    case 'Hôpital la Tour':
        return 'Hôpital de La Tour';
    case 'Jardin-Alpin-Vi.':
        return 'Jardin-Alpin-Vivarium';
    case 'Aeroport-p47':
        return 'Aéroport-P47';
    case 'Vernier-Parf.':
        return 'Vernier-Parfumerie';
    case 'Les esserts':
        return 'Les Esserts';
    case 'Jardin botanique':
        return 'Jardin Botanique';
    default:
        return $stop;
    }
}
