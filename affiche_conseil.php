<?php
session_start();
include('cadre.php');
// $conn = mysqli_connect("localhost", "root", "", "gestion");
include_once('config.php');

if (!$conn) {
    die("Échec de la connexion : " . mysqli_connect_error());
}

$data = mysqli_query($conn, "SELECT DISTINCT promotion FROM classe ORDER BY promotion DESC");
$retour = mysqli_query($conn, "SELECT DISTINCT nom FROM classe");

?>
<div class="corp">
    <!-- <img src="titre_img/affiche_conseil.png" class="position_titre"> -->
    <center>
        <pre>
            <?php
            if (isset($_GET['supp_conseil'])) {
                $id = $_GET['supp_conseil'];
                mysqli_query($conn, "DELETE FROM conseil WHERE id='$id'");
            ?>
                <script language="javascript">alert("Supprimé avec succès!");</script>
            <?php
            } else if (isset($_POST['nomcl']) && isset($_POST['numsem'])) {
                $nomcl = $_POST['nomcl'];
                $promo = $_POST['promotion'];
                $numsem = $_POST['numsem'];
                $donnee = mysqli_query($conn, "SELECT * FROM classe, conseil WHERE classe.codecl=conseil.codecl AND classe.codecl=(SELECT codecl FROM classe WHERE nom='$nomcl' AND promotion='$promo') AND numsem='$numsem'");

                echo '<center><table id="rounded-corner">
                        <thead><tr>';
                if (isset($_SESSION['admin'])) {
                    echo '<th class="rounded-company">Supprimer</th>';
                }
                echo '<th class="' . rond() . '">Semestre</th>
                      <th class="rounded-q4">Classe</th>
                      </tr></thead>
                      <tfoot>
                          <tr>
                              <td colspan="' . colspan(1, 2) . '" class="rounded-foot-left"><em>&nbsp;</em></td>
                              <td class="rounded-foot-right">&nbsp;</td>
                          </tr>
                      </tfoot>
                      <tbody>';
                while ($a = mysqli_fetch_array($donnee)) {
                    if (isset($_SESSION['admin'])) {
                        echo '<tr><td><a href="affiche_conseil.php?supp_conseil=' . $a['id'] . '" onclick="return(confirm(\'Êtes-vous sûr de vouloir supprimer cette entrée?\'));">Supprimer</a></td>';
                    }
                    echo '<td>S' . $a['numsem'] . '</td><td>' . $a['nom'] . '</td></tr>';
                }
                echo '</tbody>
                    </table></center>';
            } else {
            ?>

                <form method="post" action="affiche_conseil.php" class="formulaire">
                    Veuillez choisir la classe et la promotion :<br /><br />
                    Classe : <select name="nomcl">
                        <?php
                        while ($a = mysqli_fetch_array($retour)) {
                            echo '<option value="' . $a['nom'] . '">' . $a['nom'] . '</option>';
                        }
                        ?>
                    </select><br /><br />
                    Promotion : <select name="promotion">
                        <?php
                        while ($a = mysqli_fetch_array($data)) {
                            echo '<option value="' . $a['promotion'] . '">' . $a['promotion'] . '</option>';
                        }
                        ?>
                    </select><br />
                    Semestre : <select name="numsem">
                        <?php
                        for ($i = 1; $i <= 4; $i++) {
                            echo '<option value="' . $i . '">Semestre' . $i . '</option>';
                        }
                        ?>
                    </select><br /><br />
                    <input type="submit" value="Afficher les stages">
                </form>

            <?php
            }
            ?>
            <br /><br /><a href="affiche_conseil.php">Revenir à la page précédente !</a>
        </pre>
    </center>
</div>
</body>
</html>
