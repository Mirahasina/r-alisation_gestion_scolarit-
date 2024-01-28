<?php
session_start();
include('cadre.php');
echo '<div class="corp">';

if(isset($_GET['modif_dip'])){
    // modif_el qu'on a récupéré de l'affichage (modifier)
    $id = $_GET['modif_dip'];
    $result = mysql_query("SELECT * FROM eleve, classe, eleve_diplome, diplome WHERE eleve.numel = eleve_diplome.numel AND classe.codecl = eleve.codecl AND diplome.numdip = eleve_diplome.numdip AND id = '$id'");
    $ligne = mysql_fetch_array($result);
    $titre = mysql_query("SELECT numdip, titre_dip FROM diplome");
?>

    <!-- <img src="titre_img/modif_diplom.png" class="position_titre"> -->
    <center>
        <pre>
            <form action="modif_diplome.php" method="POST" class="formulaire">
                Nom et prénom : <?php echo $ligne['nomel'].' '.$ligne['prenomel']; ?><br/>
                Classe : <?php echo $ligne['nom']; ?><br/>
                Promotion : <?php echo $ligne['promotion']; ?><br/>
                Titre du diplôme : <select name="titre">
                    <?php while($var=mysql_fetch_array($titre)){  
                        echo '<option value="'.$var['numdip'].'" '.choixpardefault2($var['titre_dip'],$ligne['titre_dip']).'>'.$var['titre_dip'].'</option>'; 
                    } ?>
                </select><br/>
                Note : <input type="text" name="note" value="<?php echo $ligne['note']; ?>"><br/>
                Commentaire : <input type="text" name="comment" value="<?php echo $ligne['commentaire']; ?>"><br/>
                Etablissement : <input type="text" name="etabli" value="<?php echo $ligne['etablissement']; ?>"><br/>
                Lieu : <input type="text" name="lieu" value="<?php echo $ligne['lieu']; ?>"><br/>
                Année d'obtention : <input type="text" name="ann_obt" value="<?php echo $ligne['annee_obtention']; ?>"><br/>
                <input type="hidden" name="id" value="<?php echo $id; ?>">
                <center><input type="image" src="modifier.png"></center>
            </form>
            <br/><br/><a href="listeEtudiant.php?nomcl=<?php echo $ligne['nom']; ?>">Revenir à la page précédente !</a>
        </pre>
    </center>

<?php
}

if(isset($_POST['titre'])){
    if($_POST['titre']!="" and $_POST['note']!="" and $_POST['etabli']!="" and $_POST['lieu']!="" and $_POST['ann_obt']!=""){
        $id = $_POST['id'];
        $numdip = addslashes(htmlspecialchars($_POST['titre']));
        $note = addslashes(htmlspecialchars($_POST['note']));
        $lieu = addslashes(htmlspecialchars($_POST['lieu']));
        $etabli = addslashes(htmlspecialchars($_POST['etabli']));
        $comment = addslashes(htmlspecialchars($_POST['comment']));
        $annee = addslashes(htmlspecialchars($_POST['ann_obt']));

        mysql_query("UPDATE eleve_diplome SET numdip='$numdip', lieu='$lieu', etablissement='$etabli', commentaire='$comment', note='$note', annee_obtention='$annee' WHERE id='$id'");
        ?> <script language="JavaScript"> alert("Modifié avec succès!"); </script> <?php
    } else {
        ?> <script language="JavaScript"> alert("Erreur! Vous devez remplir tous les champs"); </script> <?php
    }
    echo '<a href="modif_diplome.php?modif_dip='.$id.'">Revenir à la page précédente !</a>';
}

if(isset($_GET['supp_dip'])){
    $id = $_GET['supp_dip'];
    mysql_query("DELETE FROM eleve_diplome WHERE id='$id'");
    ?> <script language="JavaScript"> alert("Supprimé avec succès!"); </script> <?php
    echo '<br/><br/><a href="index.php?">Revenir à la page principale !</a>';
}

echo '</div>';
?>
