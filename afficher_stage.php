<?php
session_start();
include('cadre.php');
// $conn = mysqli_connect("localhost", "root", "", "gestion");
include_once('config.php');

$data = mysqli_query($conn, "SELECT DISTINCT promotion FROM classe ORDER BY promotion DESC");
$retour = mysqli_query($conn, "SELECT DISTINCT nom FROM classe");

?>

<html>
<body>
<div class="corp">
<!-- <img src="titre_img/affich_stage.png" class="position_titre"> -->
<center><pre>
<?php
if (isset($_POST['nomcl']) and isset($_POST['promotion'])) {
    $nomcl = $_POST['nomcl'];
    $promo = $_POST['promotion'];
    $donnee = mysqli_query($conn, "SELECT numstage, nomel, prenomel, nom, promotion, date_debut, date_fin, lieu_stage FROM eleve, stage, classe WHERE classe.codecl=eleve.codecl AND eleve.numel=stage.numel AND classe.nom='$nomcl' AND promotion='$promo'");
    ?><center><table id="rounded-corner">
<thead><tr><?php echo Edition(); ?>
<th class="<?php echo rond(); ?>">Nom de l'étudiant</th>
<th class="rounded-q2">Prenom</th>
<th class="rounded-q2">Classe</th>
<th class="rounded-q2">Promotion</th>
<th class="rounded-q2">Date de début</th>
<th class="rounded-q2">Date de fin</th>
<th class="rounded-q4">Lieu du stage</th></tr></thead>
<tfoot>
<tr>
<td colspan="<?php echo colspan(6, 8); ?>" class="rounded-foot-left"><em>&nbsp;</em></td>
<td class="rounded-foot-right">&nbsp;</td>
</tr>
</tfoot>
<tbody>
<?php
while ($a = mysqli_fetch_array($donnee)) {
    if (isset($_SESSION['admin'])) {
        echo '<td><a href="ajout_stage.php?modif_stage='.$a['numstage'].'">Modifier</a></td><td><a href="supp_stage.php?supp_stage='.$a['numstage'].'" onclick="return(confirm(\'Etes-vous sûr de vouloir supprimer cette entrée?\'));">Supprimer</a></td>';
    }
    echo '<td>'.$a['nomel'].'</td><td>'.$a['prenomel'].'</td><td>'.$a['nom'].'</td><td>'.$a['promotion'].'</td><td>'.$a['date_debut'].'</td><td>'.$a['date_fin'].'</td><td>'.$a['lieu_stage'].'</td></tr>';
}
?>
</tbody>
</table></center>
<?php
} else { ?>
<form method="post" action="afficher_stage.php" class="formulaire">
Veuillez choisir la classe et la promotion :<br/><br/>
Promotion : <select name="promotion">
<?php while ($a = mysqli_fetch_array($data)) {
    echo '<option value="'.$a['promotion'].'">'.$a['promotion'].'</option>';
}?></select><br/><br/>
Classe : <select name="nomcl">
<?php while ($a = mysqli_fetch_array($retour)) {
    echo '<option value="'.$a['nom'].'">'.$a['nom'].'</option>';
}?></select><br/><br/>
<input type="submit" value="Afficher les stages">
</form>
<?php } ?>
<br/><br/><a href="afficher_stage.php">Revenir à la page précédente !</a>
</pre></center>
</div>
</body>
</html>
