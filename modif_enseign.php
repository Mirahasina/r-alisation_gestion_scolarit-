<?php
session_start();
include('cadre.php');
include('calendrier.html');
// echo '<div class="corp"><img src="titre_img/modif_enseign.png" class="position_titre"><pre>';

if(isset($_GET['modif_ensein'])){
    $id = $_GET['modif_ensein'];
    $ligne = mysql_fetch_array(mysql_query("SELECT classe.codecl, prof.numprof, promotion, classe.nom as nomcl, prenom, prof.nom as nomp, matiere.codemat, nommat, numsem FROM classe, matiere, enseignement, prof WHERE classe.codecl = enseignement.codecl AND matiere.codemat = enseignement.codemat AND prof.numprof = enseignement.numprof AND id = '$id'"));
    $prof = mysql_query("SELECT * FROM prof");
    $mat = mysql_query("SELECT * FROM matiere");
?>

<form action="modif_enseign.php" method="POST" class="formulaire">
    Matière: 
    <select name="codemat"> 
        <?php 
        while($a = mysql_fetch_array($mat)){
            echo '<option value="'.$a['codemat'].'" '.choixpardefault2($a['codemat'], $ligne['codemat']).'>'.$a['nommat'].'</option>';
        }
        ?>
    </select><br/><br/>

    Professeur: 
    <select name="numprof"> 
        <?php 
        while($a = mysql_fetch_array($prof)){
            echo '<option value="'.$a['numprof'].'" '.choixpardefault2($a['numprof'], $ligne['numprof']).'>'.$a['nom'].' '.$a['prenom'].'</option>';
        }
        ?>
    </select><br/><br/>

    Classe: <?php echo stripslashes($ligne['nomcl']); ?><br/><br/>
    Promotion: <?php echo $ligne['promotion']; ?><br/><br/>
    Semestre: <?php echo $ligne['numsem']; ?>

    <input type="hidden" name="id" value="<?php echo $id; ?>">
    <input type="hidden" name="codecl" value="<?php echo $ligne['codecl']; ?>">
    <input type="hidden" name="numsem" value="<?php echo $ligne['numsem']; ?>">
    <input type="image" src="modifier.png">
</form>

<?php
    echo '<br/><br/><a href="afficher_devoir.php">Revenir à la page précédente !</a>';
}

if(isset($_POST['numprof'])){
    $id = $_POST['id'];
    $numprof = $_POST['numprof'];
    $codemat = $_POST['codemat'];
    $codecl = $_POST['codecl'];
    $numsem = $_POST['numsem'];
    
    $compte = mysql_fetch_array(mysql_query("SELECT COUNT(*) AS nb FROM enseignement WHERE numprof='$numprof' AND codemat='$codemat' AND codecl='$codecl'"));
    
    if($compte['nb'] != 0){
        ?>
        <script language="JavaScript">	alert("Erreur de modification, cet enseignement existe déjà"); </script>
        <?php
    } else {
        mysql_query("UPDATE enseignement SET numprof='$numprof', codemat='$codemat' WHERE id='$id'");
        $suppression = mysql_query("SELECT * FROM devoir WHERE codemat='$codemat' AND codecl='$codecl' AND numsem='$numsem'");
        
        while($a = mysql_fetch_array($suppression)){
            $cle = $a['numdev'];
            mysql_query("DELETE FROM evaluation WHERE numdev='$cle'");
            mysql_query("DELETE FROM devoir WHERE numdev='$cle'");
        }
        ?>
        <script language="JavaScript">	alert("Modifié avec succès! Toutes les entrées reliées à cet enregistrement ont été supprimées."); </script>
        <?php
    }
    
    echo '<br/><br/><a href="modif_enseign.php?modif_ensein='.$id.'">Revenir à la page précédente !</a>';
}

if(isset($_GET['supp_ensein'])){
    $id = $_GET['supp_ensein'];
    $ligne = mysql_fetch_array(mysql_query("SELECT classe.codecl, matiere.codemat, numsem FROM classe, matiere, enseignement WHERE classe.codecl = enseignement.codecl AND matiere.codemat = enseignement.codemat AND id = '$id'"));
    $codemat = $ligne['codemat'];
    $codecl = $ligne['codecl'];
    $numsem = $ligne['numsem'];
    
    $suppression = mysql_query("SELECT * FROM devoir WHERE codemat='$codemat' AND codecl='$codecl' AND numsem='$numsem'");
    
    while($a = mysql_fetch_array($suppression)){
        $cle = $a['numdev'];
        mysql_query("DELETE FROM evaluation WHERE numdev='$cle'");
        mysql_query("DELETE FROM devoir WHERE numdev='$cle'");
    }
    
    mysql_query("DELETE FROM enseignement WHERE id='$id'");
    ?>
    <script language="JavaScript">	alert("Supprimé avec succès! Toutes les entrées reliées à cet enregistrement ont été supprimées."); </script>
    <?php
    echo '<br/><br/><a href="index.php">Revenir à la page principale</a>';
}
?>
</pre>
</div>
