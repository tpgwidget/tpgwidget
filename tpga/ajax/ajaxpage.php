<div data-page="page-<?=htmlentities($_GET["id"])?>" class="page page-page layout-dark">
    <div class="navbar">
      <div class="navbar-inner">
        <div class="left">
          <a href="#" class="back link icon-only">
            <i class="icon icon-back"></i>
           </a>
        </div>
        <div class="center sliding"><?=$_GET["name"]?></div>
      </div>
    </div>
    <div class="page-content">
        <section class="graym">
            <span class="preloader preloader-white"></span>
		    <h2>Chargement...</h2>
        </section>
    </div>
</div>
