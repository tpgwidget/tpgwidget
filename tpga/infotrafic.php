<div data-page="infotraffic" class="page">
    <div class="navbar">
        <div class="navbar-inner">
            <div class="left"><a href="#" class="back link icon-only"><i class="icon icon-back"></i></a></div>
            <div class="center">Info trafic</div>
        </div>
    </div>

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
