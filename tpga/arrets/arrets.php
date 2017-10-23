<div data-page="arrets" class="page page-arrets">

    <div class="navbar">
        <div class="navbar-inner">
            <div class="left"><a href="#" class="back link icon-only"><i class="icon icon-back"></i></a></div>
            <div class="center">Liste des arrêts</div>
        </div>
    </div>

    <div class="page-content">
        <?php
        require '../../tpgdata/apikey.php';
        $stops = simplexml_load_file('http://prod.ivtr-od.tpg.ch/v1/GetStops.xml?key='.$key);
        ?>
        <form data-search-list=".virtual-list" class="searchbar searchbar-init">
            <div class="searchbar-input">
                <input id="arrets-search" type="search" placeholder="Rechercher..."/><a href="#" class="searchbar-clear"></a>
            </div><a href="#" class="searchbar-cancel">Annuler</a>
        </form>

        <div class="list-block arrets-location">
            <ul>
                <li class="item-content location-message">
                    <div class="item-media">
                        <i class="icon icon-location"></i>
                    </div>
                    <div class="item-inner">
                        <div class="item-title">Chargement...</div>
                    </div>
                </li>
                <li class="enable-geolocation">
                    <a href="#" class="item-link item-content">
                        <div class="item-media">
                            <i class="icon icon-location"></i>
                        </div>
                        <div class="item-inner">
                            <div class="item-title">Afficher les arrêts à proximité</div>
                        </div>
                    </a>
                </li>
            </ul>
        </div>

        <div class="list-block virtual-list searchbar-found"></div>
        <div class="list-block searchbar-not-found">
            <ul>
                <li class="item-content">
                    <div class="item-inner">
                        <div class="item-title">Aucun arrêt trouvé</div>
                    </div>
                </li>
            </ul>
        </div>
    </div>
</div>
