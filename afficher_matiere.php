<?php
session_start();
include('cadre.php');
// $conn = mysqli_connect("localhost", "root", "", "gestion");
include_once('config.php');

if (!$conn) {
    die("Échec de la connexion : " . mysqli_connect_error());
}

?>
<html>
<body>
<div class="corp">
    <!-- <img src="titre_img/affich_matiere.png" class="position_titre"> -->
    <pre>
<?php
if (isset($_GET['nomcl'])) {
    $_SESSION['nomcl'] = $_GET['nomcl'];
    $nomcl = $_GET['nomcl'];
    $data = mysqli_query($conn, "SELECT promotion FROM classe WHERE nom='$nomcl' ORDER BY promotion DESC");
?>
    <form method="post" action="afficher_matiere.php" class="formulaire">
        Veuillez choisir la promotion et le semestre pour <?php echo $nomcl; ?><br /><br />
        <fieldset>
            <legend align="top">Critères d'affichage</legend>
            <pre>
Promotion : <select name="promotion"> 
<?php
    while ($a = mysqli_fetch_array($data)) {
        echo '<option value="' . $a['promotion'] . '">' . $a['promotion'] . '</option>';
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
    <br/><br/><a href="index.php">Revenir à la page principale</a>
<?php
}

if (isset($_POST['radiosem'])) {
    $nomcl = $_SESSION['nomcl'];
    $semestre = $_POST['radiosem'];
    $promo = $_POST['promotion'];
    $donnee = mysqli_query($conn, "SELECT matiere.codemat, nommat, classe.nom, numsem, prof.nom AS nomprof FROM matiere, enseignement, classe, prof WHERE matiere.codemat=enseignement.codemat AND prof.numprof=enseignement.numprof AND enseignement.codecl=classe.codecl AND classe.nom='$nomcl' AND enseignement.numsem='$semestre' AND promotion='$promo'");
?>
    <center>
        <table id="rounded-corner">
            <thead>
                <tr>
                    <?php echo Edition(); ?>
                    <th class="<?php echo rond(); ?>">Matière</th>
                    <th class="rounded-q2">Classe</th>
                    <th class="rounded-q2">Nom prof</th>
                    <th class="rounded-q4">Semestre</th>
                </tr>
            </thead>
            <tfoot>
                <tr>
                    <td colspan="<?php echo colspan(3, 5); ?>" class="rounded-foot-left"><em>&nbsp;</em></td>
                    <td class="rounded-foot-right">&nbsp;</td>
                </tr>
            </tfoot>
            <tbody>
            <?php
            while ($a = mysqli_fetch_array($donnee)) {
                if (isset($_SESSION['admin'])) {
                    echo '<tr><td><a href="modif_matiere.php?modif_matiere=' . $a['codemat'] . '">modifier</a></td><td><a href="modif_matiere.php?supp_matiere=' . $a['codemat'] . '" onclick="return(confirm(\'Etes-vous sûr de vouloir supprimer cette entrée?\'));">supprimer</a></td>';
                }
                echo '<td>' . $a['nommat'] . '</td><td >' . $a['nom'] . '</strong></td><td>' . $a['nomprof'] . '</td><td>S' . $a['numsem'] . '</td></tr>';
            }
            ?>
            </tbody>
        </table>
    </center>
    <?php
    echo '<br/><br/><a href="afficher_matiere.php?nomcl=' . $nomcl . '">Revenir à la page principale</a>';
}
?>
    </div>
</pre>

</body>
</html>
