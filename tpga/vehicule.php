<?php

require '../tpgdata/vehicules/vehicules.php';

$vehicule = new Vehicule(htmlspecialchars($_GET['id'] ?? ''));
?>

<div data-page="vehicule" class="page page-vehicule">
    <div class="navbar">
        <div class="navbar-inner">
            <div class="left">
                <a href="#" class="back link icon-only">
                    <i class="icon icon-back"></i>
                </a>
            </div>
            <div class="center sliding">Votre véhicule</div>
        </div>
    </div>
    <div class="page-content">
        <header style="background-image: linear-gradient(to bottom, rgba(0, 0, 0, 0) 0%, rgba(0, 0, 0, 0) 50%, rgba(0, 0, 0, 0.65) 100%), url(https://tpgdata.nicolapps.ch/vehicules/img/headers/<?=$vehicule->img?>.jpg);">
            <h1>
                <?=$vehicule->type?>
                <strong><?=$vehicule->name?></strong>
            </h1>
        </header>

        <section class="infos">
            <div class="row">
                <div class="col-50 info">
                    <label>Numéro du véhicule</label>
                    <p><?=$vehicule->id?></p>
                </div>
                <div class="col-50 info">
                    <label>Année de mise en service</label>
                    <p><?= ($vehicule->year) ? $vehicule->year : 'Inconnue' ?></p>
                </div>
            </div>

            <?php if ($vehicule->places_assises && $vehicule->places_debout && $vehicule->places_totales) { ?>
                <div class="row">
                    <div class="col-33 info">
                        <label>Places assises</label>
                        <p><?=$vehicule->places_assises?></p>
                    </div>
                    <div class="col-33 info">
                        <label>Places debout</label>
                        <p><?=$vehicule->places_debout?></p>
                    </div>
                    <div class="col-33 info">
                        <label>Places totales</label>
                        <p><?=$vehicule->places_totales?></p>
                    </div>
                </div>
            <?php } ?>

        </section>

        <section class="links">
            <?php if ($vehicule->tpg_link) { ?>
                <p>
                    <a class="external" href="<?=$vehicule->tpg_link?>" target="_blank">Site internet des tpg</a>
                </p>
            <?php } ?>

            <?php if ($vehicule->img_author) { ?>
                <p>
                    Crédit photo : <a class="external" href="<?=$vehicule->img_link?>"><?=$vehicule->img_author?></a>
                </p>
            <?php } ?>
        </section>
    </div>
</div>
