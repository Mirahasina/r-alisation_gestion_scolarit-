<?php
session_start();
include('cadre.php');
include('calendrier.html');
// mysql_connect("localhost", "root", "");
// mysql_select_db("gestion");
include_once('config.php');

if(isset($_GET['modif_el'])){
    $id = $_GET['modif_el'];
    $ligne = mysql_fetch_array(mysql_query("SELECT * FROM eleve, classe WHERE eleve.codecl = classe.codecl AND numel = '$id'"));
    $nom = stripslashes($ligne['nomel']);
    $prenom = stripslashes($ligne['prenomel']);
    $date = stripslashes($ligne['date_naissance']);
    $phone = stripslashes($ligne['telephone']);
    $adresse = str_replace("<br />", ' ', stripslashes($ligne['adresse']));
?>

<div class="corp">
    <img src="titre_img/modif_eleve.png" class="position_titre">
    <center>
        <pre>
            <form action="modif_eleve.php" method="POST" class="formulaire">
                <fieldset>
                    <legend align="top">Modifier un étudiant</legend>
                    <pre>
                        Nom étudiant    : <?php echo $nom; ?><br/>
                        Prénom          : <?php echo $prenom; ?><br/>
                        Date de naissance : <input type="text" name="date" class="calendrier" value="<?php echo $date; ?>"><br/>
                        Adresse         : <textarea name="adresse"><?php echo $adresse; ?></textarea><br/>
                        Téléphone       : <input type="text" name="phone" value="<?php echo $phone; ?>"><br/>
                        Classe          : <?php echo $ligne['nom']; ?><br/>
                        Promotion       : <?php echo $ligne['promotion']; ?>
                        <input type="hidden" name="id" value="<?php echo $id; ?>"><br/>
                        <input type="image" src="button.png">
                    </pre>
                </fieldset>
            </form>
            <a href="listeEtudiant.php?nomcl=<?php echo $ligne['nom']; ?>">Revenir à la page précédente !</a>
        </pre>
    </center>
</div>

<?php
}

if(isset($_POST['adresse'])){
    if($_POST['date']!="" and $_POST['adresse']!="" and $_POST['phone']!=""){
        $id = $_POST['id'];
        $date = addslashes(htmlspecialchars($_POST['date']));
        $phone = addslashes(htmlspecialchars($_POST['phone']));
        $adresse = addslashes(nl2br(htmlspecialchars($_POST['adresse'])));
        mysql_query("UPDATE eleve SET date_naissance='$date', adresse='$adresse', telephone='$phone' WHERE numel='$id'");
        ?> <script language="JavaScript"> alert("Modifié avec succès!"); </script> <?php
    } else {
        ?> <script language="JavaScript"> alert("Erreur! Vous devez remplir tous les champs"); </script> <?php
    }
    echo '<div class="corp"><br/><br/><a href="modif_eleve.php?modif_el='.$id.'">Revenir à la page précédente !</a></div>';
}

if(isset($_GET['supp_el'])){
    $id = $_GET['supp_el'];
    mysql_query("DELETE FROM eleve WHERE numel='$id'");
    mysql_query("DELETE FROM evaluation WHERE numel='$id'");
    mysql_query("DELETE FROM stage WHERE numel='$id'");
    mysql_query("DELETE FROM bulletin WHERE numel='$id'");
    ?> <script language="JavaScript"> alert("Supprimé avec succès!"); </script> <?php
    echo '<br/><br/><a href="index.php?">Revenir à la page principale !</a>';
}
?>
