<?
include '../stops.php';

if(!isset($_GET["id"])){
    $erreur = "Paramètre manquant";
} else {
    require '../../tpgdata/apikey.php';
    $file = 'http://prod.ivtr-od.tpg.ch/v1/GetNextDepartures.xml?key='.$key.'&stopCode=' . htmlentities($_GET["id"]);
    $nextDepartures = @simplexml_load_file($file);
}

if($nextDepartures){
?>
<div class="navbar">
  <div class="navbar-inner">
    <div class="left">
      <a href="#" class="back link">
        <i class="icon icon-back"></i>
        <span>Retour</span>
       </a>
    </div>
    <div class="center sliding"><?=$nextDepartures->stop->stopName?></div>
  </div>
</div>
<div class="pages">
  <div data-page="install" class="page page-page">
    <div class="page-content">
        <div class="card">
            <div class="card-header"><?=$nextDepartures->stop->stopName?></div>
            <div class="card-content">
            <div class="card-content-inner">
                <ul class="lignes">
                <?
                $lignes = [];

                   foreach($nextDepartures->stop->connections->connection as $connection){
                       $lignes[] = $connection->lineCode;
                   }

                   $lignes = array_unique($lignes);

                   foreach($lignes as $key => $ligne){
                       if(substr($ligne, "0", "1") == "N"){
                            $lignesNocambus[] = $ligne;
                            unset($lignes[$key]);
                       }
                   }

                   foreach($lignes as $ligne){
                       echo '<li class="l'.$ligne.'">'.$ligne.'</li>';
                   }

                   foreach($lignesNocambus as $ligne){
                       echo '<li class="l'.$ligne.'">'.$ligne.'</li>';
                   }
                ?>
                </ul>
            </div>
            </div>
            <div class="card-footer"><a href="#" data-stop="<?=$nextDepartures->stop->stopCode?>" class="button button-big button-fill install">Installer</a></div>
        </div>
    </div>
  </div>
</div>
<?
} else {
?>
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
		    <h2>Erreur : <?
    		    if($erreur){
        		    print $erreur;
    		    }else{
        		    print "Serveur TPG indisponible";
    		    }

    		    ?></h2>
	    </section>
    </div>
  </div>
</div>
<? } ?>
