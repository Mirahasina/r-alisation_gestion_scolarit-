<?php
session_start();
include('cadre.php');
// $conn = mysqli_connect("localhost", "root", "", "gestion");
include_once('config.php');

?>

<html>
<body>
    <div class="corp">
        <pre>
            <!-- <img src="titre_img/ajout_eval.png" class="position_titre"> -->
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
                <form method="post" action="ajout_eval.php" class="formulaire">
                    Veuillez choisir la matière : <br/><br/>
                    <?php
                    $i = 6;
                    while ($a = mysqli_fetch_array($donnee)) {
                        echo '<input type="radio" name="radio" value="' . $a['nommat'] . '" id="choix' . $i . '" /> <label for="choix' . $i . '">' . $a['nommat'] . '</label><br /><br />';
                        $i++;
                    }
                    ?>
                    <input type="submit" value="Afficher les devoirs">
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
                    <table id="rounded-corner">
                        <thead>
                            <tr>
                                <th scope="col" class="rounded-company">Évaluation</th>
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
                                echo '<td><a href="ajout_eval.php?ajout_eval=' . $a['numdev'] . '">Ajouter évaluation</a></td><td>' . $a['nommat'] . '</td><td>' . $a['date_dev'] . '</td><td>' . $a['nom'] . '</td><td>' . $a['coeficient'] . '</td><td>S' . $a['numsem'] . '</td><td>' . $a['n_devoir'] . '</td></tr>';
                            }
                            ?>
                        </tbody>
                    </table>
                    <br/><br/><a href="ajout_eval.php">Revenir à la page principale !</a></center>
            <?php
            } elseif (isset($_POST['numel'])) {
                $numel = $_POST['numel'];
                $numdev = $_POST['numdev'];
                $nomcl = $_SESSION['classe'];
                $promo = $_SESSION['promo'];
                $note = str_replace(",", ".", $_POST['note']);
                $compte = mysqli_fetch_array(mysqli_query($conn, "SELECT count(*) as nb FROM evaluation WHERE numdev='$numdev' AND numel='$numel'"));

                if ($compte['nb'] > 0) {
                    ?>
                    <SCRIPT LANGUAGE="Javascript">
                        alert("Erreur d'insertion, l'enregistrement existe déjà !");
                    </SCRIPT>
                    <br/><br/><a href="ajout_eval.php">Revenir à la page principale </a>
                <?php
                } else {
                    mysqli_query($conn, "INSERT INTO evaluation(numdev, numel, note) VALUES('$numdev','$numel','$note')");
                    ?>
                    <SCRIPT LANGUAGE="Javascript">
                        alert("Ajout avec succès!");
                    </SCRIPT>
                    <br/><br/><a href="ajout_eval.php">Revenir à la page principale </a>
                <?php
                }
            } elseif (isset($_GET['ajout_eval'])) {
                $semestre = $_SESSION['semestre'];
                $nommat = $_SESSION['radio_matiere'];
                $nomcl = $_SESSION['classe'];
                $promo = $_SESSION['promo'];
                $numdev = $_GET['ajout_eval'];
                $donnee = mysqli_fetch_array(mysqli_query($conn, "SELECT date_dev, coeficient, n_devoir FROM devoir WHERE numdev='$numdev'"));
                $data = mysqli_query($conn, "SELECT numel, nomel, prenomel FROM eleve WHERE codecl=(SELECT codecl FROM classe WHERE nom='$nomcl' AND promotion='$promo')"); ?>
                <form method="POST" action="ajout_eval.php" class="formulaire">
                    Filière                 :          <?php echo $nomcl . ' - ' . $promo; ?><br/>
                    Matière                :           <?php echo $nommat; ?><br/>
                    Semestre               :           S<?php echo $semestre; ?><br/>
                    Date devoir           :           <?php echo $donnee['date_dev']; ?><br/>
                    Coefficient            :          <?php echo $donnee['coeficient']; ?><br/>
                    Devoir N�              :           <?php echo $donnee['n_devoir']; ?><br/>
                    Etudiant               :        <select name="numel">
                        <?php
                        while ($a = mysqli_fetch_array($data)) {
                            echo '<option value="' . $a['numel'] . '">' . $a['nomel'] . ' ' . $a['prenomel'] . '</option>';
                        } ?>
                    </select><br/>
                    Note                       :         <input type="text" name="note">
                    <input type="hidden" name="numdev" value="<?php echo $numdev; ?>">
                    <input type="image" src="button.png" style="margin-top:13px;">
                </form>
                <br/><br/><a href="ajout_eval.php">Revenir à la page principale !</a>
            <?php } else {
                $data = mysqli_query($conn, "SELECT DISTINCT promotion FROM classe ORDER BY promotion DESC"); ?>
                <h2>Veuillez choisir le Semestre, la promotion et la classe :</h2></br>
                <form method="post" action="ajout_eval.php" class="formulaire">
                    Promotion       :         <select name="promotion">
                        <?php
                        while ($a = mysqli_fetch_array($data)) {
                            echo '<option value="' . $a['promotion'] . '">' . $a['promotion'] . '</option>';
                        } ?></select><br/>
                    <?php
                    $data = mysqli_query($conn, "SELECT DISTINCT nom FROM classe"); ?>

                    Classe                :        <select name="nomcl">
                        <?php
                        while ($a = mysqli_fetch_array($data)) {
                            echo '<option value="' . $a['nom'] . '">' . $a['nom'] . '</option>';
                        } ?></select><br/>
                    Semestre           :        <select name="radiosem">
                        <?php for ($i = 1; $i <= 4; $i++) {
                            echo '<option value="' . $i . '">Semestre' . $i . '</option>';
                        } ?>
                    </select><br/><br/><br /><br />
                    <input type="submit" value="Afficher les matières">
                </form>
            <?php } ?>
        </pre></center>
    </div>
</body>
</html>
