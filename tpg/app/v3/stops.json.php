<?php
require_once __DIR__.'/../../../config.inc.php';
use TPGwidget\Data\{Lines, Stops};

header('Content-type: application/json; charset=utf-8');
echo json_encode([
    'error' => "Pendant près de 10 ans, TPGwidget a aidé les Genevois·es à se déplacer. Aujourd’hui, les TPG ont supprimé la source d’informations dont TPGwidget dépendait, ce qui m’oblige à contrecœur à mettre mon application hors service.

J’ai créé TPGwidget lorsque j’avais 13 ans pour réaliser mon rêve de gosse de créer une app. J’ai appris énormément grâce à ce projet et je tiens à vous remercier du fond du cœur pour votre soutien.

— Nicolas Ettlin, créateur de TPGwidget"
]);
