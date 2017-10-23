<?
	function qprint($quai){
		print '<div class="quai">'.htmlentities($quai).'</div>';
	}
	
	function quai($stopCode, $departureLine, $departureDestination){
		switch ($stopCode){
			
			case 'ESRT':
			
				switch($departureDestination){
					case 'Lancy-Hubert': case 'Rive':
						qprint('1');
						break;
					case 'Cressy':
						qprint('2');
						break;
					case 'Chancy-Douane':
						if($departureLine == "K"){
							qprint('3');
						} elseif ($departureLine == "NJ") {
							qprint('2');
						}
						break;
					case 'Gare Eaux-Vives': case 'Tours-de-Carouge': case 'Le Rolliet': case 'ZIPLO': case 'Ziplo':  case 'Pougny-Gare': case 'Avusy':
						qprint('3');
						break;
					case 'Nations': case 'Aéroport': case 'Cité Lignon':	
						qprint('4');
						break;
					default:
						if($departureLine == "14"){ // inclure tous les parcours de 14 + rentrées de dépôt
							qprint('1');
						}
						break;
				}
				
				break;
		}
	}
?>