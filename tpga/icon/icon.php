<?php

/* Générateur d'icônes TPGwidget */

//===========================
// Paramètres
//===========================

// Image d'origine
$source = 'resources/192.png'; // emplacement du fichier source
$sourceSize = 192; // dimensions du fichier source (192x192 pixels)

$imgSize = $_GET['size'];

// Texte
$font = 'resources/RobotoCondensed-Bold.ttf'; // police de caractères
$fontSize = 42; // taille du texte
$angle = 0;
$text = $_GET['text']; // texte à afficher

//===========================
// Code
//===========================

// On créée l'image à partir du modèle
$img = imagecreatefrompng($source);

// On active le fond transparent de l'image
imagealphablending($img, true);
imagesavealpha($img, true);

// On créée la couleur blanc (pour le texte)
$blanc = imagecolorallocate($img, 255, 255, 255);

// On calcule la position du texte
$textBox = imagettfbbox($fontSize, $angle, $font, $text);

$textWidth = $textBox[2]-$textBox[0];
$textHeight = $textBox[7]-$textBox[1];

$x = ($sourceSize / 2) - ($textWidth / 2);
$y = ($sourceSize / 2) - ($textHeight / 2);
// source : http://stackoverflow.com/a/14517450/4652564

// On ajoute le texte
imagettftext($img, $fontSize, $angle, $x, $y, $blanc, $font, $text);

// On crée une image redimensionnée
$resizedImage = imagecreatetruecolor($imgSize, $imgSize);

// On lui met un fond transparent
imagealphablending($resizedImage, true);
imagesavealpha($resizedImage, true);
imagefill($resizedImage, 0, 0, 0x7fff0000);

// On copie l'ancienne image redimensionnée
imagecopyresized($resizedImage, $img, 0, 0, 0, 0, $imgSize, $imgSize, $sourceSize, $sourceSize);
imagedestroy($img);

// On envoie l'image
header ('Content-type: image/png');
imagepng($resizedImage);
imagedestroy($resizedImage);
