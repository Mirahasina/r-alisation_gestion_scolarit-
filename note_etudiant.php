<?php
session_start();
include('cadre.php');

if (isset($_SESSION['etudiant'])) {
    $id = $_SESSION['etudiant'];
    // $mysqli = new mysqli("localhost", "root", "", "gestion");
    include_once('config.php');

    if ($mysqli->connect_error) {
        die("Connection failed: " . $mysqli->connect_error);
    }

    $stmt = $mysqli->prepare("SELECT bulletin.numel, nomel, prenomel, nommat, numsem, promotion, notefinal, nom FROM matiere, bulletin, eleve, classe WHERE classe.codecl = eleve.codecl AND bulletin.numel = eleve.numel AND matiere.codemat = bulletin.codemat AND eleve.numel = ? ORDER BY numsem");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->bind_result($numel, $nomel, $prenomel, $nommat, $numsem, $promotion, $notefinal, $nom);

    ?>
    <div class="corp">
        <img src="titre_img/affich_stage.png" class="position_titre">
        <pre>
            <center>
                <table id="rounded-corner">
                    <thead>
                        <tr>
                            <th class="rounded-company">Nom</th>
                            <th class="rounded-q2">Prenom</th>
                            <th class="rounded-q2">Classe</th>
                            <th class="rounded-q2">Promotion</th>
                            <th class="rounded-q2">Matière</th>
                            <th class="rounded-q2">Note final</th>
                            <th class="rounded-q4">Semestre</th>
                        </tr>
                    </thead>
                    <tfoot>
                        <tr>
                            <td colspan="6" class="rounded-foot-left"><em>&nbsp;</em></td>
                            <td class="rounded-foot-right">&nbsp;</td>
                        </tr>
                    </tfoot>
                    <tbody>
                        <?php
                        while ($stmt->fetch()) {
                            echo '<tr><td>' . $nomel . '</td><td>' . $prenomel . '</td><td>' . $nom . '</td><td>' . $promotion . '</td><td>' . $nommat . '</td><td>' . $notefinal . '</td><td>S' . $numsem . '</td></tr>';
                        }
                        $stmt->close();
                        ?>
                    </tbody>
                </table>
            </center>
            <br/><br/><a href="index.php">Revenir à la page précédente !</a>
        </pre>
    </div>

    <?php
    $mysqli->close();
}
?>
</html>
