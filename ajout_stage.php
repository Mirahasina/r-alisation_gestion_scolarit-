<?php
session_start();
include('cadre.php');
include('calendrier.html');
include_once('config.php');
// $conn = mysqli_connect("localhost", "root", "", "gestion");

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

if (isset($_POST['adresse'])) {
    // si on a cliqué sur ajouter/modifier pour modifier le post pour ne pas entrer
    if (!empty($_POST['lieu']) && !empty($_POST['date_debut']) && !empty($_POST['date_fin'])) {
        $id = $_SESSION['modif_stage'];
        $date_debut = $_POST['date_debut'];
        $date_fin = $_POST['date_fin'];
        $lieu = $_POST['lieu'];

        mysqli_query($conn, "UPDATE stage SET lieu_stage='$lieu', date_debut='$date_debut', date_fin='$date_fin' WHERE numstage='$id'");
        ?>
        <SCRIPT LANGUAGE="Javascript">
            alert("Modification avec succès !");
        </SCRIPT>
        <?php
        unset($_SESSION['modif_stage']);
        echo '<br/><br/><a href="index.php">Revenir à la page d\'accueil !</a>';
    } else {
        ?>
        <SCRIPT LANGUAGE="Javascript">
            alert("Veuillez remplir tous les champs !");
        </SCRIPT>
        <?php
    }
} elseif (isset($_POST['lieu'])) {
    // s'il a cliqué sur ajouter / modifier la 2ème fois pour ajouter
    if (!empty($_POST['lieu']) && !empty($_POST['date_debut']) && !empty($_POST['date_fin'])) {
        $numel = $_POST['numel'];
        $date_debut = addslashes($_POST['date_debut']);
        $date_fin = addslashes($_POST['date_fin']);
        $lieu = addslashes($_POST['lieu']);

        $compte = mysqli_fetch_array(mysqli_query($conn, "SELECT COUNT(*) as nb FROM stage WHERE lieu_stage='$lieu' AND numel='$numel' AND date_debut='$date_debut' AND date_fin='$date_fin'"));
        $bool = true;

        if ($compte['nb'] > 0) {
            $bool = false;
            ?>
            <SCRIPT LANGUAGE="Javascript">
                alert("Erreur d'insertion, le stage existe déjà !");
            </SCRIPT>
            <?php
        }

        if ($bool) {
            mysqli_query($conn, "INSERT INTO stage(lieu_stage, date_debut, date_fin, numel) VALUES ('$lieu', '$date_debut', '$date_fin', '$numel')");
            ?>
            <SCRIPT LANGUAGE="Javascript">
                alert("Ajouté avec succès!");
            </SCRIPT>
            <?php
        }

        echo '<a href="index.php">Revenir à la page d\'accueil !</a>';
    } else {
        ?>
        <SCRIPT LANGUAGE="Javascript">
            alert("Veuillez remplir tous les champs !");
        </SCRIPT>
        <?php
        echo '<a href="index.php">Revenir à la page d\'accueil !</a>';
    }
} elseif (!isset($_POST['nomcl']) && !isset($_GET['modif_stage'])) {
    $data = mysqli_query($conn, "SELECT DISTINCT promotion FROM classe ORDER BY promotion DESC");
    $retour = mysqli_query($conn, "SELECT DISTINCT nom FROM classe");
    ?>
    <form action="ajout_stage.php" method="POST" class="formulaire">
        Veuillez choisir la classe et la promotion : <br/><br/>
        Promotion : <select name="promotion">
            <?php while ($a = mysqli_fetch_array($data)) {
                echo '<option value="' . $a['promotion'] . '">' . $a['promotion'] . '</option>';
            } ?>
        </select><br/><br/>
        Classe : <select name="nomcl">
            <?php while ($a = mysqli_fetch_array($retour)) {
                echo '<option value="' . $a['nom'] . '">' . $a['nom'] . '</option>';
            } ?>
        </select><br/><br/>
        <center><input type="submit" value="Suivant"></center>
    </form>
    <?php
} elseif ((isset($_POST['nomcl']) && isset($_POST['promotion'])) || isset($_GET['modif_stage'])) {
    $id = "";
    $lieu = "";
    $date_debut = "";
    $date_fin = "";

    if (isset($_GET['modif_stage'])) {
        $id = $_GET['modif_stage'];
        $_SESSION['modif_stage'] = $id;
        $donnee = mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM stage WHERE numstage='$id'"));
        $lieu = $donnee['lieu_stage'];
        $date_debut = $donnee['date_debut'];
        $date_fin = $donnee['date_fin'];
        $data = mysqli_fetch_array(mysqli_query($conn, "SELECT numel, nomel, prenomel FROM eleve WHERE numel=(SELECT numel FROM stage WHERE numstage='$id')"));
    } else {
        $_SESSION['promo'] = $_POST['promotion'];
        $promo = $_POST['promotion'];
        $nomcl = $_POST['nomcl'];
        $data = mysqli_query($conn, "SELECT numel, nomel, prenomel FROM eleve, classe WHERE classe.codecl=eleve.codecl AND nom='$nomcl' AND promotion='$promo'");
    }
    ?>
    <form action="ajout_stage.php" method="POST" class="formulaire">
        Eleve : <?php if (isset($_GET['modif_stage'])) {
            echo $data['nomel'] . ' ' . $data['prenomel'];
        } else {
            ?>
            <select name="numel">
                <?php while ($a = mysqli_fetch_array($data)) {
                    echo '<option value="' . $a['numel'] . '">' . $a['nomel'] . ' ' . $a['prenomel'] . '</option>';
                } ?>
            </select><br/><br/>
        <?php } ?>

        Lieu de stage : <input type="text" name="lieu" value="<?php echo $lieu; ?>"><br/><br/>
        Date de début : <input type="text" name="date_debut" class="calendrier" value="<?php echo $date_debut; ?>"><br/><br/>
        Date de fin : <input type="text" name="date_fin" class="calendrier" value="<?php echo $date_fin; ?>"><br/><br/>
        <center><input type="image" src="button.png"></center>
    </form>
<?php } ?>
</pre>
</center>
</div>
</html>
