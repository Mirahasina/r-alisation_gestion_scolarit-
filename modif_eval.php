<?php
session_start();
include('cadre.php');
// echo '<div class="corp"><img src="titre_img/modif_evalu.png" class="position_titre"><pre>';

if(isset($_GET['modif_eval'])){
    $id = $_GET['modif_eval'];
    $ligne = mysql_fetch_array(mysql_query("SELECT * FROM evaluation, eleve, classe WHERE eleve.numel = evaluation.numel AND eleve.codecl = classe.codecl AND numeval = '$id'"));

    $codecl = $ligne['codecl'];
    $eleve = mysql_query("SELECT numel, nomel, prenomel FROM eleve WHERE codecl = '$codecl'");
    $numdev = stripslashes($ligne['numdev']);
    $mat_dev = mysql_fetch_array(mysql_query("SELECT * FROM matiere, devoir WHERE devoir.codemat = matiere.codemat AND numdev = '$numdev'"));
?>

<form action="modif_eval.php" method="POST" class="formulaire">
    Matière: <?php echo $mat_dev['nommat']; ?><br/>
    Classe: <?php echo stripslashes($ligne['nom']); ?><br/>
    Promotion: <?php echo stripslashes($ligne['promotion']); ?><br/>
    Date du devoir: <?php echo stripslashes($mat_dev['date_dev']); ?><br/>
    Coefficient: <?php echo stripslashes($mat_dev['coeficient']); ?><br/>
    Semestre: S<?php echo $mat_dev['numsem']; ?><br/>
    Devoir N°: <?php echo $mat_dev['n_devoir']; ?><br/>
    Étudiant: 
    <select name="numel"> 
        <?php 
        while($a = mysql_fetch_array($eleve)){
            echo '<option value="'.$a['numel'].'" '.choixpardefault2($a['numel'], $ligne['numel']).'>'.$a['nomel'].' '.$a['prenomel'].'</option>';
        }
        ?>
    </select><br/>
    Note: <input type="text" name="note" value="<?php echo $ligne['note']; ?>">

    <input type="hidden" name="id" value="<?php echo $id; ?>">
    <center><input type="image" src="modifier.png" style="margin-top:13px;"></center>
</form>

<?php
    echo '<br/><br/><a href="afficher_evaluation.php">Revenir à la page précédente !</a>';
}

if(isset($_POST['numel'])){
    if($_POST['note'] != ""){
        $id = $_POST['id'];
        $numel = $_POST['numel'];
        $note = str_replace(",", ".", $_POST['note']);
        mysql_query("UPDATE evaluation SET numel='$numel', note='$note' WHERE numeval='$id'");
        ?>
        <script language="JavaScript">	alert("Modifié avec succès!"); </script>
        <?php
    } else {
        ?>
        <script language="JavaScript">	alert("Erreur! Vous devez remplir tous les champs"); </script>
        <?php
    }

    echo '<br/><br/><a href="modif_eval.php?modif_eval='.$id.'">Revenir à la page précédente !</a>';
}

if(isset($_GET['supp_eval'])){
    $id = $_GET['supp_eval'];
    mysql_query("DELETE FROM evaluation WHERE numeval='$id'");
    ?>
    <script language="JavaScript">	alert("Supprimé avec succès!"); </script>
    <?php
    echo '<br/><br/><a href="afficher_evaluation.php">Revenir à la page d\'affichage</a>';
}

echo '</pre></div>';
?>
