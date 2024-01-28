<?php
// Inclure le fichier de configuration
include_once("config.php");

//session_start();

/***** Vérification du mot de passe ****/
if (isset($_POST['mdp'])) {
    if ($_POST['mdp'] != "" and $_POST['pseudo'] != "") {
        $mdp = $_POST['mdp'];
        $pseudo = $_POST['pseudo'];
        $query = "select count(*) as nb,type,Num from login where pseudo='$pseudo' and passe='$mdp'";
        $result = mysqli_query($conn, $query); // Utiliser $conn au lieu de $connexion

        if ($result) {
            $nb = mysqli_fetch_array($result);

            if ($nb['nb'] == 1) {
                if ($nb['type'] == "etudiant")
                    $_SESSION['etudiant'] = $nb['Num'];
                else if ($nb['type'] == "prof")
                    $_SESSION['prof'] = $nb['Num'];
                else if ($nb['type'] == "admin")
                    $_SESSION['admin'] = $nb['Num'];
            } else {
                ?>
                <script language="Javascript">alert("Login ou mot de passe incorrect");</script>
                <?php
            }
        } else {
            echo mysqli_error($conn);
        }
    } else {
        ?>
        <script language="Javascript">alert("Vous devez remplir tous les champs!");</script>
        <?php
    }
} elseif (isset($_GET['sortir'])) {
    session_destroy();
    header("location:index.php");
}

Function colspan($min,$max){
if(isset($_SESSION['admin']))
	return $max;
else
	return $min;
}
Function rond(){
if(isset($_SESSION['admin']))
	return 'rounded-q1';	
else
	return 'rounded-company';
}
Function Edition(){
 if(isset($_SESSION['admin']))
 return '<th colspan="2" class="rounded-company">EDITION</th>';
 else
 return '';
}
Function Edition2(){//si on veut affcher edtition pour le prof aussi
 if(isset($_SESSION['admin']) or isset($_SESSION['prof']))
 return '<th colspan="2" class="rounded-company">EDITION</th>';
 else
 return '';
}
Function rond2(){//si on veut affcher edtition pour le prof aussi
if(isset($_SESSION['admin']) or isset($_SESSION['prof']))
	return 'rounded-q1';	
else
	return 'rounded-company';
}
Function colspan2($min,$max){//si on veut affcher edtition pour le prof aussi
if(isset($_SESSION['admin']) or isset($_SESSION['prof']))
	return $max;
else
	return $min;
}
Function choixpardefault2($var1,$var2)//pour selection l'element � modifier par defautl
{
if($var1==$var2)
return 'selected="selected"';
else
return "";
}

// mysql_connect("localhost", "root", "");
// mysql_select_db("gestion");
include_once("config.php");
$data = mysqli_query($conn, "select distinct nom from classe");
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
    <link rel="stylesheet" media="screen" type="text/css" title="Essai" href="style.css" />
    <link rel="stylesheet" media="screen" type="text/css" title="Essai" href="table.css" />
</head>
<body>
<div class="ombre"></div>
<div class="entete"><center></center></div>

<div class="menu">
&nbsp;&nbsp;&nbsp;<font>Comment gérer la scolarité de nos jous ? <img src="leader.png" alt=""></font><br/><br/>
</div>
<div id="monmenu" >
		<ul class="niveau1">
			<li><a href="1" class="fly">Etudiants </a>
				<ul class="niveau2">
					<li><a href="listeEtudiant.php?list=true">Consulter la liste</a>
						<ul class="niveau3">
						<?php $retour = mysqli_query($conn, "select distinct nom from classe");
							while($a=mysqli_fetch_array($retour)){
								echo '<li><a href="listeEtudiant.php?nomcl=' . $a['nom'] . '">' . $a['nom'] . '</a></li>';
			
							}
							?>
						</ul>
					</li>
					<?php if(isset($_SESSION['admin']) or isset($_SESSION['prof'])){/*!(isset($_SESSION['prof'])) and !(isset($_SESSION['public'])) and !(isset($_SESSION['etudiant']))*/
					echo '<li><a href="afficher_note.php">Consulter les notes</a>
							<ul class="niveau3">';
								while($nomcl=mysqli_fetch_array($data)){
								echo '<li><a href="afficher_note.php?nomcl='.$nomcl['nom'].'">'.$nomcl['nom'].'</a></li>';
								}
						echo '</ul>
						</li>';
				if(!isset($_SESSION['prof'])){ echo '<li><a href="Ajout_etudiant.php">Ajouter un etudiant</a></li>';
					} }
				if(isset($_SESSION['etudiant'])){ echo '<li><a href="note_etudiant.php">Consulter les notes</a></li>'; } ?>
					<li><a href="chercher_eleve.php?cherche_eleve=true">Chercher un �tudiant</a></li>
				</ul>
			</li>
			<li><a href="#">Enseignants</a>
				<ul class="niveau2" >
					<li><a href="afficher_prof.php">Liste des enseignants</a></li>
					<?php if((isset($_SESSION['admin'])) or (isset($_SESSION['prof']))){
					if(!isset($_SESSION['prof'])){echo '<li><a href="ajout_prof.php">Ajouter un enseignant</a></li>';}
					}
					?>	
					<li><a href="chercher_prof.php?cherche_prof=true">Chercher un enseignant</a></li>
				</ul>
			</li>
			<?php
$data = mysqli_query($conn, "select distinct nom from classe");
?>

<li><a href="#">Classes</a>
    <ul class="niveau2">
        <li><a href="affiche_classe.php">Voir les classes</a></li>

        <?php if (isset($_SESSION['admin'])) : ?>
            <li><a href="ajout_classe.php">Ajouter une classe</a></li>
        <?php endif; ?>
    </ul>
</li>

			
			<li><a href="#">Stages</a>
				<ul class="niveau2">
					<li><a href="afficher_stage.php">Voir les stages</a></li>
					<?php
					if (isset($_SESSION['admin'])) {
						echo '<li><a href="ajout_stage.php">Ajouter un stage</a></li>';
					}
					?>
					<li><a href="chercher_stage.php?cherche_stage=true">Chercher un stage</a></li>
				</ul>
			</li>
			
			<?php
			if (isset($_SESSION['admin']) or isset($_SESSION['prof'])) {
				echo '<li><a href="#">Conseil</a>
					<ul class="niveau2">';
				echo '<li><a href="affiche_conseil.php">Voir les conseils</a></li>';
				if (isset($_SESSION['admin'])) {
					echo '<li><a href="ajout_conseil.php">Ajouter un conseil</a></li>';
				}
				echo '</ul>
				</li>';
			}
			?>
			
			<li><a href="#">Matière </a>
				<ul class="niveau2">
					<li><a href="#">Voir les matières</a>
						<ul class="niveau3">
							<?php
							while ($nomcl = mysqli_fetch_array($data)) {
								echo '<li><a href="afficher_matiere.php?nomcl=' . $nomcl['nom'] . '">' . $nomcl['nom'] . '</a></li>';
							}
							?>
						</ul>
					</li>
					<?php
					if (isset($_SESSION['admin'])) {
						echo '<li><a href="ajout_matiere.php">Ajouter une matière</a></li>';
					}
					?>
				</ul>
			</li>
			
			<?php
			if (isset($_SESSION['admin']) or isset($_SESSION['prof'])) {
				echo '<li><a href="#">Bulletins</a>
					<ul class="niveau2">';
				// if (isset($_SESSION['admin'])) {
				//     echo '<li><a href="ajout_bulettin.php">Ajouter une note final</a></li>';
				// }
				echo '<li><a href="afficher_bulettin.php">note final d\'un etudiant</a></li>';
				echo '</ul>
				</li>';
			}
			?>
			
			<li><a href="#">Diplômes</a>
				<ul class="niveau2">
					<li><a href="type_diplome.php">Types de diplômes</a></li>
			<?php
			if (isset($_SESSION['admin']) or isset($_SESSION['prof'])) {
				echo '<li><a href="diplome_obt.php">Diplômes obtenus</a></li>';
			}
			if (isset($_SESSION['admin'])) {
				echo '<li><a href="ajout_diplome.php?ajout_type">Ajouter un nouveau type</a></li>
					<li><a href="ajout_diplome.php?ajout_diplome">Ajouter une obtention </a></li>';
			}
			?>
			</ul>
			</li>
			
		<?php if(isset($_SESSION['admin']) or isset($_SESSION['prof']))
			echo '<li><a href="#">Evalutation</a>
						<ul class="niveau2">
							<li><a href="ajout_eval.php">Ajouter une evaluation</a></li>
							<li><a href="afficher_evaluation.php">Voir les evalutations</a></li>
						</ul>
					</li>	
			<li><a href="ajout_devoir.php">Devoirs</a>
				<ul class="niveau2">
				<li><a href="ajout_devoir.php">Ajouter un devoir</a></li>
				<li><a href="afficher_devoir.php">Voir les devoirs</a></li>
				</ul>
			</li>';
		?>	
			<li><a href="#">Enseignement </a>
				<ul class="niveau2" >
					<li><a href="afficher_enseign.php">Voir les enseignement</a></li>
					<?php if(isset($_SESSION['admin'])){/*!(isset($_SESSION['prof'])) and !(isset($_SESSION['public'])) and !(isset($_SESSION['etudiant']))*/
					echo '<li><a href="ajout_enseignement.php">Ajouter un enseignement</a></li>';
					} ?>
				</ul>
			</li>		
		</ul>
	</div>
		<?php //} ?>
		</div>
	<div class="même">
	<div class="st">
		<p>Etudiants</p>
		<p class="th">Enseignement</p>
	</div>
	<div class="nd">
		<p>Classes</p>
		<p>Stages</p>
	</div>
	<div class="rd">
		<p>Matière</p>
		<p>Diplômes</p>
	</div>
	</div>
	<div class="foot">
		<p class="phrase">Offrons un avenir <br> meilleur aux enfants</p>
		<p class="phrasest">Enseignant</p>
	</div>
		
	<div class="all">	
		<img class="image" src="images.png" alt="image">
		<img class="imagest" src="download (3).png" alt="rien">
		<img class="imagend" src="download (2).png" alt="rien">
		<img class="imagerd" src="download (1).png" alt="rien">
	</div>
<div class="connexion2">
&nbsp;&nbsp;&nbsp;<font class="connecter">Connexion</font><br/><br/>
<?php if(!(isset($_SESSION['admin'])) and !isset($_SESSION['prof']) and !isset($_SESSION['etudiant'])){
?>

<form action="index.php" method="post">
<LEGEND align=top class="connecterst">Authentification<LEGEND>
<p   class="name">Pseudo :<br/><input type="text" name="pseudo" size="15" class="valider"></p>
<p  class="mdp">Mot de passe :<br/><input type="password" name="mdp" size="15" class=valider></p>
<br/><br/><input type="submit" value="envoyer" class="envoyer"><br/>
</form>
<?php
}
else
echo '<br/><br/><br/><li><a href="index.php?sortir=1">Deconexion</a></li>';
?>
</div>
<div class="terminer">
<div class="pied"><br/><center>&reg; 2010 Ecole supérieure de technologie de Berrechid. Gestion de scolarité </center></div><img src="image.png" alt="" class="fin"></div>