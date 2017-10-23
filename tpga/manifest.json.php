<?php
    header('Content-Type: application/json');

    $stopCode = urlencode($_GET['stopCode']);
?>
{
  "short_name": "<?= htmlspecialchars($_GET['stopName']) ?>",
  "name": "<?= htmlspecialchars($_GET['stopName']) ?>",
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
  "orientation": "portrait"
}
