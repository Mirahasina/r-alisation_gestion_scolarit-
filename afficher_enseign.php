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
    <!-- <img src="titre_img/affich_enseign.png" class="position_titre"> -->
    <center>
        <pre>
<?php
if (isset($_POST['nomcl']) && isset($_POST['radiosem'])) {
    $nomcl = $_POST['nomcl'];
    $semestre = $_POST['radiosem'];
    $promo = $_POST['promotion'];
    $donnee = mysqli_query($conn, "SELECT enseignement.id, classe.nom as nomcl, nommat, prof.nom, numsem, promotion FROM enseignement, classe, matiere, prof WHERE matiere.codemat=enseignement.codemat AND enseignement.codecl=classe.codecl AND prof.numprof=enseignement.numprof AND classe.nom='$nomcl' AND promotion='$promo' AND enseignement.numsem='$semestre'");
    ?>
            <center>
                <table id="rounded-corner">
                    <thead>
                        <tr>
<?php echo Edition(); ?>
                            <th class="<?php echo rond(); ?>">Classe</th>
                            <th class="rounded-q1">Promotion</th>
                            <th class="rounded-q1">Matière</th>
                            <th class="rounded-q1">Professeur</th>
                            <th class="rounded-q4">Semestre</th>
                        </tr>
                    </thead>
                    <tfoot>
                        <tr>
                            <td colspan="<?php echo colspan(4, 6); ?>" class="rounded-foot-left"><em>&nbsp;</em></td>
                            <td class="rounded-foot-right">&nbsp;</td>
                        </tr>
                    </tfoot>
                    <tbody>
<?php
    while ($a = mysqli_fetch_array($donnee)) {
        if (isset($_SESSION['admin'])) {
            echo '<td><a href="modif_enseign.php?modif_ensein=' . $a['id'] . '">modifier</a></td><td><a href="modif_enseign.php?supp_ensein=' . $a['id'] . '" onclick="return(confirm(\'Êtes-vous sûr de vouloir supprimer cette entrée?\ntous les enregistrements en relation avec cette entrée seront perdus\'));">Supprimer</td>';
        }
        echo '<td>' . $a['nomcl'] . '</td><td>' . $a['promotion'] . '</td><td>' . $a['nommat'] . '</td><td>' . $a['nom'] . '</td><td>S' . $a['numsem'] . '</td></tr>';
    }
?>
                    </tbody>
                </table>
                <br/><br/><a href="afficher_enseign.php">Revenir à la page précédente !</a>
            </center>
<?php
} else {
    $retour = mysqli_query($conn, "SELECT DISTINCT nom FROM classe");
?>
            <form method="post" action="afficher_enseign.php" class="formulaire">
                <FIELDSET>
                    <LEGEND align=top>Critères d'affichage</LEGEND>
                    Classe : <select name="nomcl">
<?php
    while ($a = mysqli_fetch_array($retour)) {
        echo '<option value="' . $a['nom'] . '">' . $a['nom'] . '</option>';
    }
?>
                </select><br/><br/>
                Promotion : <select name="promotion">
<?php
    while ($a = mysqli_fetch_array($data)) {
        echo '<option value="' . $a['promotion'] . '">' . $a['promotion'] . '</option>';
    }
?>
                </select><br/><br/>
                Semestre : <select name="radiosem">
<?php
    for ($i = 1; $i <= 4; $i++) {
        echo '<option value="' . $i . '">Semestre' . $i . '</option>';
    }
?>
                </select><br/><br/>
                <input type="submit" value="afficher">
                </FIELDSET>
            </form>
<?php } ?>
        </pre>
    </center>
</div>
</body>
</html>
