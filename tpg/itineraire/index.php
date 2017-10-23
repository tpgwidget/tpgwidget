<div class="navbar">
  <div class="navbar-inner">
    <div class="left"><a href="#" class="back link"> <i class="icon icon-back"></i><span>Retour</span></a></div>
    <div class="center sliding">Itinéraire</div>
    <div class="right">
      <a href="#" class="open-panel link icon-only"><i class="icon icon-panel"></i></a>
    </div>
  </div>
</div>
<div class="pages">
  <div data-page="itineraire" class="page page-itineraire">
    <div class="page-content">
        <header></header>

        <form class="form-itineraire ajax-submit" method="POST" action="/itineraire/trajets.php">
            <div class="content-block-title">Où souhaitez-vous aller ?</div>
            <div class="list-block">
                <ul>
                    <li>
                        <a href="#" class="item-link item-content autocomplete-opener itineraire-depart">
                            <input name="depart" type="hidden" value="<?= htmlspecialchars($_GET['departure']) ?>">
                            <div class="item-media">
                                <i class="icon icon-depart"></i>
                            </div>
                            <div class="item-inner">
                                <div class="item-title">De : </div>
                                <div class="item-after">
                                    <?
                                        if($_GET['departure']) {
                                            echo htmlspecialchars($_GET['departure']);
                                        } else {
                                            echo 'Cliquez pour choisir';
                                        }
                                    ?>
                                </div>
                            </div>
                        </a>
                    </li>

                    <li>
                        <a href="#" class="item-link item-content autocomplete-opener itineraire-arrivee">
                            <input name="arrivee" type="hidden" value="">
                            <div class="item-media">
                                <i class="icon icon-arrivee"></i>
                            </div>
                            <div class="item-inner">
                                <div class="item-title">À :</div>
                                <div class="item-after">Cliquez pour choisir</div>
                            </div>
                        </a>
                    </li>
                </ul>
            </div>

            <div class="content-block-title">Date et heure</div>
            <div class="list-block">
                <ul>
                    <li class="itineraire-dateheure">
                        <div class="item-content">
                            <div class="item-media">
                                <i class="icon icon-form-calendar"></i>
                            </div>
                            <div class="item-inner">
                                <div class="item-input">
                                    <input type="datetime-local" name="dateheure" value="<?= date("Y-m-d\TH:i") ?>:00" required>
                                </div>
                            </div>
                        </div>
                        <div class="buttons-row">
                            <a href="#" class="button heure-depart active">Départ</a>
                            <input id="isArrivalTime" type="hidden" name="isArrivalTime" value="0">
                            <a href="#" class="button heure-arrivee">Arrivée</a>
                        </div>
                    </li>
                </ul>
            </div>

            <button type="submit" class="button button-big button-fill">Rechercher un itinéraire</button>
        </form>
    </div>
  </div>
</div>
