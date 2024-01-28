<?php
session_start();
include('cadre.php');
// $mysqli = new mysqli("localhost", "root", "", "gestion");
include_once('config.php');


if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

echo '<div class="corp">';
if (isset($_GET['matiere'])) {
    $id = $_GET['matiere'];
    $result = $mysqli->query("SELECT prof.nom, prenom, nommat, classe.nom as nomcl, promotion, numsem FROM prof, enseignement, matiere, classe WHERE enseignement.numprof = prof.numprof AND classe.codecl = enseignement.codecl AND matiere.codemat = enseignement.codemat AND enseignement.numprof = '$id' ORDER BY promotion DESC");

    echo '<center><h1>Matieres enseignées par cet enseignant</h1></center>';
    echo '<table id="rounded-corner">';
    echo '<thead><tr><th scope="col" class="rounded-company">Nom</th>';
    echo '<th scope="col" class="rounded-q2">Prenom</th>';
    echo '<th scope="col" class="rounded-q2">Matière</th>';
    echo '<th scope="col" class="rounded-q2">Classe</th>';
    echo '<th scope="col" class="rounded-q2">Promotion</th>';
    echo '<th scope="col" class="rounded-q4">Semestre</th></tr></thead>';
    echo '<tfoot><tr><td colspan="5"class="rounded-foot-left"><em>&nbsp;</em></td>';
    echo '<td class="rounded-foot-right">&nbsp;</td></tr></tfoot><tbody>';

    while ($a = $result->fetch_assoc()) {
        echo '<tr><td>' . $a['nom'] . '</td><td>' . $a['prenom'] . '</td><td>' . $a['nommat'] . '</td><td>' . $a['nomcl'] . '</td><td>' . $a['promotion'] . '</td><td>' . $a['numsem'] . '</td></tr>';
    }

    echo '</tbody></table>';
}

else if (isset($_GET['classe'])) {
    $id = $_GET['classe'];
    $result = $mysqli->query("SELECT * FROM prof, classe WHERE numprofcoord = numprof AND numprof = '$id' ORDER BY promotion DESC");

    echo '<center><h1>Classes coordonnées par cet enseignant</h1></center>';
    echo '<table id="rounded-corner">';
    echo '<thead><tr><th scope="col" class="rounded-company">Nom</th>';
    echo '<th scope="col" class="rounded-q2">Prenom</th>';
    echo '<th scope="col" class="rounded-q2">Classes coordonnées</th>';
    echo '<th scope="col" class="rounded-q4">Promotion</th></tr></thead>';
    echo '<tfoot><tr><td colspan="3" class="rounded-foot-left">&nbsp;</td>';
    echo '<td class="rounded-foot-right">&nbsp;</td></tr></tfoot><tbody>';

    while ($a = $result->fetch_assoc()) {
        echo '<tr><td>' . $a['nom'] . '</td><td>' . $a['prenom'] . '</td><td>' . $a['nom'] . '</td><td>' . $a['promotion'] . '</td></tr>';
    }

    echo '</tbody></table>';
}

echo '</div>';
$mysqli->close();
?>
