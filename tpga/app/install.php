<?php
require_once __DIR__ . '/../../config.inc.php';

use TPGwidget\Data\{Lines, Stops};

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET');
if (!isset($_GET["id"])) {
    $erreur = "Paramètre manquant";
    $nextDepartures = null;
} else {
    $file = 'https://prod.ivtr-od.tpg.ch/v1/GetNextDepartures.xml?key=' . getenv('TPG_API_KEY') . '&stopCode=' . htmlentities($_GET["id"]);
    $nextDepartures = @simplexml_load_file($file);
}

if ($nextDepartures && isset($nextDepartures->stop->stopName)) { ?>
    <div data-page="install" class="page page-page">
        <div class="navbar">
            <div class="navbar-inner">
                <div class="left">
                    <a href="#" class="back link icon-only">
                        <i class="icon icon-back"></i>
                    </a>
                </div>
                <div class="center"><?= Stops::format($nextDepartures->stop->stopName) ?></div>
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

                <?php
                // Temporary fix: for Chrome version ≥ 78, ask the user to open the link in Chrome by themselves
                $userAgent = $_SERVER['HTTP_USER_AGENT'];
                $browsersBlockList = ['Chrome/78', 'Chrome/79', 'Chrome/80', 'Chrome/81'];

                $manualInstall = false;
                foreach ($browsersBlockList as $b) {
                    if (strpos($userAgent, ' ' . $b) !== false) {
                        $manualInstall = true;
                    }
                }

                if (!$manualInstall) { ?>
                    <a
                        href="googlechrome://navigate?url=https://tpga.nicolapps.ch/<?= $nextDepartures->stop->stopCode ?>/"
                        class="button button-big button-fill external button-install"
                        data-tapped="0"
                    >
                        Installer
                    </a>
                <?php } else { ?>
                    <div class="manual-install-instructions">
                        <header>
                            <img src="https://www.nicolapps.ch/tpgw-hotfix/chrome.png" alt="Logo de Google Chrome">
                            <h3>Pour installer cet arrêt sur votre écran d’accueil, veuillez ouvrir le lien suivant
                                <strong>dans Google Chrome</strong> :</h3>
                        </header>

                        <div class="ath-link">
                            <?php $uniq = uniqid(); ?>
                            <input id="link_<?= $uniq ?>" type="text"
                                   value="https://tpga.nicolapps.ch/<?= $nextDepartures->stop->stopCode ?>/" readonly>

                            <button
                                class="button button-fill"
                                onclick="link_<?= $uniq ?>.select() & document.execCommand('copy') & $$('.link-copied').css('display', 'block')"
                                title="Copier le lien dans le presse-papier"
                            >
                                <img src="https://www.nicolapps.ch/tpgw-hotfix/copy.svg" width="18" alt="">
                            </button>
                        </div>

                        <div style="text-align: right; padding-right: 10px; padding-top: 5px;">
                            <img src="https://www.nicolapps.ch/tpgw-hotfix/help.svg" alt="">
                        </div>

                        <div class="link-copied" style="display: none">
                            ✓ Lien copié !
                        </div>

                    </div>
                    <style>
                        .manual-install-instructions {
                            padding: 16px;
                            color: #856404;
                            background-color: #fff3cd;
                            border-color: #ffeeba;
                            border-radius: 4px;
                        }

                        .manual-install-instructions header {
                            display: flex;
                            align-items: center;
                            margin-bottom: 15px;
                        }

                        .manual-install-instructions header img {
                            width: 64px;
                            height: 64px;
                        }

                        .manual-install-instructions h3 {
                            flex: 1;
                            margin-top: 0;
                            margin-bottom: 0;
                            margin-left: 15px;
                            font-weight: 400;
                            font-size: 16px;
                        }

                        .ath-link {
                            display: grid;
                            grid-template-columns: 1fr auto;
                            border-radius: 4px;
                            overflow: hidden;
                            box-shadow: 0 1px 3px rgba(0, 0, 0, .15);
                        }

                        .ath-link button {
                            display: flex;
                            align-items: center;
                            justify-content: center;
                            border-radius: 0;
                        }

                        .ath-link input {
                            border: 0;
                            padding: 4px;
                            font-size: 12px;
                            font-family: monospace;
                            color: #888;
                        }

                        .link-copied {
                            color: #1b5e20;
                            font-weight: bold;
                        }
                    </style>
                <?php } ?>
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
