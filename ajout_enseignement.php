<?php
session_start();
include('cadre.php');
// $conn = mysqli_connect("localhost", "root", "", "gestion");
include_once('config.php');

?>

<html>
<div class="corp">
    <!-- <img src="titre_img/ajout_enseignemt.png" class="position_titre"> -->

    <?php
    if (isset($_POST['nomcl'])) {
        $_SESSION['nomcl'] = $_POST['nomcl'];
        $nomcl = $_POST['nomcl'];
        $promo = $_POST['promotion'];
        $_SESSION['promo'] = $promo; //pour l'envoyer la 2eme fois 
        $donnee = mysqli_query($conn, "SELECT codemat, nommat FROM matiere,classe WHERE matiere.codecl=classe.codecl AND classe.nom='$nomcl' AND promotion='$promo'");
        $prof = mysqli_query($conn, "SELECT numprof, nom, prenom FROM prof");
        ?>

        <form action="ajout_enseignement.php" method="POST" class="formulaire">
            <FIELDSET>
                <LEGEND align=top>Ajoutet un enseignement<LEGEND>
                    <pre>
                        Matière       :    <select name="choix_mat" id="choix">
                            <?php
                            while ($a = mysqli_fetch_array($donnee)) {
                                echo '<option value="' . $a['codemat'] . '">' . $a['nommat'] . '</option>';
                            }
                            ?>
                        </select><br/><br/>
                        Enseignant   :  <select name="n_prof">
                            <?php while ($prof2 = mysqli_fetch_array($prof)) {
                                echo '<option value="' . $prof2['numprof'] . '">' . $prof2['nom'] . ' ' . $prof2['prenom'] . '</option>';
                            }
                            ?>
                        </select><br/><br/>
                        Semestre       :    <select name="semestre">
                            <?php for ($i = 1; $i <= 4; $i++) {
                                echo '<option value="' . $i . '">Semestre' . $i . '</option>';
                            } ?>
                        </select><br/><br/>
                        <center><input type="image" src="button.png"></center>
                    </pre>
            </FIELDSET>
        </form>

    <?php } else if (isset($_POST['semestre'])) { //s'il a cliqué sur ajouter la 2eme fois
        $semestre = $_POST['semestre'];
        $codemat = $_POST['choix_mat'];
        $nomcl = $_SESSION['nomcl'];
        $n_prof = $_POST['n_prof']; //Premier ou 2eme devoir -- 1 ou 2
        $promo = $_SESSION['promo'];
        $codeclasse = mysqli_fetch_array(mysqli_query($conn, "SELECT codecl FROM classe WHERE nom='$nomcl' AND promotion='$promo'"));
        $codecl = $codeclasse['codecl'];

        /*
         pour ne pas ajouter deux enseignements similaires
         */
        $data = mysqli_query($conn, "SELECT count(*) as nb FROM enseignement WHERE codecl='$codecl' AND codemat='$codemat' AND numsem='$semestre'");
        $nb = mysqli_fetch_array($data);
        $bool = true;

        /*
        pour ne pas ajouter deux contrôles similaires
        */
        if ($nb['nb'] > 0) {
            $bool = false;
            echo '<br\><h2>Erreur d\'insertion!! (impossible d\'ajouter deux enseignements similaires)</h2>';
            ?><SCRIPT LANGUAGE="Javascript">alert("Erreur d'insertion\nimpossible d'ajouter deux enseignements similaires");</SCRIPT><?php
        }
        if ($bool == true) {
            mysqli_query($conn, "INSERT INTO enseignement(codecl, codemat, numprof, numsem) VALUES('$codecl', '$codemat', '$n_prof', '$semestre')");
            ?><SCRIPT LANGUAGE="Javascript">	alert("Ajouté avec succès!"); </SCRIPT> <?php
        }
        echo '<br/><br/><a href="ajout_enseignement.php?">Revenir à la page précédente !</a>';
    } else {
        $data = mysqli_query($conn, "SELECT DISTINCT promotion FROM classe ORDER BY promotion DESC"); //select pour les promotions
        $donnee = mysqli_query($conn, "SELECT DISTINCT nom FROM classe");
        ?>

        <form action="ajout_enseignement.php" method="POST" class="formulaire">
            <FIELDSET>
                <LEGEND align=top>Critères d'ajout<LEGEND>
                        <pre>
                         Classe          :       <select name="nomcl">
                        <?php while ($a = mysqli_fetch_array($donnee)) {
                            echo '<option value="' . $a['nom'] . '">' . $a['nom'] . '</option>';
                        } ?></select><br/><br/>
                         Promotion   :     <select name="promotion">
                        <?php while ($a = mysqli_fetch_array($data)) {
                            echo '<option value="' . $a['promotion'] . '">' . $a['promotion'] . '</option>';
                        } ?></select><br/><br/>
                         <center><input type="submit" value="Afficher"></center>
                        </pre>
            </FIELDSET>
        </form>
    <?php } ?>
</div>
</html>
