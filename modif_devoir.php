<?php
session_start();
include('cadre.php');
include('calendrier.html');

echo '<div class="corp">';

if(isset($_GET['modif_dev'])){
    // modif_el qu'on a récupéré de l'affichage (modifier)
    $id = $_GET['modif_dev'];
    $result = mysql_query("SELECT * FROM classe, devoir, matiere WHERE classe.codecl = devoir.codecl AND matiere.codemat = devoir.codemat AND numdev = '$id'");
    $ligne = mysql_fetch_array($result);
    $date = $ligne['date_dev'];
?>
    <center>
        <pre>
            <h1>Modifier un devoir</h1>
            <form action="modif_devoir.php" method="POST" class="formulaire">
                Matière : <?php echo $ligne['nommat']; ?><br/>
                Classe : <?php echo stripslashes($ligne['nom']); ?><br/>
                Promotion : <?php echo $ligne['promotion']; ?><br/>
                Coefficient : <input type="text" name="coeficient" value="<?php echo $ligne['coeficient']; ?>"><br/>
                Semestre : <?php echo $ligne['numsem']; ?><br/>
                Devoir N° : <input type="text" name="n_devoir" value="<?php echo $ligne['n_devoir']; ?>"><br/>
                Date du devoir : <input type="text" name="date" class="calendrier" value="<?php echo $date; ?>"/>
                <input type="hidden" name="id" value="<?php echo $id; ?>">
                <input type="hidden" name="numdev" value="<?php echo $ligne['numdev']; ?>">
                <input type="image" src="modifier.png">
            </form>
            <br/><br/><a href="afficher_devoir.php">Revenir à la page précédente !</a>
        </pre>
    </center>
<?php
}

if(isset($_POST['n_devoir'])){
    // s'il a cliqué sur le bouton modifier
    $id = $_POST['id'];
    if(($_POST['n_devoir']=="1" or $_POST['n_devoir']=="2") and $_POST['date']!="" and $_POST['coeficient']!=""){
        $n_devoir = $_POST['n_devoir'];
        $numdev = $_POST['numdev'];
        $coeficient = $_POST['coeficient'];
        $date = $_POST['date'];
        
        $result = mysql_query("SELECT COUNT(*) AS nb FROM devoir WHERE n_devoir = '$n_devoir' AND numdev = '$numdev' AND date_dev = '$date'");
        $compte = mysql_fetch_array($result);
        
        if($compte['nb'] != 0){
            ?> <script language="JavaScript"> alert("Erreur de modification, ce devoir existe déjà (vérifiez le numéro de devoir)"); </script> <?php
        } else {
            mysql_query("UPDATE devoir SET n_devoir='$n_devoir', coeficient='$coeficient', date_dev='$date' WHERE numdev='$id'");
            ?> <script language="JavaScript"> alert("Modifié avec succès!"); </script> <?php
        }
    } else {
        ?> <script language="JavaScript"> alert("Erreur! Vous devez remplir tous les champs (numéro de devoir 1 ou 2)"); </script> <?php
    }
    echo '<br/><br/><a href="modif_devoir.php?modif_dev='.$id.'">Revenir à la page précédente !</a>';
}

if(isset($_GET['supp_dev'])){
    $id = $_GET['supp_dev'];
    mysql_query("DELETE FROM devoir WHERE numdev='$id'");
    mysql_query("DELETE FROM evaluation WHERE numdev='$id'");
    ?> <script language="JavaScript"> alert("Supprimé avec succès!\nToutes les évaluations de ce devoir ont été supprimées"); </script> <?php
    echo '<br/><br/><a href="afficher_devoir.php">Revenir à la page d\'affichage</a>';
}

echo '</div>';
?>
