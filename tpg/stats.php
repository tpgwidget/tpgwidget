<?php
require '../../tpgdata/db.php';
?><html>
<head>
    <title>TPGwidget - Statistiques</title>
    <meta charset="utf-8">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css">
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/js/bootstrap.min.js"></script>
</head>
<body>
    <div class="container">
        <div class="row">
            <div class="col-md-6">
                <h2>Arrêts les plus populaires</h2>
                <ul>
                    <?
                    $req = $bdd->query('SELECT name, stop, COUNT(*) AS nombre FROM widgets GROUP BY name, stop ORDER BY nombre DESC LIMIT 0,100');

                    while ($donnees = $req->fetch())
                    {
                        echo "<li><strong>".$donnees["nombre"]."</strong> : ";
                        if($donnees["name"] != ""){
                            echo $donnees["name"];
                        } else {
                            print '<em><strong>?</strong></em>';
                        }

                        print ' <span class="text-muted">('.$donnees["stop"].")</span>";

                        echo "</li>";
                    }

                    $req->closeCursor();
                    ?>
                </ul>
            </div>
            <div class="col-md-6">
                <h2>Derniers arrêts créés</h2>
                <p class="lead">Le plus récent tout en haut</p>
                <ul>
                    <?
                    $req = $bdd->query('SELECT * FROM widgets ORDER BY id DESC LIMIT 0,100');

                    while ($donnees = $req->fetch())
                    {
                        echo "<li>".$donnees["id"].": ";
                        if($donnees["name"] != ""){
                            echo $donnees["name"];
                        } else {
                            print '<em><strong>???</strong></em>';
                        }

                        print ' <span class="text-muted">('.$donnees["stop"].")</span>";

                        echo "</li>";
                    }

                    $req->closeCursor();
                    ?>
                </ul>

            </div>
        </div>
    </div>
</body>
</html>
