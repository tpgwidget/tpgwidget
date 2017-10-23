<?
header('Content-type: application/json; charset=utf-8');

require '../../tpgdata/db.php';

$i = 0;

echo '[';

    $req = $bdd->query('SELECT tpg FROM `tpg-sbb` ORDER BY tpg');

    while($stop = $req->fetch()){
        if($i !== 0){
            echo ',';
        }

        echo '"'.$stop['tpg'].'"';

        $i++;
    }

echo ']';

?>
