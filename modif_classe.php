<?php
session_start();
include('cadre.php');

if (isset($_GET['modif_classe'])) {
    // modif_el qu'on a récupéré de l'affichage (modifier)
    $id = $_GET['modif_classe'];
    $result = mysql_query("SELECT codecl, classe.nom AS nomcl, promotion, numprofcoord, prof.nom, prenom FROM classe, prof WHERE numprof = numprofcoord AND codecl = '$id'");
    $ligne = mysql_fetch_array($result);
    $promo = mysql_query("SELECT DISTINCT promotion FROM classe");
    $prof = mysql_query("SELECT numprof, nom, prenom FROM prof");
    $nom = stripslashes($ligne['nomcl']);
    $numprof = stripslashes($ligne['numprofcoord']);
    $promotion = stripslashes($ligne['promotion']);
    ?>
    <div class="corp">
        <!-- <img src="titre_img/modifier_classe.png" class="position_titre"> -->
        <center>
            <pre>
                <form action="modif_classe.php" method="POST" class="formulaire">
                    <h4>Veuillez choisir les nouveaux informations :</h4><br/>
                    Nom de la classe : <input type="text" name="nom" value="<?php echo $nom; ?>"><br/><br/>
                    Prof coordinataire :
                    <select name="prof">
                        <?php
                        while ($a = mysql_fetch_array($prof)) {
                            echo '<option value="' . $a['numprof'] . '" ' . choixpardefault2($a['numprof'], $numprof) . '>
                                    ' . $a['nom'] . ' ' . $a['prenom'] . '</option>';
                        }
                        ?>
                    </select><br/><br/>
                    Promotion :
                    <select name="promo">
                        <?php
                        while ($a = mysql_fetch_array($promo)) {
                            echo '<option value="' . $a['promotion'] . '" ' . choixpardefault2($a['promotion'], $promotion) . '>
                                    ' . $a['promotion'] . '</option>';
                        }
                        ?>
                    </select><br/><br/>
                    <input type="hidden" name="id" value="<?php echo $id; ?>">
                    <!-- pour revenir en arrière et pour avoir l'id dont lequel on va modifier-->
                    <center><input type="image" src="modifier.png"></center>
                </form>
                <br/><br/><a href="affiche_classe.php">Revenir à la page précédente !</a>
            </pre>
        </center>
    </div>
<?php
}

if (isset($_POST['nom'])) {
    // s'il a cliqué sur le bouton modifier
    if ($_POST['nom'] != "") {
        $id = $_POST['id'];
        $nom = addslashes(htmlspecialchars($_POST['nom']));
        $prof = addslashes(htmlspecialchars($_POST['prof']));
        $promo = addslashes(htmlspecialchars($_POST['promo']));

        mysql_query("UPDATE classe SET nom='$nom', numprofcoord='$prof', promotion='$promo' WHERE codecl='$id'");
        ?>
        <script language="JavaScript"> alert("Modifié avec succès!"); </script>
        <?php
        echo '<br/><br/><a href="modif_classe.php?modif_classe=' . $id . '">Revenir à la page précédente !</a>';
    } else {
        echo '<h1>Erreur! Vous devez remplir tous les champs<h1>';
        echo '<br/><br/><a href="modif_classe.php?modif_classe=' . $id . '">Revenir à la page précédente !</a>';
    }
}

if (isset($_GET['supp_classe'])) {
    $id = $_GET['supp_classe'];
    mysql_query("DELETE FROM classe WHERE codecl='$id'");
    ?>
    <script language="JavaScript"> alert("Supprimé avec succès!"); </script>
    <?php
    echo '<br/><br/><a href="affiche_classe.php">Revenir à la page précédente !</a>';
}
?>
