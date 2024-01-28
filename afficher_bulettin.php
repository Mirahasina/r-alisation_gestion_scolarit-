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
    <!-- <img src="titre_img/affich_bulletin.png" class="position_titre"> -->
    <pre>
        <?php
        if (isset($_POST['nomcl']) && isset($_POST['radiosem'])) {
            $nomcl = $_POST['nomcl'];
            $promo = $_POST['promotion'];
            $semestre = $_POST['radiosem'];
            $matiere = mysqli_query($conn, "SELECT matiere.codemat, nommat FROM enseignement, matiere WHERE enseignement.codemat = matiere.codemat AND enseignement.codecl = (SELECT codecl FROM classe WHERE nom = '$nomcl' AND promotion = '$promo') AND numsem = '$semestre'");
            echo '<form method="post" action="afficher_bulettin.php" class="formulaire">';
            echo 'Veuillez choisir la matiere pour  : ' . $nomcl . ' ' . $promo . '<br/><br/><br/>';
            ?>
            <FIELDSET>
                <LEGEND align=top> Matières étudiées </LEGEND>
                Matière : <select name="codemat">
                    <?php while ($c = mysqli_fetch_array($matiere)) {
                        echo '<option value="' . $c['codemat'] . '">' . $c['nommat'] . '</option>';
                    } ?></select>
                <input type="hidden" name="nomclasse" value="<?php echo $nomcl; ?>">
                <input type="hidden" name="promo" value="<?php echo $promo; ?>">
                <input type="hidden" name="semestre" value="<?php echo $semestre; ?>">
                <input type="submit" value="Afficher les notes finals">
            </FIELDSET>
            <br/><br/><a href="afficher_bulettin.php">Revenir à la page precedente !</a>
            </form>

        <?php
        } elseif (isset($_POST['codemat'])) {
            $nomcl = $_POST['nomclasse'];
            $semestre = $_POST['semestre'];
            $promo = $_POST['promo'];
            $codemat = $_POST['codemat'];
            $dev1 = mysqli_query($conn, "SELECT nomel, prenomel, nom, promotion, nommat, numsem, notefinal FROM eleve, classe, matiere, bulletin WHERE eleve.numel = bulletin.numel AND classe.codecl = eleve.codecl AND matiere.codemat = bulletin.codemat AND matiere.codemat = '$codemat' AND numsem = '$semestre' AND eleve.numel IN (SELECT numel FROM eleve WHERE codecl = (SELECT codecl FROM classe WHERE nom = '$nomcl' AND promotion = '$promo'))");
        ?>
            <center>
                <table id="rounded-corner">
                    <thead>
                        <tr>
                            <th class="rounded-company">Nom</th>
                            <th class="rounded-q1">Prenom</th>
                            <th class="rounded-q3">Classe</th>
                            <th class="rounded-q3">Promotion</th>
                            <th class="rounded-q3">Matiere</th>
                            <th class="rounded-q3">Semestre</th>
                            <th class="rounded-q4">Note Final</th>
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
                        while ($a = mysqli_fetch_array($dev1)) {
                            echo '<tr>
                                    <td>' . $a['nomel'] . '</td>
                                    <td>' . $a['prenomel'] . '</td>
                                    <td>' . $a['nom'] . '</td>
                                    <td>' . $a['promotion'] . '</td>
                                    <td>' . $a['nommat'] . '</td>
                                    <td>' . $a['numsem'] . '</td>
                                    <td>' . $a['notefinal'] . '</td>
                                </tr>';
                        }
                        ?>
                    </tbody>
                </table>
            </center>
            <br/><br/><a href="afficher_bulettin.php">Revenir à la page precedente !</a>
        <?php
        } else {
            $retour = mysqli_query($conn, "SELECT DISTINCT nom FROM classe");
        ?>
            <form method="post" action="afficher_bulettin.php" class="formulaire">
                Veuillez choisir le Semestre, la promotion et la classe :<br/><br/><br/>
                <FIELDSET>
                    <LEGEND align=top>Critères d'affichage<LEGEND>
                        <pre>Promotion      :       <select name="promotion">
                                <?php while ($a = mysqli_fetch_array($data)) {
                                    echo '<option value="' . $a['promotion'] . '">' . $a['promotion'] . '</option>';
                                } ?></select><br/><br/>
                                Classe              :       <select name="nomcl">
                                    <?php while ($a = mysqli_fetch_array($retour)) {
                                        echo '<option value="' . $a['nom'] . '">' . $a['nom'] . '</option>';
                                    } ?></select><br/><br/>
                                Semestre        :        <select name="radiosem">
                                    <?php for ($i = 1; $i <= 4; $i++) {
                                        echo '<option value="' . $i . '">Semestre' . $i . '</option>';
                                    } ?>
                                </select><br/><br/>
                                <input type="submit" value="Afficher les matieres">
                            </pre>
                </FIELDSET>
            </form>
        <?php
        }
        ?>
    </pre>
</div>
</body>
</html>
