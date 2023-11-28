<?php
    header('Content-Type: application/json');

    $stopCode = urlencode($_GET['stopCode']);
?>
{
  "short_name": <?= json_encode($_GET['stopName']) ?>,
  "name": <?= json_encode($_GET['stopName']) ?>,
  "icons": [
    {
      "src": "/icon/<?= $stopCode ?>/36.png",
      "sizes": "36x36",
      "type": "image/png",
      "density": 0.75
    },
    {
      "src": "/icon/<?= $stopCode ?>/48.png",
      "sizes": "48x48",
      "type": "image/png",
      "density": 1.0
    },
    {
      "src": "/icon/<?= $stopCode ?>/72.png",
      "sizes": "72x72",
      "type": "image/png",
      "density": 1.5
    },
    {
      "src": "/icon/<?= $stopCode ?>/96.png",
      "sizes": "96x96",
      "type": "image/png",
      "density": 2.0
    },
    {
      "src": "/icon/<?= $stopCode ?>/144.png",
      "sizes": "144x144",
      "type": "image/png",
      "density": 3.0
    },
    {
      "src": "/icon/<?= $stopCode ?>/192.png",
      "sizes": "192x192",
      "type": "image/png",
      "density": 4.0
    }
  ],
  "display": "standalone",
  "orientation": "portrait",
  "start_URL": <?= json_encode('https://' . $_SERVER['HTTP_HOST'] . '/'.$_GET['id'].'/', JSON_UNESCAPED_SLASHES) ?>,

  "related_applications": [
    {
      "platform": "play",
      "id": "ch.nicolapps.tpgwidget",
      "url": "https://play.google.com/store/apps/details?id=ch.nicolapps.tpgwidget"
    },
    {
      "platform": "itunes",
      "url": "https://apps.apple.com/ch/app/tpgwidget-raccourcis-tpg/id959278327"
    }
  ]
}
