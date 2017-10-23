<?

require '../../tpgdata/stops.php';

require '../../tpgdata/db.php';

function showCffName($tpgName) {
    global $bdd;

    // On va chercher dans la base de données
    // un arrêt CFF correspondant à l'arrêt TPG demandé
    $req = $bdd->prepare('SELECT sbb FROM `tpg-sbb` WHERE tpg = ?');
    $req->execute([$tpgName]);

    if($req->rowCount() === 0){ // Aucun résultat ?
        return $tpgName; // On retourne le nom TPG
    }

    $row = $req->fetch();
    $req->closeCursor();

    return $row['sbb'];
}

function showTpgName($cffName) {
    global $bdd;

    // On va chercher dans la base de données
    // un arrêt TPG correspondant à l'arrêt CFF
    $req = $bdd->prepare('SELECT tpg FROM `tpg-sbb` WHERE sbb = ?');
    $req->execute([$cffName]);

    if($req->rowCount() === 0){ // Aucun résultat ?
        return $cffName; // On retourne le nom CFF
    }

    $row = $req->fetch();
    $req->closeCursor();

    return stopFilter($row['tpg']);
}
