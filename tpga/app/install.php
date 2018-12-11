<?php
require_once __DIR__.'/../../config.inc.php';
use TPGwidget\Data\{Lines, Stops};

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET');
if (!isset($_GET["id"])) {
    $erreur = "Paramètre manquant";
    $nextDepartures = null;
} else {
    $file = 'https://prod.ivtr-od.tpg.ch/v1/GetNextDepartures.xml?key='.getenv('TPG_API_KEY').'&stopCode=' . htmlentities($_GET["id"]);
    $nextDepartures = @simplexml_load_file($file);
}

if ($nextDepartures) { ?>
    <div data-page="install" class="page page-page">
        <div class="navbar">
            <div class="navbar-inner">
                <div class="left">
                    <a href="#" class="back link icon-only">
                        <i class="icon icon-back"></i>
                    </a>
                </div>
                <div class="center"><?= Stops::correct($nextDepartures->stop->stopName) ?></div>
            </div>
        </div>

        <div class="page-content">
            <div class="content-block">
                <ul class="lignes">
                    <?php
                    $lignes = [];

                    foreach ($nextDepartures->stop->connections->connection as $connection) {
                        $lignes[] = $connection->lineCode;
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
            <div class="content-block">
                <a
                    href="googlechrome://navigate?url=https://tpga.nicolapps.ch/<?=$nextDepartures->stop->stopCode?>/"
                    class="button button-big button-fill external button-install"
                    data-tapped="0"
                >
                    Installer
                </a>
            </div>
        </div>
    </div>
<?php } else { ?>
    <div data-page="install" class="page page-page layout-dark">
        <div class="navbar">
            <div class="navbar-inner">
                <div class="left">
                    <a href="#" class="back link icon-only">
                        <i class="icon icon-back"></i>
                    </a>
                </div>
                <div class="center">Erreur</div>
            </div>
        </div>

        <div class="page-content">
            <section class="graym">
                <h1>:-(</h1>
                <h2>
                    Erreur :
                    <?php
                    if ($erreur) {
                        print $erreur;
                    } else {
                        print "Serveur TPG indisponible";
                    }
                    ?>
                </h2>
            </section>
        </div>
    </div>
<?php } ?>
