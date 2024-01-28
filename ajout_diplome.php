<?php
session_start();
include('cadre.php');
// $conn = mysqli_connect("localhost", "root", "", "gestion");
include_once('config.php');

?>

<div class="corp">
    <pre>
        <?php
        if (isset($_GET['ajout_type'])) { ?>
            <!-- <img src="titre_img/ajout_diplome.png" class="position_titre"> -->
            <form action="ajout_diplome.php" method="POST" class="formulaire">
                Veuillez saisir le titre du diplôme à ajouter : <br/><br/>
                Titre du diplôme       :       <input type="text" name="ajout_titre"><br/><br/>
                <center><input type="image" src="button.png"></center>
            </form>
        <?php } else if (isset($_GET['ajout_diplome'])) {
            $data = mysqli_query($conn, "SELECT DISTINCT promotion FROM classe ORDER BY promotion DESC");//select pour les promotions
            $nomclasse = mysqli_query($conn, "SELECT DISTINCT nom FROM classe");
            ?>
            <img src="titre_img/ajout_diplome.png" class="position_titre">
            <form action="ajout_diplome.php" method="POST" class="formulaire">
                Veuillez choisir la classe et la promotion : <br/>
                Promotion        :             <select name="promotion">
                    <?php while ($a = mysqli_fetch_array($data)) {
                        echo '<option value="' . $a['promotion'] . '">' . $a['promotion'] . '</option>';
                    } ?></select><br/><br/>
                Classe                 :         <select name="nomcl">
                    <?php while ($a = mysqli_fetch_array($nomclasse)) {
                        echo '<option value="' . $a['nom'] . '">' . $a['nom'] . '</option>';
                    } ?></select><br/><br/>
                <input type="submit" value="Suivant">
            </form>
        <?php } else if (isset($_POST['nomcl'])) {
            $nomcl = $_POST['nomcl'];
            $promo = $_POST['promotion'];
            $data = mysqli_query($conn, "SELECT numel, nomel, prenomel FROM eleve WHERE codecl = (SELECT codecl FROM classe WHERE nom='$nomcl' AND promotion='$promo')");
            $titre = mysqli_query($conn, "SELECT numdip, titre_dip FROM diplome");
            ?>
            <img src="titre_img/ajout_diplome.png" class="position_titre">
            <form action="ajout_diplome.php" method="POST" class="formulaire">
                Veuillez remplir les informations : <br/>
                Etudiant               :        <select name="numel">
                    <?php while ($a = mysqli_fetch_array($data)) {
                        echo '<option value="' . $a['numel'] . '">' . $a['nomel'] . ' ' . $a['prenomel'] . '</option>';
                    } ?></select><br/>
                Titre du diplôme        :    <select name="titre"><?php while ($var = mysqli_fetch_array($titre)) {
                        echo '<option value="' . $var['numdip'] . '">' . $var['titre_dip'] . '</option>';
                    } ?> </select><br/>
                Note                    :               <input type="text" name="note"><br/>
                Commentaire        :         <input type="text" name="comment"><br/>
                Etablissement        :      <input type="text" name="etabli"><br/>
                Lieu                     :       <input type="text" name="lieu"><br/>
                Année d'obtention             :       <input type="text" name="ann_obt"><br/>
                <center><input type="image" src="button.png"></center>
            </form>
        <?php } else if (isset($_POST['numel'])) {
            if ($_POST['note'] != "" and $_POST['lieu'] != "" and $_POST['comment'] != "" and $_POST['etabli'] != "" and $_POST['ann_obt'] != "") {
                $note = str_replace(',', '.', $_POST['note']);
                $comment = addslashes(htmlspecialchars($_POST['comment']));
                $etabli = addslashes(htmlspecialchars($_POST['etabli']));
                $annee = addslashes(htmlspecialchars($_POST['ann_obt']));
                $lieu = addslashes(nl2br(htmlspecialchars($_POST['lieu'])));
                $numel = $_POST['numel'];
                $numdip = $_POST['titre'];

                $nb = mysqli_fetch_array(mysqli_query($conn, "SELECT COUNT(*) AS nb FROM eleve_diplome WHERE numel='$numel'"));

                if ($nb['nb'] != 0) {
                    ?><script language="Javascript">alert("Erreur! Cet enregistrement existe déjà!");</script><?php
                } else {
                    mysqli_query($conn, "INSERT INTO eleve_diplome(numdip, numel, note, commentaire, etablissement, lieu, annee_obtention) VALUES('$numdip', '$numel', '$note', '$comment', '$etabli', '$lieu', '$annee')");
                    ?><script language="Javascript">alert("Ajout avec succès!");</script><?php
                }
            } else {
                ?><script language="Javascript">alert("Vous devez remplir tous les champs!");</script><?php
            }
            echo '<br/><br/><a href="ajout_diplome.php?ajout_diplome">Revenir à la page précédente !</a>';
        } else if (isset($_POST['ajout_titre'])) {
            $titre = $_POST['ajout_titre'];
            $nb = mysqli_fetch_array(mysqli_query($conn, "SELECT COUNT(*) AS nb FROM diplome WHERE titre_dip='$titre'"));

            if ($nb['nb'] != 0) {
                ?><script language="Javascript">alert("Erreur! Cet enregistrement existe déjà!");</script><?php
            } else {
                mysqli_query($conn, "INSERT INTO diplome(titre_dip) VALUES('$titre')");
                ?><script language="Javascript">alert("Ajout avec succès!");</script><?php
            }
            echo '<br/><br/><a href="ajout_diplome.php?ajout_type">Revenir à la page précédente !</a>';
        }
        ?>
    </pre>
</div>
