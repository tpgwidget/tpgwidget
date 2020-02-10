<?php
require_once __DIR__.'/../../config.inc.php';
use TPGwidget\Data\{Lines, Stops};

if (!preg_match('/^\d{6}$/', $_GET['id'])) {
    http_response_code(404);
    die('Erreur : Aucun arrêt spécifié');
}

// Get the widget from the database
$req = $bdd->prepare('SELECT * FROM widgets WHERE id = ?');
$req->execute([$_GET['id']]);
$widget = $req->fetch();

if (empty($widget)) {
    http_response_code(404);
    die('Erreur : Widget inconnu');
}

$stopW = $widget['stop'];
$nameW = Stops::format($widget['name']);
$rawNameW = Stops::correct($widget['name']);
?>
<!DOCTYPE html>
<html translate="no">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, minimum-scale=1, user-scalable=no, minimal-ui">
    <link rel="manifest" href="/manifest.json.php?id=<?= urlencode($_GET['id']) ?>&amp;stopCode=<?= urlencode($stopW) ?>&amp;stopName=<?=urlencode($rawNameW) ?>">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="theme-color" content="#ff6600">

    <title><?= $rawNameW ?></title>

    <link rel="stylesheet" href="/resources/css/framework7.material.min.css?171">
    <link rel="stylesheet" href="/resources/css/tpgwidget.min.css?2019">
    <style>
    <?php
    foreach (Lines::all() as $line) {
        echo '.l'.str_replace('+', 'plus', $line['name']);
        echo '{ background: '.$line['background'].' }';
    }
    ?>
    </style>

    <!-- Icônes -->
    <link rel="icon" sizes="192x192" href="/icon/<?= urlencode($stopW) ?>/192.png">
    <link rel="apple-touch-icon" sizes="192x192" href="/icon/<?= urlencode($stopW) ?>/192.png">
</head>
<body>
    <div class="panel-overlay"></div>

    <div class="panel panel-left panel-cover">
        <a class="close-panel" href="/about.php">
            <header>
                <img src="/resources/img/logo.png" alt="Logo de TPGwidget" title="TPGwidget">
            </header>
        </a>

        <div class="list-block">
            <ul>
                <li>
                    <a href="/itineraire/?departure=<?= urlencode($rawNameW) ?>" class="item-link close-panel">
                        <div class="item-content">
                            <div class="item-media">
                                <i class="icon i-itineraire"></i>
                            </div>
                            <div class="item-inner">
                                <div class="item-title">Itinéraire</div>
                            </div>
                        </div>
                    </a>
                </li>
                <li>
                    <a href="/arrets/arrets.php" class="item-link close-panel">
                        <div class="item-content">
                            <div class="item-media">
                                <i class="icon i-stops"></i>
                            </div>
                            <div class="item-inner">
                                <div class="item-title">Liste des arrêts</div>
                            </div>
                        </div>
                    </a>
                </li>
                <li>
                    <a href="/infotrafic.php" class="item-link close-panel">
                        <div class="item-content">
                            <div class="item-media">
                                <i class="icon i-infotraffic"></i>
                            </div>
                            <div class="item-inner">
                                <div class="item-title">Info trafic</div>
                            </div>
                        </div>
                    </a>
                </li>
                <li>
                    <a href="/plans.php" class="item-link close-panel">
                        <div class="item-content">
                            <div class="item-media">
                                <i class="icon i-plans"></i>
                            </div>
                            <div class="item-inner">
                                <div class="item-title">Plans</div>
                            </div>
                        </div>
                    </a>
                </li>
                <li>
                    <a href="/about.php" class="item-link close-panel">
                        <div class="item-content">
                            <div class="item-media">
                                <i class="icon i-about"></i>
                            </div>
                            <div class="item-inner">
                                <div class="item-title">À propos</div>
                            </div>
                        </div>
                    </a>
                </li>
            </ul>
        </div>
    </div>

    <div class="views">
        <div class="view view-main" data-stop="<?= $stopW ?>">
            <div class="pages navbar-fixed">
                <div data-page="index-<?= $stopW ?>" class="page page-index layout-dark">
                    <div class="navbar">
                        <div class="navbar-inner">
                            <div class="left">
                                <a href="#" class="open-panel link icon-only">
                                    <i class="icon icon-bars"></i>
                                </a>
                            </div>
                            <div class="center"><?= $nameW ?></div>
                        </div>
                    </div>

                    <div class="page-content"></div>
                </div>
            </div>
        </div>
    </div>

    <script type="text/javascript" src="/resources/js/framework7.min.js?171"></script>
    <script type="text/javascript" src="/resources/js/tpgwidget.min.js?2020-1"></script>
</body>
</html>
