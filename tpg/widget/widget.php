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

$iconPrefix = '';
if (getenv('APP_ENV') === 'beta') {
    $iconPrefix = '/beta';
}
?>
<!DOCTYPE html>
<html lang="fr">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, minimum-scale=1, user-scalable=no, viewport-fit=cover">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta name="apple-mobile-web-app-title" content="<?= $rawNameW ?>">

    <title>TPGwidget</title>

    <link rel="stylesheet" href="/resources/css/framework7.ios.min.css?disruptions">
    <link rel="stylesheet" href="/resources/css/tpgwidget.css">

    <!-- Icônes -->
    <link href="https://www.nicolapps.ch/tpgicon<?= $iconPrefix ?>/<?= urlencode($stopW) ?>/152.png" sizes="152x152" rel="apple-touch-icon">
    <link href="https://www.nicolapps.ch/tpgicon<?= $iconPrefix ?>/<?= urlencode($stopW) ?>/144.png" sizes="144x144" rel="apple-touch-icon">
    <link href="https://www.nicolapps.ch/tpgicon<?= $iconPrefix ?>/<?= urlencode($stopW) ?>/120.png" sizes="120x120" rel="apple-touch-icon">
    <link href="https://www.nicolapps.ch/tpgicon<?= $iconPrefix ?>/<?= urlencode($stopW) ?>/114.png" sizes="114x114" rel="apple-touch-icon">
    <link href="https://www.nicolapps.ch/tpgicon<?= $iconPrefix ?>/<?= urlencode($stopW) ?>/76.png" sizes="76x76" rel="apple-touch-icon">
    <link href="https://www.nicolapps.ch/tpgicon<?= $iconPrefix ?>/<?= urlencode($stopW) ?>/72.png" sizes="72x72" rel="apple-touch-icon">
    <link href="https://www.nicolapps.ch/tpgicon<?= $iconPrefix ?>/<?= urlencode($stopW) ?>/57.png" sizes="57x57" rel="apple-touch-icon">
  </head>
  <body>
    <div class="panel-overlay"></div>
    <div class="panel panel-right panel-reveal">
        <h1>TPG<span>widget</span></h1>
    </div>
    <div class="views">
      <div class="view view-main">
        <div class="navbar theme-white">
          <div class="navbar-inner">
            <div class="center sliding">TPGwidget</div>
          </div>
        </div>
        <div class="pages navbar-through toolbar-through">
          <div data-page="sunset" class="page page-sunset">
              <div class="page-content">
                  <div class="sunset-header">
                      <img
                          class="sunset-hero"
                          srcset="https://tpgdata.nicolapps.ch/sunset/sunset-400w.avif 400w, https://tpgdata.nicolapps.ch/sunset/sunset-600w.avif 600w, https://tpgdata.nicolapps.ch/sunset/sunset-800w.avif 800w, https://tpgdata.nicolapps.ch/sunset/sunset-1000w.avif 1000w, https://tpgdata.nicolapps.ch/sunset/sunset-1200w.avif 1200w, https://tpgdata.nicolapps.ch/sunset/sunset-1600w.avif 1600w, https://tpgdata.nicolapps.ch/sunset/sunset-2000w.avif 2000w, https://tpgdata.nicolapps.ch/sunset/sunset-2400w.avif 2400w"
                          sizes="(max-width: 400px) 400px, (max-width: 600px) 600px, (max-width: 800px) 800px, (max-width: 1000px) 1000px, (max-width: 1200px) 1200px, (max-width: 1600px) 1600px, (max-width: 2000px) 2000px, (min-width: 2001px) 2400px"
                          src="https://tpgdata.nicolapps.ch/sunset/sunset.jpg"
                          alt=""
                          width="3067"
                          height="1725"
                      />
                  </div>

                  <div class="sunset">
                      <p>
                          Pendant près de 10 ans, TPGwidget a aidé les Genevois·es à se déplacer.
                          Aujourd’hui, les TPG ont supprimé la source d’informations dont TPGwidget dépendait,
                          ce qui m’oblige à contrecœur à mettre mon application hors service.
                      </p>
                      <p>
                          J’ai créé TPGwidget lorsque j’avais 13 ans pour réaliser mon rêve de gosse de créer une app.
                          J’ai appris énormément grâce à ce projet et je tiens à vous remercier du fond du cœur pour votre soutien.
                      </p>

                      <div class="sunset-signature">
                        <img src="https://tpgdata.nicolapps.ch/sunset/nicolas.jpg" alt="">
                        <div>
                          <strong>Nicolas Ettlin</strong>
                          <br>
                          Créateur de TPGwidget
                        </div>
                      </div>

                      <p class="sunset-credits">
                          Photo de
                          <a class="external" href="https://www.instagram.com/trambusal.off/">TramBusAl</a>
                      </p>
                  </div>
              </div>
          </div>
        </div>
      </div>
    </div>

    <script type="text/javascript" src="/resources/js/framework7.min.js?171"></script>
    <script type="text/javascript" src="/resources/js/tpgwidget.js"></script>
  </body>
</html>
