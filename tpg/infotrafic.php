<div class="navbar">
  <div class="navbar-inner">
    <div class="left"><a href="#" class="back link"> <i class="icon icon-back"></i><span>Retour</span></a></div>
    <div class="center sliding">Info trafic</div>
    <div class="right">
      <a href="#" class="open-panel link icon-only"><i class="icon icon-panel"></i></a>
    </div>
  </div>
</div>
<div class="pages">
  <div data-page="infotraffic" class="page">
    <div class="page-content pull-to-refresh-content infotraffic">
    <div class="pull-to-refresh-layer">
      <div class="preloader"></div>
      <div class="pull-to-refresh-arrow"></div>
    </div>
    <div id="perturbations-all">
        <? include 'ajax/ajaxperturbations.php'; ?>
    </div>
    </div>
  </div>
</div>