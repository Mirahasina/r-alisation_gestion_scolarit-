<?php
session_start();
include('cadre.php');
?>
<div class="corp">
    <!-- <img src="titre_img/affich_eleve.png" class="position_titre"> -->
    <pre>
        <?php
        if (isset($_GET['nomcl'])) {// affichage de la promotion
            $nomcl = $_GET['nomcl'];
            $_SESSION['nomcl'] = $_GET['nomcl']; // session du nomcl choisi dans le menu pour laisser la variable jusqu'à la page où on va afficher la liste
            $data = mysqli_query($conn, "SELECT promotion FROM classe WHERE nom='$nomcl' ORDER BY promotion DESC");
            ?>
            <form action="listeEtudiant.php" method="POST" class="formulaire">
                Veuillez choisir la Promotion pour la classe <?php echo $_GET['nomcl']; ?>: <br/><br/>
                <FIELDSET>
                    <LEGEND align=top>Promotion <LEGEND>  <pre>
                        <select name="promotion">
                            <?php while ($a = mysqli_fetch_array($data)) {
                                echo '<option value="' . $a['promotion'] . '">' . $a['promotion'] . '</option>';
                            } ?></select><br/><br/>
                        <input type="submit" value="afficher">
                </pre></fieldset>
            </form>
            <br/><br/><a href="index.php?">Revenir à la page précédente !</a>
            <?php
        }
        if (isset($_POST['promotion'])) {
            $nomcl = $_SESSION['nomcl'];
            $promo = mysqli_real_escape_string($conn, $_POST['promotion']);
            $donnee = mysqli_query($conn, "SELECT numel, nomel, prenomel, date_naissance, adresse, telephone, eleve.codecl, nom, promotion FROM eleve, classe WHERE eleve.codecl=classe.codecl AND nom='$nomcl' AND promotion='$promo'");
            ?>
            <center><table id="rounded-corner">
                    <thead><?php echo Edition(); ?><th class="<?php echo rond(); ?>">Nom</th>
                        <th class="rounded-q2">Prenom</th>
                        <th class="rounded-q2">date de naissance</th>
                        <th class="rounded-q2">Adresse</th>
                        <th class="rounded-q2">Telephone</th>
                        <th class="rounded-q2">classe</th>
                        <th class="rounded-q2">Promotion</th>
                        <th class="rounded-q4">Ses enseignants</th></thead>
                    <tfoot>
                    <tr>
                        <td colspan="<?php echo colspan(7, 9); ?>" class="rounded-foot-left"><em>&nbsp;</em></td>
                        <td class="rounded-foot-right">&nbsp;</td>
                    </tr>
                    </tfoot>
                    <tbody>
                    <?php
                    while ($a = mysqli_fetch_array($donnee)) {
                        ?>
                        <tr><?php if (isset($_SESSION['admin']) or isset($_SESSION['etudiant']) or isset($_SESSION['prof'])) {
                                echo '<td><a href="modif_eleve.php?modif_el=' . $a['numel'] . '">modifier</a></td>
                                      <td><a href="modif_eleve.php?supp_el=' . $a['numel'] . '" onclick="return(confirm(\'Etes-vous sûr de vouloir supprimer cette entrée?\ntous les enregistrements en relation avec cette entrée seront perdus\'));">supprimer</a></td>';
                            }
                            echo '<td>' . $a['nomel'] . '</td><td>' . $a['prenomel'] . '</td><td>' . $a['date_naissance'] . '</td><td>' . $a['adresse'] . '</td><td>' . $a['telephone'] . '</td><td>' . $a['nom'] . '</td><td>' . $a['promotion'] . '</td><td><a href="listeEtudiant.php?voir_ensei=' . $a['numel'] . '">Voir ses enseignants</a></td></tr>';
                    }
                    ?>
                    <tbody>
            </table></center>
            <?php
            echo '<br/><br/><a href="listeEtudiant.php?nomcl=' . $nomcl . '">Revenir à la page précédente !</a>';
        }
        if (isset($_GET['voir_ensei'])) {
            $id = $_GET['voir_ensei'];
            $data = mysqli_query($conn, "SELECT prof.nom, prenom, nomel, prenomel, classe.nom AS nomcl, numsem, nommat, prof.adresse, promotion FROM prof, matiere, classe, eleve, enseignement WHERE prof.numprof=enseignement.numprof AND enseignement.codemat=matiere.codemat AND eleve.codecl=classe.codecl AND classe.codecl=enseignement.codecl AND numel='$id'");
            ?>
            <h2>Les enseignants de l'étudiant choisi : </h2><br/>
            <center><table id="rounded-corner">
                    <thead><th class="rounded-company">Nom d'etudiant</th>
                        <th class="rounded-q2">Prenom</th>
                        <th class="rounded-q2">Classe</th>
                        <th class="rounded-q2">promotion</th>
                        <th class="rounded-q2">Nom et prénom d'enseignant</th>
                        <th class="rounded-q2">Semestre</th>
                        <th class="rounded-q4">Matière</th></thead>
                    <tfoot>
                    <tr>
                        <td colspan="6" class="rounded-foot-left"><em>&nbsp;</em></td>
                        <td class="rounded-foot-right">&nbsp;</td>
                    </tr>
                    </tfoot>
                    <tbody>
                    <?php
                    while ($a = mysqli_fetch_array($data)) {
                        ?>
                        <tr><?php
                            echo '<td>' . $a['nomel'] . '</td><td>' . $a['prenomel'] . '</td><td>' . $a['nomcl'] . '</td><td>' . $a['promotion'] . '</td><td>' . $a['nom'] . ' ' . $a['prenom'] . '</td><td>' . $a['numsem'] . '</td><td>' . $a['nommat'] . '</td></tr>';
                    }
                    ?>
                    <tbody>
            </table></center> <?php
        }

        ?>
    </pre>
</div>
