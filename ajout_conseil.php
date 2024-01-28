<?php
session_start();
include('cadre.php');
// $conn = mysqli_connect("localhost", "root", "", "gestion");
include_once('config.php');

?>

<html>
<body>
<div class="corp">
<!-- <img src="titre_img/ajout_conseil.png" class="position_titre"> -->
<pre>
<?php
if (isset($_POST['nomcl']) && isset($_POST['radiosem'])) {
    $nomcl = $_POST['nomcl'];
    $promo = $_POST['promotion'];
    $semestre = $_POST['radiosem'];

    $code_classe = mysqli_fetch_array(mysqli_query($conn, "SELECT codecl FROM classe WHERE nom='$nomcl' AND promotion='$promo'"));
    $codecl = $code_classe['codecl'];

    $compte = mysqli_fetch_array(mysqli_query($conn, "SELECT COUNT(*) AS nb FROM conseil WHERE numsem='$semestre' AND codecl='$codecl'"));
    if ($compte['nb'] > 0) {
?>
        <SCRIPT LANGUAGE="Javascript">alert("Erreur! Ce conseil existe déjà");</SCRIPT>
<?php
    } else {
        mysqli_query($conn, "INSERT INTO conseil(numsem, codecl) VALUES ('$semestre', '$codecl')");

        /*
         À la veille de chaque conseil de classe : on suppose qu'un étudiant passe 2 devoirs dans la même matière dans un semestre,
         on spécifie le semestre dans la requête, alors si on regroupe par numel et codemat, on va trouver au maximum 2 notes.
         */
        $bulletin = mysqli_query($conn, "SELECT eleve.numel, matiere.codemat, AVG(note) AS moyen 
                                        FROM eleve, devoir, matiere, evaluation, classe 
                                        WHERE matiere.codemat = devoir.codemat 
                                        AND classe.codecl = devoir.codecl 
                                        AND devoir.numdev = evaluation.numdev 
                                        AND evaluation.numel = eleve.numel 
                                        AND devoir.codecl = (SELECT codecl FROM classe WHERE nom='$nomcl' AND promotion='$promo') 
                                        AND numsem='$semestre' 
                                        GROUP BY numel, matiere.codemat");

        while ($b = mysqli_fetch_array($bulletin)) {
            $numel = $b['numel'];
            $codemat = $b['codemat'];
            $notef = $b['moyen'];
            mysqli_query($conn, "INSERT INTO bulletin(numsem, numel, codemat, notefinal) VALUES ('$semestre', '$numel', '$codemat', '$notef')");
        }
?>
        <SCRIPT LANGUAGE="Javascript">alert("Ajouté avec succès!");</SCRIPT>
<?php
    }
?>
    <br/><br/><a href="ajout_conseil.php">Revenir à la page précédente !</a>
<?php
} else {
    $data = mysqli_query($conn, "SELECT DISTINCT promotion FROM classe ORDER BY promotion DESC");
    $retour = mysqli_query($conn, "SELECT DISTINCT nom FROM classe"); // afficher les classes
?>
    <form method="post" action="ajout_conseil.php" class="formulaire">
        Veuillez choisir le Semestre, la promotion et la classe :<br/><br/><br/>
        Promotion : <select name="promotion">
        <?php while ($a = mysqli_fetch_array($data)) {
            echo '<option value="'.$a['promotion'].'">'.$a['promotion'].'</option>';
        }?></select><br/><br/>
        Classe : <select name="nomcl">
        <?php while ($a = mysqli_fetch_array($retour)) {
            echo '<option value="'.$a['nom'].'">'.$a['nom'].'</option>';
        }?></select><br/><br/>
        Semestre : <select name="radiosem">
        <?php for ($i = 1; $i <= 4; $i++) {
            echo '<option value="'.$i.'">Semestre'.$i.'</option>';
        } ?></select><br/><br/>
        <input type="submit" value="Valider le conseil">
    </form>
<?php } ?>
</pre>
</div>
</body>
</html>
