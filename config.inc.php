<?php
/**
 * TPGwidget config file (must be included by all PHP files)
 */

require __DIR__.'/vendor/autoload.php';

// Load env file
$dotenv = \Dotenv\Dotenv::createUnsafeImmutable(__DIR__);
$dotenv->load();

// Connect to database
try {
    $bdd = new \PDO('mysql:host='.getenv('DB_HOST').':'.getenv('DB_PORT').';dbname='.getenv('DB_NAME').';charset=utf8', getenv('DB_USERNAME'), getenv('DB_PASSWORD'));
} catch (Exception $e) {
    error_log('['.date('Y-m-d H:i:s').'] TPGwidget SQL Error : '.$e->getMessage());
    die('Erreur : impossible de se connecter à la base de données');
}
