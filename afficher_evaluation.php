<?php
session_start();
include('cadre.php');
// $conn = mysqli_connect("localhost", "root", "", "gestion");
include_once('config.php');

if (!$conn) {
    die("Échec de la connexion : " . mysqli_connect_error());
}

$data = mysqli_query($conn, "SELECT DISTINCT promotion FROM classe ORDER BY promotion DESC");
?>
<div class="corp">
    <!-- <img src="titre_img/affich_note.png" class="position_titre"> -->
    <pre>
<?php
if (isset($_POST['nomcl']) && isset($_POST['radiosem'])) {
    $_SESSION['semestre'] = $_POST['radiosem'];
    $nomcl = $_POST['nomcl'];
    $semestre = $_SESSION['semestre'];
    $promo = $_POST['promotion'];
    $_SESSION['promo'] = $_POST['promotion'];
    $donnee = mysqli_query($conn, "SELECT nommat FROM matiere, enseignement, classe WHERE matiere.codemat=enseignement.codemat AND enseignement.codecl=classe.codecl AND classe.nom='$nomcl' AND promotion='$promo' AND enseignement.numsem='$semestre'");
    $_SESSION['classe'] = $nomcl;
?>
<form method="post" action="afficher_evaluation.php" class="formulaire">
   <fieldset>
       <legend align="top">Les matières correspondantes</legend>
<?php
    $i = 6;
    while ($a = mysqli_fetch_array($donnee)) {
        echo '<input type="radio" name="radio" value="' . $a['nommat'] . '" id="choix' . $i . '" /> <label for="choix' . $i . '">' . $a['nommat'] . '</label><br /><br />';
        $i++;
    }
?>
        <input type="submit" value="Afficher les devoirs">
   </fieldset>
</form>
<?php
} elseif (isset($_POST['radio'])) {
    $semestre = $_SESSION['semestre'];
    $nommat = $_POST['radio'];
    $_SESSION['radio_matiere'] = $nommat;
    $nomcl = $_SESSION['classe'];
    $promo = $_SESSION['promo'];
    $donnee = mysqli_query($conn, "SELECT numdev, date_dev, nommat, nom, coeficient, numsem, n_devoir FROM devoir, matiere, classe WHERE matiere.codemat=devoir.codemat AND classe.codecl=devoir.codecl AND classe.nom='$nomcl' AND devoir.numsem='$semestre' AND matiere.nommat='$nommat' AND promotion='$promo'");
?>
<center>
    <h2>Veuillez choisir le devoir pour lequel vous voulez voir l'évaluation</h2><br/><br/>
    <table id="rounded-corner">
        <thead>
            <tr>
                <th scope="col" class="rounded-company">Evaluation</th>
                <th scope="col" class="rounded-q1">Matière</th>
                <th scope="col" class="rounded-q2">Date_devoir</th>
                <th scope="col" class="rounded-q3">Classe</th>
                <th scope="col" class="rounded-q3">Coefficient</th>
                <th scope="col" class="rounded-q3">Semestre</th>
                <th scope="col" class="rounded-q4">1er/2eme devoir</th>
            </tr>
        </thead>
        <tfoot>
            <tr>
                <td colspan="6" class="rounded-foot-left"><em>&nbsp;</em></td>
                <td class="rounded-foot-right">&nbsp;</td>
            </tr>
        </tfoot>
        <tbody>
<?php
    while ($a = mysqli_fetch_array($donnee)) {
        echo '<td><a href="afficher_evaluation.php?affich_eval=' . $a['numdev'] . '">Voir l\'évaluation</a></td><td>' . $a['nommat'] . '</td><td>' . $a['date_dev'] . '</td><td>' . $a['nom'] . '</td><td>' . $a['coeficient'] . '</td><td>S' . $a['numsem'] . '</td><td>' . $a['n_devoir'] . '</td></tr>';
    }
?>
        </tbody>
    </table>
    <br/><br/><a href="afficher_evaluation.php">Revenir à la page principale !</a>
</center>
<?php
} elseif (isset($_GET['affich_eval'])) {
    $numdev = $_GET['affich_eval'];
    $donnee = mysqli_query($conn, "SELECT numeval, date_dev, nommat, nom, nomel, prenomel, note, coeficient, numsem, promotion, n_devoir FROM devoir, matiere, classe, eleve, evaluation WHERE evaluation.numdev=devoir.numdev AND eleve.numel=evaluation.numel AND matiere.codemat=devoir.codemat AND classe.codecl=devoir.codecl AND devoir.numdev='$numdev'");
?>
<center>
    <table id="rounded-corner">
        <thead>
            <?php echo Edition2();?>
            <th class="<?php echo rond2(); ?>">Nom</th>
            <th>Prenom</th>
            <th>classe</th>
            <th>Promotion</th>
            <th>Matiere</th>
            <th>Date devoir</th>
            <th>Coefficient</th>
            <th>Semestre</th>
            <th>N° de devoir</th>
            <th class="rounded-q4">Note</th>
        </thead>
        <tfoot>
            <tr>
                <td colspan="<?php echo colspan2(9, 11); ?>" class="rounded-foot-left"><em>&nbsp;</em></td>
                <td class="rounded-foot-right">&nbsp;</td>
            </tr>
        </tfoot>
        <tbody>
<?php
    while ($a = mysqli_fetch_array($donnee)) {
        ?>
        <tr>
            <?php
            if (isset($_SESSION['admin']) || isset($_SESSION['prof'])) {
                echo '<td><a href="modif_eval.php?modif_eval=' . $a['numeval'] . '">modifier</a></td><td><a href="modif_eval.php?supp_eval=' . $a['numeval'] . '" onclick="return(confirm(\'Etes-vous sûr de vouloir supprimer cette entrée?\'));">supprimer</a></td>';
            }
            echo '<td>' . $a['nomel'] . '</td><td>' . $a['prenomel'] . '</td><td>' . $a['nom'] . '</td><td>' . $a['promotion'] . '</td><td>' . $a['nommat'] . '</td><td>' . $a['date_dev'] . '</td><td>' . $a['coeficient'] . '</td><td>S' . $a['numsem'] . '</td><td>' . $a['n_devoir'] . '</td><td>' . $a['note'] . '</td></tr>';
    }
    ?>
        </tbody>
    </table>
    <br/><br/><a href="afficher_evaluation.php">Revenir à la page principale !</a>
</center>
<?php
} else {
    $retour = mysqli_query($conn, "SELECT DISTINCT nom FROM classe");
?>
    <form method="post" action="afficher_evaluation.php" class="formulaire">
        <fieldset>
            <legend align="top">Critères d'affichage</legend>
            <pre>
Promotion : <select name="promotion">
<?php
    while ($a = mysqli_fetch_array($data)) {
        echo '<option value="' . $a['promotion'] . '">' . $a['promotion'] . '</option>';
    }
    ?></select><br/><br/>
Classe : <select name="nomcl">
<?php
    while ($a = mysqli_fetch_array($retour)) {
        echo '<option value="' . $a['nom'] . '">' . $a['nom'] . '</option>';
    }
    ?></select><br/><br/>
Semestre : <select name="radiosem">
<?php
    for ($i = 1; $i <= 4; $i++) {
        echo '<option value="' . $i . '">Semestre' . $i . '</option>';
    }
    ?></select><br/><br/>
<input type="submit" value="Afficher les matières">
            </pre>
        </fieldset>
    </form>
<?php } ?>
    </pre>
</div>
</body>
</html>
