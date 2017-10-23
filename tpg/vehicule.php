<?php

require '../tpgdata/vehicules/vehicules.php';

$vehicule = new Vehicule(htmlspecialchars($_GET['id']));
$vehicule->renderPage_iOS();