<?php
session_start();
include('cadre.php');
// $conn = mysqli_connect("localhost", "root", "", "gestion");
include_once('config.php');

?>

<html>
    <div class="corp">
        <!-- <img src="titre_img/ajout_prof.png" class="position_titre"> -->
        <center>
            <pre>
                <?php
                if (isset($_POST['adresse'])) {
                    // s'il a cliqué sur ajouter la 2ème fois
                    if ($_POST['nom'] != "" && $_POST['prenom'] != "" && $_POST['adresse'] != "" && $_POST['telephone'] != "" && $_POST['pseudo'] != "" && $_POST['passe'] != "") {
                        $nom = addslashes($_POST['nom']);
                        $prenom = addslashes($_POST['prenom']);
                        $adresse = addslashes(nl2br(htmlspecialchars($_POST['adresse'])));
                        $telephone = $_POST['telephone'];
                        $pseudo = $_POST['pseudo'];
                        $passe = $_POST['passe'];

                        $compte = mysqli_fetch_array(mysqli_query($conn, "SELECT COUNT(*) as nb FROM prof WHERE nom='$nom' AND prenom='$prenom'"));

                        // pour ne pas ajouter deux profs similaires
                        if ($compte['nb'] > 0) {
                            ?>
                            <SCRIPT LANGUAGE="Javascript">
                                alert("Erreur! Ce professeur existe déjà.");
                            </SCRIPT>
                            <?php
                        } else {
                            mysqli_query($conn, "INSERT INTO prof(nom, prenom, adresse, telephone) VALUES ('$nom', '$prenom', '$adresse', '$telephone')");

                            // Ajouter le num dans le login
                            $numprof = mysqli_fetch_array(mysqli_query($conn, "SELECT numprof FROM prof WHERE nom='$nom' AND prenom='$prenom'"));
                            $num = $numprof['numprof'];
                            mysqli_query($conn, "INSERT INTO login(Num, pseudo, passe, type) VALUES ('$num', '$pseudo', '$passe', 'prof')");
                            ?>
                            <SCRIPT LANGUAGE="Javascript">
                                alert("Insertion avec succès!");
                            </SCRIPT>
                            <?php
                        }
                    } else {
                        ?>
                        <SCRIPT LANGUAGE="Javascript">
                            alert("Vous devez remplir tous les champs!");
                        </SCRIPT>
                        <?php
                    }
                    echo '<br/><a href="ajout_prof.php">Revenir à la page précédente !</a>';
                } else {
                    ?>
                    <form action="ajout_prof.php" method="POST" class="formulaire">
                        Nom           :         <input type="text" name="nom"><br/>
                        Prenom      :         <input type="text" name="prenom"><br/>
                        Adresse     :          <textarea name="adresse"></textarea><br/>
                        Telephone  :       <input type="text" name="telephone"> <br/>
                        Pseudo        :      <input type="text" name="pseudo"> <br/>
                        Password     :       <input type="password" name="passe"> <br/>
                        <center><input type="image" src="button.png"></center>
                    </form>
                <?php
                }
                ?>
            </pre>
        </center>
    </div>
</html>
