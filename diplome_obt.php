<?php
session_start();
include('cadre.php');
?>
<html>
<body>
<div class="corp">
    <!-- <img src="titre_img/obt_diplome.png" class="position_titre"> -->
    <center>
        <pre>
            <?php
            if (isset($_POST['nomcl']) and isset($_POST['promotion'])) {
                $nomcl = mysqli_real_escape_string($conn, $_POST['nomcl']);
                $promo = mysqli_real_escape_string($conn, $_POST['promotion']);

                $donnee = mysqli_query($conn, "SELECT id, titre_dip, nomel, prenomel, nom, promotion, note, commentaire, etablissement, lieu, annee_obtention FROM eleve,eleve_diplome,classe,diplome WHERE diplome.numdip=eleve_diplome.numdip AND classe.codecl=eleve.codecl AND eleve.numel=eleve_diplome.numel AND classe.nom='$nomcl' AND promotion='$promo'");

                echo '<center><table id="rounded-corner">
                        <thead><tr>' . Edition() . '
                        <th class="' . rond() . '">Nom</th>
                        <th class="rounded-q2">Prenom</th>
                        <th class="rounded-q2">Classe</th>
                        <th class="rounded-q2">Promo</th>
                        <th class="rounded-q2">Titre_dip</th>
                        <th class="rounded-q2">Note</th>
                        <th class="rounded-q2">Commentaire</th>
                        <th class="rounded-q2">Etablissement</th>
                        <th class="rounded-q2">Lieu</th>
                        <th class="rounded-q4">Ann�e_obtention</th></tr></thead>
                        <tfoot>
                        <tr><td colspan="' . colspan(9, 11) . '" class="rounded-foot-left"><em>&nbsp;</em></td>
                        <td class="rounded-foot-right">&nbsp;</td></tr>
                        </tfoot>
                        <tbody>';

                while ($a = mysqli_fetch_array($donnee)) {
                    if (isset($_SESSION['admin'])) {
                        echo '<td><a href="modif_diplome.php?modif_dip=' . $a['id'] . '">modifier</a></td>
                              <td><a href="modif_diplome.php?supp_dip=' . $a['id'] . '" onclick="return(confirm(\'Etes-vous s�r de vouloir supprimer cette entr�e?\'));">Supprimer</td>';
                    }
                    echo '<td>' . $a['nomel'] . '</td><td>' . $a['prenomel'] . '</td><td>' . $a['nom'] . '</td><td>' . $a['promotion'] . '</td>
                          <td>' . $a['titre_dip'] . '</td><td>' . $a['note'] . '</td><td>' . $a['commentaire'] . '</td><td>' . $a['etablissement'] . '</td>
                          <td>' . $a['lieu'] . '</td><td>' . $a['annee_obtention'] . '</td></tr>';
                }
                echo '</tbody></table></center><br/><br/><a href="diplome_obt.php">Revenir � la page precedente </a>';
            } else {
                $data = mysqli_query($conn, "SELECT DISTINCT promotion FROM classe ORDER BY promotion DESC");
                $retour = mysqli_query($conn, "SELECT DISTINCT nom FROM classe");
                ?>
                <form method="post" action="diplome_obt.php" class="formulaire">
                    Veuillez choisir la classe et la promotion :<br/><br/>
                    Promotion       :       <select name="promotion">
                        <?php while ($a = mysqli_fetch_array($data)) {
                            echo '<option value="' . $a['promotion'] . '">' . $a['promotion'] . '</option>';
                        } ?></select><br/><br/>
                    Classe              :       <select name="nomcl">
                        <?php while ($a = mysqli_fetch_array($retour)) {
                            echo '<option value="' . $a['nom'] . '">' . $a['nom'] . '</option>';
                        } ?></select><br/><br/>
                    <input type="submit" value="Afficher les stages">
                </form>
                <br/><br/><a href="index.php">Revenir � la page principale </a>
            <?php } ?>
        </pre>
    </center>
</div>
</body>
</html>
