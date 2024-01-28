<?php
session_start();
include('cadre.php');
?>

<div class="corp">
    <!-- <img src="titre_img/type_diplome.png" class="position_titre"> -->
    <center>
        <pre>
            <?php
            $mysqli = new mysqli("localhost", "root", "", "gestion");

            // Vérification de la connexion
            if ($mysqli->connect_error) {
                die("La connexion a échoué : " . $mysqli->connect_error);
            }

            $query = "SELECT * FROM diplome";
            $result = $mysqli->query($query);

            echo '<center><table id="rounded-corner">';
            echo '<thead><tr>';
            if (isset($_SESSION['admin'])) {
                echo '<th class="rounded-company">Supprimer</th>';
            }
            echo '<th class="rounded-q1">Titre du diplôme</th></tr></thead>';
            echo '<tfoot><tr><td colspan="' . colspan(0, 2) . '" class="rounded-foot-left"><em>&nbsp;</em></td></tr></tfoot>';
            echo '<tbody>';

            while ($row = $result->fetch_assoc()) {
                echo '<tr>';
                if (isset($_SESSION['admin'])) {
                    echo '<td><a href="type_diplome.php?supp_type=' . $row['numdip'] . '" onclick="return(confirm(\'Etes-vous sûr de vouloir supprimer cette entrée?\'));">Supprimer</td>';
                }
                echo '<td>' . htmlspecialchars($row['titre_dip']) . '</td></tr>';
            }

            echo '</tbody></table></center>';

            // Fermer la connexion
            $mysqli->close();

            if (isset($_GET['supp_type'])) {
                // Validation de l'ID du diplôme à supprimer
                $id = intval($_GET['supp_type']);

                $mysqli = new mysqli("localhost", "root", "", "gestion");

                // Vérification de la connexion
                if ($mysqli->connect_error) {
                    die("La connexion a échoué : " . $mysqli->connect_error);
                }

                $query = "DELETE FROM diplome WHERE numdip = $id";
                $mysqli->query($query);

                // Fermer la connexion
                $mysqli->close();
            }
            ?>
        </pre>
    </center>
</div>
