<?php
session_start();
include('cadre.php');
// $conn = mysqli_connect("localhost", "root", "", "gestion");
include_once('config.php');

if (!$conn) {
    die("Échec de la connexion : " . mysqli_connect_error());
}

?>
<div class="corp">
    <!-- <img src="titre_img/affich_classe.png" class="position_titre"> -->
    <center>
        <?php
        $query = "SELECT codecl, classe.nom AS nomcl, promotion, prof.nom AS nomprof FROM classe, prof WHERE classe.numprofcoord = prof.numprof";
        $result = mysqli_query($conn, $query);
        ?>
        <table id="rounded-corner">
            <thead>
                <tr>
                    <?php echo Edition(); ?>
                    <th class="<?php echo rond(); ?>">Nom de la classe</th>
                    <th class="rounded-q1">Promotion</th>
                    <th class="rounded-q4">Prof coordinataire</th>
                </tr>
            </thead>
            <tfoot>
                <tr>
                    <td colspan="<?php echo colspan(2, 4); ?>" class="rounded-foot-left"><em>&nbsp;</em></td>
                    <td class="rounded-foot-right">&nbsp;</td>
                </tr>
            </tfoot>
            <tbody>
                <?php
                while ($a = mysqli_fetch_array($result)) {
                ?>
                    <tr>
                        <?php if (isset($_SESSION['admin'])) { ?>
                            <td><a href="modif_classe.php?modif_classe=<?php echo $a['codecl']; ?>">modifier</a></td>
                            <td><a href="modif_classe.php?supp_classe=<?php echo $a['codecl']; ?>" onclick="return(confirm('Êtes-vous sûr de vouloir supprimer cette entrée?\nTous les enregistrements en relation avec cette entrée seront perdus'));">supprimer</a></td>
                        <?php } ?>
                        <td><?php echo $a['nomcl']; ?></td>
                        <td><?php echo $a['promotion']; ?></td>
                        <td><?php echo $a['nomprof']; ?></td>
                    </tr>
                <?php
                }
                ?>
            </tbody>
        </table>
        <?php
        echo '<br/><br/><a href="index.php">Revenir à la page précédente !</a>';
        ?>
    </center>
</div>
</html>
