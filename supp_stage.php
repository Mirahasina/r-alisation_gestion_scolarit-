<?php
session_start();
include('cadre.php');
// mysql_connect("localhost", "root", "");
// mysql_select_db("gestion");
include_once('config.php');
?>
<html>
<div class="corp">
    <center><h1>Suppression du stage</h1></center>
    <div class="formulaire">
        <?php
        if(isset($_GET['supp_stage'])){
            $id = intval($_GET['supp_stage']); // Convertir en entier pour des raisons de sécurité

            // Validation supplémentaire pour s'assurer que $id est un entier positif
            if ($id > 0) {
                mysql_query("DELETE FROM stage WHERE numstage='$id'");
                echo '<h1>Suppression avec succès ! </h1>';
                echo '<br/><br/><a href="index.php">Revenir à la page d\'accueil !</a>';
            } else {
                echo '<h1>Erreur : ID de stage non valide ! </h1>';
                echo '<br/><br/><a href="index.php">Revenir à la page d\'accueil !</a>';
            }
        }
        ?>
    </div>
</div>
</html>
