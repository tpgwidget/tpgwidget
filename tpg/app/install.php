<?php
require __DIR__.'/../../config.inc.php';
use TPGwidget\Data\{Lines, Stops};

if (!isset($_GET["id"])) {
    $erreur = "Paramètre manquant";
    $nextDepartures = null;
} else {
//    $file = 'http://prod.ivtr-od.tpg.ch/v1/GetNextDepartures.xml?key='.getenv('TPG_API_KEY').'&stopCode=' . htmlentities($_GET["id"]);
//    $nextDepartures = @simplexml_load_file($file);

    // Fall Back To Old API - This marks the final moments of TPGw and Third Party TPG Open Data Apps
    $stops = json_decode(file_get_contents("http://prod.ivtr.tpg.ch/GetTousArrets.json?transporteur=All"))->connexions->connexion;
    $nextDepartures = [];
    foreach ($stops as $stop) {
        if (htmlentities($_GET["id"]) === $stop->codeArret) {
            $nextDepartures = $stop;
        }
    }

}

if ($nextDepartures && isset($nextDepartures->nomArret)) { ?>
    <div class="navbar">
        <div class="navbar-inner">
            <div class="left">
                <a href="#" class="back link">
                    <i class="icon icon-back"></i>
                    <span>Retour</span>
                </a>
            </div>
            <div class="center sliding"><?= Stops::format($nextDepartures->nomArret) ?></div>
        </div>
    </div>
    <div class="pages">
        <div data-page="install" class="page page-page">
            <div class="page-content">
                <div class="card">
                    <div class="card-header"><span><?= Stops::format($nextDepartures->nomArret) ?></span></div>
                    <div class="card-content">
                        <div class="card-content-inner">
                            <ul class="lignes">
                                <?php
                                $lignes = [];

                                foreach (explode(",", $nextDepartures->lignes ?? $nextDepartures->ligne) as $connection) {
                                    $lignes[] = $connection;
                                }

                                $lignes = array_unique($lignes);
                                $lignesNoctambus = [];

                                foreach ($lignes as $index => $ligne) {
                                    if (preg_match('/^N.$/', $ligne)) {
                                        $lignesNoctambus[] = $ligne;
                                        unset($lignes[$index]);
                                    }
                                }

                                foreach (array_merge($lignes, $lignesNoctambus) as $ligne) {
                                    $details = Lines::get($ligne);
                                    ?>
                                    <li style="background-color: <?= $details['background'] ?>; color: <?= $details['text'] ?>">
                                        <?= $ligne ?>
                                    </li>
                                <?php } ?>
                            </ul>
                        </div>
                    </div>
                    <div class="card-footer">
                        <a href="#" data-stop="<?=$nextDepartures->codeArret?>" class="button button-big button-fill install">
                            Installer
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php } else { ?>
    <div class="navbar">
        <div class="navbar-inner">
            <div class="left">
                <a href="#" class="back link">
                    <i class="icon icon-back"></i>
                    <span>Retour</span>
                </a>
            </div>
            <div class="center sliding">Erreur</div>
            <div class="right">
                <a href="#" class="link icon-only open-panel"><i class="icon icon-bars-blue"></i></a>
            </div>
        </div>
    </div>
    <div class="pages">
        <div data-page="install" class="page page-page layout-dark">
            <div class="page-content">
                <section class="graym">
                    <h1>:-(</h1>
                    <h2>
                        Erreur :
                        <?php
                        if (isset($erreur)) {
                            print $erreur;
                        } else {
                            print "Serveur TPG indisponible";
                        }
                        ?>
                    </h2>
                </section>
            </div>
        </div>
    </div>
<?php } ?>
