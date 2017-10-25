<?php
header('Content-type: application/json; charset=utf-8');

require '../../tpgdata/apikey.php';
require '../../tpgdata/stops.php';
$stops = @simplexml_load_file('http://prod.ivtr-od.tpg.ch/v1/GetStops.xml?key='.$key);


echo '[';

  $firstStop = true;

  foreach($stops->stops->stop as $stop) {
      if(!$firstStop){
          echo ', ';
      }

      if($firstStop) {
        $firstStop = false;
      }
      echo '{ "stopName": "'.stopFilter($stop->stopName).'", "stopCode": "'.$stop->stopCode.'" }';
  }

echo ']';
