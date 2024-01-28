<?php
session_start();
include('cadre.php');
if(isset($_SESSION['admin']) or isset($_SESSION['etudiant']) or isset($_SESSION['prof'])){
echo '<div class="corp">';
// echo '<img src="titre_img/cherche_eleve.png" class="position_titre"><center>';
if(isset($_GET['cherche_eleve'])){ 
$retour=mysqli_query($conn,"select distinct nom from classe"); // afficher les classes
$data=mysqli_query($conn,"select distinct promotion from classe order by promotion desc");
?>
<pre>
<form action="chercher_eleve.php" method="post" class="formulaire">
   <FIELDSET>
 <LEGEND align=top>Crit�re de recherche<LEGEND>  <pre>
Nom          :        <input type="text" name="nomel"><br/><br/>
Prenom      :       <input type="text" name="prenomel"><br/><br/>
vous pouvez pr�ciser la promotion si vous voulez : <br/><select name="promotion"> 
<option value="">Choisir la promotion</option>
<?php while($a=mysql_fetch_array($data)){
echo '<option value="'.$a['promotion'].'">'.$a['promotion'].'</option>';
}?></select><br/><br/>
Vous pouvez pr�ciser la classe si vous voulez: <br/><select name="nomcl"> 
<option value="">Choisir la classe</option>
<?php while($a=mysql_fetch_array($retour)){
echo '<option value="'.$a['nom'].'">'.$a['nom'].'</option>';
}?></select><br/>
<center><input type="image" src="chercher.png"></center>
</pre></fieldset>
</form><a href="index.php">Revenir � la page principale!</a>
<?php
}
else if(isset($_POST['nomel'])){
	$nomel=$_POST['nomel'];
	$prenomel=$_POST['prenomel'];
	$nomcl=$_POST['nomcl'];
	$promo=$_POST['promotion'];
	$option="";
	if($nomcl!="" and $promo=="")
	$option="and eleve.codecl in (select codecl from classe where nom='$nomcl')";
	else if($promo!="" and $nomcl=="")
	$option="and eleve.codecl in (select codecl from classe where promotion='$promo')";
	else if($promo!="" and $nomcl!="")
	$option="and eleve.codecl=(select codecl from classe where nom='$nomcl' and promotion='$promo')";
	$cherche=mysql_query("select * from eleve,classe where classe.codecl=eleve.codecl and nomel LIKE '%$nomel%' and prenomel LIKE '%$prenomel%' ".$option."");//option contient les info suplimentaire
?>
<table id="rounded-corner">
<thead><tr><th class="rounded-company">Nom</th>
<th class="rounded-q1">Prenom</th>
<th class="rounded-q3">Adresse</th>
<th class="rounded-q3">Date de naissance</th>
<th class="rounded-q3">Telepohne</th>
<th class="rounded-q3">Classe</th>
<th class="rounded-q4">Promotion</th></tr></thead>
<tfoot>
<tr><td colspan="6" class="rounded-foot-left"><em>&nbsp;</em></td>
<td class="rounded-foot-right">&nbsp;</td></tr>
</tfoot>
 <tbody>
 <?php
	while($a=mysql_fetch_array($cherche)){
		echo '<tr><td>'.$a['nomel'].'</td><td>'.$a['prenomel'].'</td><td >'.$a['adresse'].'</td><td >'.$a['date_naissance'].'</td><td >'.$a['telephone'].'</td><td>'.$a['nom'].'</td><td>'.$a['promotion'].'</td></tr>';
	}
	?>
	</tbody>
	</table>
	<a href="chercher_eleve.php?cherche_eleve=true">Revenir � la page precedente !</a>
	<?php
	}
}
?>
</div>
</pre>
</center>
</body>
</html>