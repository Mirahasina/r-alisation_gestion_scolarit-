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
    <!-- <img src="titre_img/affich_devoir.png" class="position_titre"> -->
    <center>
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
            <form method="post" action="afficher_devoir.php" class="formulaire">
                Les matières étudiées par la classe choisie
                <p>Matière :
<?php
    while ($a = mysqli_fetch_array($donnee)) {
        echo '<input type="radio" name="radio" value="' . $a['nommat'] . '" id="choix1" /><label for="choix1">' . $a['nommat'] . '</label><br /><br />';
    }
    ?>
                    <input type="submit" value="Afficher les devoirs">
                </p>
            </form>
<?php
} elseif (isset($_POST['radio'])) {
    $semestre = $_SESSION['semestre'];
    $nommat = $_POST['radio'];
    $nomcl = $_SESSION['classe'];
    $promo = $_SESSION['promo'];
    $donnee = mysqli_query($conn, "SELECT numdev, date_dev, nommat, nom, coeficient, numsem, n_devoir FROM devoir, matiere, classe WHERE matiere.codemat=devoir.codemat AND classe.codecl=devoir.codecl AND classe.nom='$nomcl' AND devoir.numsem='$semestre' AND matiere.nommat='$nommat' AND promotion='$promo'");
?>
            <center>
                <table id="rounded-corner">
                    <thead>
                        <tr>
<?php echo Edition(); ?>
                            <th class="<?php echo rond(); ?>">Matière</th>
                            <th class="rounded-q2">Date_devoir</th>
                            <th class="rounded-q2">Classe</th>
                            <th class="rounded-q2">Coefficient</th>
                            <th class="rounded-q2">Semestre</th>
                            <th class="rounded-q4">1er/2eme devoir</th>
                        </tr>
                    </thead>
                    <tfoot>
                        <tr>
                            <td colspan="<?php echo colspan(5, 7); ?>" class="rounded-foot-left"><em>&nbsp;</em></td>
                            <td class="rounded-foot-right">&nbsp;</td>
                        </tr>
                    </tfoot>
                    <tbody>
<?php
    while ($a = mysqli_fetch_array($donnee)) {
        if (isset($_SESSION['admin'])) {
            echo '<td><a href="modif_devoir.php?modif_dev=' . $a['numdev'] . '">modifier</a></td><td><a href="modif_devoir.php?supp_dev=' . $a['numdev'] . '" onclick="return(confirm(\'Êtes-vous sûr de vouloir supprimer cette entrée?\ntous les enregistrements en relation avec cette entrée seront perdus\'));">Supprimer</td>';
        }
        echo '<td>' . $a['nommat'] . '</td><td>' . $a['date_dev'] . '</td><td>' . $a['nom'] . '</td><td>' . $a['coeficient'] . '</td><td>S' . $a['numsem'] . '</td><td>' . $a['n_devoir'] . '</td></tr>';
    }
?>
                    </tbody>
                </table>
                <br/><br/><a href="afficher_devoir.php">Revenir à la page principale !</a></center>
<?php
} else {
    $retour = mysqli_query($conn, "SELECT DISTINCT nom FROM classe"); // afficher les classes
?>
            <form method="post" action="afficher_devoir.php" class="formulaire">
                Veuillez choisir le Semestre, la promotion et la classe :<br/><br/><br/>
                Promotion : <select name="promotion">
<?php
    while ($a = mysqli_fetch_array($data)) {
        echo '<option value="' . $a['promotion'] . '">' . $a['promotion'] . '</option>';
    }
?>
                </select><br/>
                Classe : <select name="nomcl">
<?php
    while ($a = mysqli_fetch_array($retour)) {
        echo '<option value="' . $a['nom'] . '">' . $a['nom'] . '</option>';
    }
?>
                </select><br/>
                Semestre : <select name="radiosem">
<?php
    for ($i = 1; $i <= 4; $i++) {
        echo '<option value="' . $i . '">Semestre' . $i . '</option>';
    }
?>
                </select><br/><br/>
                <input type="submit" value="Afficher les matières">
            </form>
<?php } ?>
        </pre>
    </center>
</div>
</body>
</html>
