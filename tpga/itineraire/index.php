<div data-page="itineraire" class="page page-itineraire">
    <div class="navbar">
        <div class="navbar-inner">
            <div class="left"><a href="#" class="back link icon-only"><i class="icon icon-back"></i></a></div>
            <div class="center">Itinéraire</div>
        </div>
    </div>

    <div class="page-content">
        <header></header>

        <form class="form-itineraire ajax-submit" method="POST" action="/itineraire/trajets.php">
            <div class="content-block-title">Où souhaitez-vous aller ?</div>
            <div class="list-block">
                <div role="button" class="itineraire-invert floating-button ripple">
                    <svg height="24" viewBox="0 0 24 24" width="24" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                        <defs>
                            <path d="M0 0h24v24H0V0z" id="a"/>
                        </defs>
                        <clipPath id="b">
                            <use overflow="visible" xlink:href="#a"/>
                        </clipPath>
                        <path clip-path="url(#b)" d="M9.01 14H2v2h7.01v3L13 15l-3.99-4v3zm5.98-1v-3H22V8h-7.01V5L11 9l3.99 4z"/>
                    </svg>
                </div>

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
                                    <?php
                                        if ($_GET['departure']) {
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
                            <input name="arrivee" type="hidden" value="" required>
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
                    </li>
                    <div class="row">
                        <div class="col-50">
                            <li>
                              <label class="label-radio item-content">
                                <input type="radio" name="isArrivalTime" value="0" checked="checked">
                                <div class="item-media">
                                  <i class="icon icon-form-radio"></i>
                                </div>
                                <div class="item-inner">
                                  <div class="item-title">Départ</div>
                                </div>
                              </label>
                            </li>
                        </div>
                        <div class="col-50">
                            <li>
                              <label class="label-radio item-content">
                                <input type="radio" name="isArrivalTime" value="1">
                                <div class="item-media">
                                  <i class="icon icon-form-radio"></i>
                                </div>
                                <div class="item-inner">
                                  <div class="item-title">Arrivée</div>
                                </div>
                              </label>
                            </li>
                        </div>
                    </div>
                </ul>
            </div>

            <button type="submit" class="button button-raised button-big button-fill">Rechercher un itinéraire</button>
        </form>
    </div>
</div>
