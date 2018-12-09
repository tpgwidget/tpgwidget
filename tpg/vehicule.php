<?php
require '../tpgdata/vehicules/vehicules.php';
$vehicule = new Vehicule(htmlspecialchars($_GET['id'] ?? ''));
?>
<div class="navbar">
    <div class="navbar-inner">
        <div class="left">
            <a href="#" class="back link">
                <i class="icon icon-back"></i>
                <span>Retour</span>
            </a>
        </div>
        <div class="center sliding">Votre véhicule</div>
        <div class="right">
          <a href="#" class="open-panel link icon-only"><i class="icon icon-panel"></i></a>
        </div>
    </div>
</div>

<div class="pages">
    <div data-page="vehicule" class="page page-vehicule">
        <div class="page-content">
            <header style="background-image: linear-gradient(to bottom, rgba(0, 0, 0, 0) 0%, rgba(0, 0, 0, 0) 50%, rgba(0, 0, 0, 0.65) 100%), url(https://tpgdata.nicolapps.ch/vehicules/img/headers/<?= $vehicule->img ?>.jpg);">
                <h1>
                    <?= $vehicule->type ?>
                    <strong><?= $vehicule->name ?></strong>
                </h1>
            </header>

            <section class="infos">
                <div class="row">
                    <div class="col-50 info">
                        <label>Numéro du véhicule</label>
                        <p><?= $vehicule->id ?></p>
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
                            <p><?= $vehicule->places_assises ?></p>
                        </div>
                        <div class="col-33 info">
                            <label>Places debout</label>
                            <p><?= $vehicule->places_debout ?></p>
                        </div>
                        <div class="col-33 info">
                            <label>Places totales</label>
                            <p><?= $vehicule->places_totales ?></p>
                        </div>
                    </div>
                <?php } ?>

            </section>

            <section class="links">
                <?php if ($vehicule->tpg_link) { ?>
                    <p>
                        <a class="external" href="<?= $vehicule->tpg_link ?>" target="_blank">Site internet des tpg</a>
                    </p>
                <?php } ?>

                <?php if ($vehicule->img_author) { ?>
                    <p>
                        Crédit photo : <a class="external" href="<?= $vehicule->img_link ?>"><?= $vehicule->img_author ?></a>
                    </p>
                <?php } ?>
            </section>

            <?php if ($vehicule->wifi) { ?>
                <a class="card card-wifi external" href="https://www.tpg.ch/freewifi" target="_blank">
                    <img src="/resources/img/wifi.svg" alt="Icône Wi-Fi">
                    <p>Ce véhicule est équipé d’une connection Wi-Fi gratuite ! Pour en profiter, connectez-vous au réseau <strong>tpg-freeWiFi</strong>. Pour plus d’informations, consultez le <span>site web des tpg</span>.</p>
                </a>
            <?php } ?>
        </div>
    </div>
</div>
