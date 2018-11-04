<?php
require_once __DIR__.'/../../config.inc.php';
use TPGwidget\Data\Datasets;
header('Content-type: application/json; charset=utf-8');

$stops = array_values(Datasets::load('stopTranslation'));
sort($stops);
echo json_encode($stops);
