<?php
session_start();
include('cadre.php');

if (isset($_SESSION['admin']) || isset($_SESSION['etudiant']) || isset($_SESSION['prof'])) {
    echo '<html> <body> <div class="corp">';
    // echo '<img src="titre_img/cherche_prof.png" class="position_titre"><pre>';

    if (isset($_GET['cherche_prof'])) {
        $retour = mysqli_query($conn,"select distinct nom from classe");
        $data = mysqli_query($conn,"select distinct promotion from classe order by promotion desc");
?>

        <form action="chercher_prof.php" method="post" class="formulaire">
            Nom du prof: <input type="text" name="nomel"><br/>
            Prenom du prof: <input type="text" name="prenomel"><br/>
            Vous pouvez préciser la promotion si vous voulez : <br/><br/><select name="promotion">
                <option value="">Choisir la promotion</option>
                <?php while ($a = mysql_fetch_array($data)) {
                    echo '<option value="' . $a['promotion'] . '">' . $a['promotion'] . '</option>';
                } ?>
            </select><br/>
            Vous pouvez préciser la classe si vous voulez : <br/><br/><select name="nomcl">
                <option value="">Choisir la classe</option>
                <?php while ($a = mysql_fetch_array($retour)) {
                    echo '<option value="' . $a['nom'] . '">' . $a['nom'] . '</option>';
                } ?></select><br/><br/>
            <input type="image" src="chercher.png">
        </form>
        <br/><br/><a href="index.php">Revenir à la page principale!</a>

<?php
    } elseif (isset($_POST['nomel'])) {
        $nomprof = $_POST['nomel'];
        $prenomprof = $_POST['prenomel'];
        $nomcl = $_POST['nomcl'];
        $promo = $_POST['promotion'];
        $option = "";

        if ($nomcl != "" && $promo == "") {
            $option = "and classe.nom='$nomcl'";
        } elseif ($promo != "" && $nomcl == "") {
            $option = "and classe.promotion='$promo'";
        } elseif ($promo != "" && $nomcl != "") {
            $option = "and classe.nom='$nomcl' and promotion='$promo'";
        }

        $cherche = mysql_query("select classe.codecl,prof.numprof,prof.nom as nomp,nommat,prof.prenom as prenomp,adresse,telephone,classe.nom,promotion from prof,classe,enseignement,matiere where matiere.codemat=enseignement.codemat and classe.codecl=enseignement.codecl and prof.numprof=enseignement.numprof and prof.nom LIKE '%$nomprof%' and prof.prenom LIKE '%$prenomprof%' " . $option);

?>

        <center><table id="rounded-corner">
                <thead>
                    <tr>
                        <th class="rounded-company">Nom du prof</th>
                        <th class="rounded-q1">Prenom du prof</th>
                        <th class="rounded-q3">Adresse</th>
                        <th class="rounded-q3">Telepohne</th>
                        <th class="rounded-q3">Classe enseignée</th>
                        <th class="rounded-q3">Matière enseignée</th>
                        <th class="rounded-q4">Promotion</th>
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
                    while ($a = mysql_fetch_array($cherche)) {
                        echo '<tr><td>' . $a['nomp'] . '</td><td>' . $a['prenomp'] . '</td><td >' . $a['adresse'] . '</td><td >' . $a['telephone'] . '</td><td>' . $a['nom'] . '</td><td>' . $a['nommat'] . '</td><td>' . $a['promotion'] . '</td></tr>';
                    }
                    ?>
                </tbody>
            </table></center>
        <br/><br/><a href="chercher_prof.php?cherche_prof=true">Revenir à la page precedente !</a>

<?php
    }
}
?>
</pre></div>
</body>
</html>
