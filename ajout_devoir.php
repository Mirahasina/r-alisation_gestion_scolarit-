<?php
session_start();
include('cadre.php');
include('calendrier.html');
// $conn = mysqli_connect("localhost", "root", "", "gestion");
include_once('config.php');

?>

<html>
<body>
<div class="corp">
<center>
    <pre>
        <!-- <img src="titre_img/ajout_devoir.png" class="position_titre"> -->
        <form action="ajout_devoir.php" method="POST" class="formulaire">
            <?php
            if (isset($_POST['nomcl'])) {
                $_SESSION['nomcl'] = $_POST['nomcl'];
                $nomcl = $_POST['nomcl'];
                $promo = $_POST['promotion'];
                $_SESSION['promo'] = $promo;
                $donnee = mysqli_query($conn, "SELECT codemat, nommat FROM matiere, classe WHERE matiere.codecl = classe.codecl AND nom = '$nomcl' AND promotion = '$promo'");
                ?>
                <FIELDSET>
                    <LEGEND align=top>Ajouter un devoir<LEGEND><pre>
                        Matière                   :          <select name="choix_mat" id="choix">
                            <?php
                            while ($a = mysqli_fetch_array($donnee)) {
                                echo '<option value="' . $a['codemat'] . '">' . $a['nommat'] . '</option>';
                            }
                            ?>
                        </select><br/><br/>
                        Date du devoir        :              <input type="text" name="date" class="calendrier"><br/></br/>
                        Coefficient              :       <select name="coefficient"><?php for ($i = 1; $i <= 15; $i++) {
                                echo '<option value="' . $i . '">' . $i . '</option>';
                            } ?>
                        </select><br/><br/>
                        Semestre                  :      <select name="semestre"><?php for ($i = 1; $i <= 4; $i++) {
                                echo '<option value="' . $i . '">Semestre' . $i . '</option>';
                            } ?>
                        </select><br/>
                        1er / 2ème Devoir    :       <input type="radio" name="devoir" value="1" id="choix1" /> <label for="choix1">1er devoir</label>
                                                  <input type="radio" name="devoir" value="2" id="choix2" /> <label for="choix2">2eme devoir</label><br/>
                        <center><input type="image" src="button.png"></center>
                    </pre></FIELDSET>
                </form>
            <?php } else if (isset($_POST['date'])) {
                $date = addslashes(nl2br(htmlspecialchars($_POST['date'])));
                $coefficient = $_POST['coefficient'];
                $semestre = $_POST['semestre'];
                $codemat = $_POST['choix_mat'];
                $nomcl = $_SESSION['nomcl'];
                $n_devoir = $_POST['devoir'];//Premier ou 2eme devoir -- 1 ou 2
                $promo = $_SESSION['promo'];

                $data = mysqli_query($conn, "SELECT COUNT(*) AS nb FROM devoir WHERE codecl = (SELECT codecl FROM classe WHERE nom='$nomcl' AND promotion='$promo') AND codemat='$codemat' AND numsem='$semestre' AND n_devoir='$n_devoir'");
                $valider = mysqli_query($conn, "SELECT COUNT(*) AS nb FROM enseignement WHERE codecl = (SELECT codecl FROM classe WHERE nom='$nomcl' AND promotion='$promo') AND codemat='$codemat' AND numsem='$semestre'");

                $nb = mysqli_fetch_array($data);
                $nb2 = mysqli_fetch_array($valider);
                $bool = true;

                if ($nb2['nb'] != 0) {
                    $bool = false;
                    echo '<br/><h2>Erreur d\'insertion!! Cet enseignement n\'existe pas</h2>';
                }

                if ($nb['nb'] > 0) {
                    $bool = false;
                    echo '<br/><h2>Erreur d\'insertion!! Numéro de devoir incorrect (impossible d\'ajouter deux devoirs similaires)</h2>';
                }

                if ($bool == true) {
                    $codeclasse = mysqli_query($conn, "SELECT codecl FROM classe WHERE nom='$nomcl' AND promotion='$promo'");
                    $code = mysqli_fetch_array($codeclasse);
                    $codecl = $code['codecl'];
                    mysqli_query($conn, "INSERT INTO devoir(date_dev, coeficient, codemat, codecl, numsem, n_devoir) VALUES ('$date', '$coefficient', '$codemat', '$codecl', '$semestre', '$n_devoir')");
                    echo '<h1>Insertion avec succès</h1>';
                }
            } else {
                $retour = mysqli_query($conn, "SELECT DISTINCT nom FROM classe");
                $data = mysqli_query($conn, "SELECT DISTINCT promotion FROM classe ORDER BY promotion DESC");
                ?>
                <form action="ajout_devoir.php" method="POST">
                    <FIELDSET>
                        <LEGEND align=top>Classe/promotion<LEGEND>  <pre>
                            Promotions      :        <select name="promotion">
                                <?php while ($a = mysqli_fetch_array($data)) {
                                    echo '<option value="' . $a['promotion'] . '">' . $a['promotion'] . '</option>';
                                } ?></select><br/><br/>
                            Classe               :         <select name="nomcl">
                                <?php while ($a = mysqli_fetch_array($retour)) {
                                    echo '<option value="' . $a['nom'] . '">' . $a['nom'] . '</option>';
                                } ?></select><br/><br/>
                            <center><input type="submit" value="Suivant"></center>
                        </pre></FIELDSET>
                </form>
            <?php } ?>
    </pre></center>
</div>
</body>
</html>
