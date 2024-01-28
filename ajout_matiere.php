<?php
session_start();
include('cadre.php');
// $conn = mysqli_connect("localhost", "root", "", "gestion");
include_once('config.php');

?>

<div class="corp">
    <!-- <img src="titre_img/ajout_matiere.png" class="position_titre"> -->
    <div class="formulaire">
        <pre>
            <?php
            if (isset($_POST['promotion'])) {
                $_SESSION['promo'] = $_POST['promotion'];
                $_SESSION['nomcl'] = $_POST['nomcl'];
            ?>
                <form action="ajout_matiere.php" method="POST">
                    Veuillez saisir la nouvelle matière : <br/><br/>
                    Matière       :      <input type="text" name="nommat"><br/><br/>
                    <center><input type="image" src="button.png"></center>
                </form>
            <?php
            } elseif (isset($_POST['nommat'])) {
                if ($_POST['nommat'] != "") {
                    $nomcl = $_SESSION['nomcl'];
                    $nommat = addslashes(htmlspecialchars($_POST['nommat']));
                    $promo = $_SESSION['promo'];
                    $codeclasse = mysqli_fetch_array(mysqli_query($conn, "SELECT codecl FROM classe WHERE nom='$nomcl' AND promotion='$promo'"));
                    $codecl = $codeclasse['codecl'];
                    $compte = mysqli_fetch_array(mysqli_query($conn, "SELECT COUNT(*) as nb FROM matiere WHERE nommat='$nommat' AND codecl='$codecl'"));
                    $bool = true;

                    if ($compte['nb'] > 0) {
            ?>
                        <SCRIPT LANGUAGE="Javascript">
                            alert("Erreur d'insertion, l'enregistrement existe déjà");
                        </SCRIPT>
                    <?php
                        $bool = false;
                    }

                    if ($bool == true) {
                        mysqli_query($conn, "INSERT INTO matiere(nommat, codecl) VALUES ('$nommat','$codecl')");
                    ?>
                        <SCRIPT LANGUAGE="Javascript">
                            alert("Ajouté avec succès!");
                        </SCRIPT>
                    <?php
                    }
                } else {
                    ?>
                    <SCRIPT LANGUAGE="Javascript">
                        alert("Veuillez remplir tous les champs!");
                    </SCRIPT>
            <?php
                }
                echo '<a href="Ajout_matiere.php">Revenir à la page précédente !</a>';
            } else {
                $data = mysqli_query($conn, "SELECT DISTINCT promotion FROM classe ORDER BY promotion DESC");
                $nomclasse = mysqli_query($conn, "SELECT DISTINCT nom FROM classe");
            ?>
                <form action="ajout_matiere.php" method="POST">
                    Promotion        :             <select name="promotion">
                        <?php while ($a = mysqli_fetch_array($data)) {
                            echo '<option value="' . $a['promotion'] . '">' . $a['promotion'] . '</option>';
                        } ?>
                    </select><br/><br/>
                    Classe                 :         <select name="nomcl">
                        <?php while ($a = mysqli_fetch_array($nomclasse)) {
                            echo '<option value="' . $a['nom'] . '">' . $a['nom'] . '</option>';
                        } ?>
                    </select><br/><br/>
                    <center><input type="submit" value="Suivant"></center>
                </form>
            <?php } ?>
        </pre>
    </div>
</div>
</html>
