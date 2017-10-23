<?php
/*
 * Database connection
 * For the database structure, see dump.sql
 */

try {
    $bdd = new PDO('mysql:host=127.0.0.1;dbname=tpgwidget;charset=utf8', 'tpgw', '');
} catch (Exception $e) {
    die('<h1>Erreur :</h1> ' . $e->getMessage());
}
