<?php
require_once __DIR__.'/../../config.inc.php';
use TPGwidget\Data\Stops;

$formattedStopName = Stops::format(str_replace('_', '/', $_GET['name'] ?? ''));
?>
<div class="navbar">
  <div class="navbar-inner">
    <div class="left">
      <a href="#" class="back link icon-only">
        <i class="icon icon-back"></i>
       </a>
    </div>
    <div class="center sliding"><?= $formattedStopName ?></div>
    <div class="right">
      <a href="#" class="open-panel link icon-only"><i class="icon icon-panel"></i></a>
    </div>
  </div>
</div>
<div class="pages">
  <div data-page="page-<?= htmlentities($_GET['id'] ?? '') ?>" class="page page-page layout-dark">
    <div class="page-content">
        <section class="graym">
            <span class="preloader preloader-white"></span>
            <h2>Chargement...</h2>
        </section>
    </div>
  </div>
</div>
