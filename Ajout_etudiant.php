<?php
session_start();
include('cadre.php');
include('calendrier.html');
// $conn = mysqli_connect("localhost", "root", "", "gestion");
include_once('config.php');

?>

<html>
<div class="corp">
    <!-- <img src="titre_img/ajout_etudiant.png" class="position_titre"> -->
    <center><pre>

    <?php
    if (isset($_POST['nom'])) {
        if (
            $_POST['nom'] != "" && $_POST['prenom'] != "" &&
            $_POST['date'] != "" && $_POST['adresse'] != "" &&
            $_POST['phone'] != "" && $_POST['pseudo'] != "" &&
            $_POST['mdp'] != ""
        ) {
            $nom = mysqli_real_escape_string($conn, $_POST['nom']);
            $prenom = mysqli_real_escape_string($conn, $_POST['prenom']);
            $date = mysqli_real_escape_string($conn, $_POST['date']);
            $phone = mysqli_real_escape_string($conn, $_POST['phone']);
            $adresse = mysqli_real_escape_string($conn, nl2br($_POST['adresse']));
            $nomcl = $_POST['nomcl'];
            $promo = $_POST['promotion'];
            $pseudo = mysqli_real_escape_string($conn, $_POST['pseudo']);
            $passe = mysqli_real_escape_string($conn, $_POST['mdp']);

            $nb = mysqli_fetch_array(mysqli_query($conn, "SELECT count(*) as nb FROM eleve WHERE nomel='$nom' AND prenomel='$prenom'"));

            if ($nb['nb'] != 0) {
                ?><SCRIPT LANGUAGE="Javascript">alert("Erreur! Cet enregistrement existe déjà!");</SCRIPT><?php
            } else {
                $data = mysqli_fetch_array(mysqli_query($conn, "SELECT codecl FROM classe WHERE nom='$nomcl' AND promotion='$promo'"));
                $codecl = $data['codecl'];
                mysqli_query($conn, "INSERT INTO eleve(nomel, prenomel, date_naissance, adresse, telephone, codecl) VALUES('$nom', '$prenom', '$date', '$adresse', '$phone', '$codecl')");

                // Ajouter le numéro dans le login
                $numel = mysqli_fetch_array(mysqli_query($conn, "SELECT numel FROM eleve WHERE nomel='$nom' AND prenomel='$prenom'"));
                $num = $numel['numel'];
                mysqli_query($conn, "INSERT INTO login(Num, pseudo, passe, type) VALUES('$num', '$pseudo', '$passe', 'etudiant')");

                ?><SCRIPT LANGUAGE="Javascript">alert("Ajout avec succès!");</SCRIPT> <?php
            }
        } else {
            ?><SCRIPT LANGUAGE="Javascript">alert("Vous devez remplir tous les champs!");</SCRIPT> <?php
        }
    }
    ?>

    <?php
    $data = mysqli_query($conn, "SELECT DISTINCT promotion FROM classe ORDER BY promotion DESC");
    ?>
    <form action="Ajout_etudiant.php" method="POST" class="formulaire">
        <FIELDSET>
            <LEGEND align=top>Ajouter un Étudiant<LEGEND>
                    <pre>
Nom étudiant        :       <input type="text" name="nom"><br/>
Prénom                   :       <input type="text" name="prenom"><br/>
Date de naissance   :       <input type="text" name="date" class="calendrier"><br/>
Adresse                    :        <input type="text" name="adresse"><br/>
Téléphone              :        <input type="text" name="phone"><br/>
Pseudo                    :        <input type="text" name="pseudo"><br/>
Mot de passe         :        <input type="password" name="mdp"><br/>
Classe                     :        <select name="nomcl"> 
    <?php
    $retour = mysqli_query($conn, "SELECT DISTINCT nom FROM classe"); // afficher les classes
    while ($a = mysqli_fetch_array($retour)) {
        echo '<option value="' . $a['nom'] . '">' . $a['nom'] . '</option>';
    } ?></select><br/>
Promotion              :      <select name="promotion"> 
    <?php while ($a = mysqli_fetch_array($data)) {
        echo '<option value="' . $a['promotion'] . '">' . $a['promotion'] . '</option>';
    } ?></select><br/>
<center><input type="image" src="button.png"></center>
</pre></FIELDSET>
</form>
</pre></center>
</div>
</body>
</html>
