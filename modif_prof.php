<?php
session_start();
include('cadre.php');
include_once('config.php');
// $host = "localhost";
// $user = "root";
// $password = "";
// $database = "gestion";

// $conn = new mysqli($host, $user, $password, $database);
include_once('config.php');

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

echo '<div class="corp"><img src="titre_img/modif_prof.png" class="position_titre"><pre>';

if (isset($_GET['modif_prof'])) {
    $id = $_GET['modif_prof'];
    $stmt = $conn->prepare("SELECT * FROM prof WHERE numprof = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $ligne = $result->fetch_assoc();
        $nom = stripslashes($ligne['nom']);
        $prenom = stripslashes($ligne['prenom']);
        $phone = stripslashes($ligne['telephone']);
        $adresse = stripslashes($ligne['adresse']);
?>

        <form action="modif_prof.php" method="POST" class="formulaire">
            Nom professeur: <?php echo $nom; ?><br/><br/>
            Prénom professeur: <?php echo $prenom; ?><br/><br/>
            Adresse: <textarea name="adresse"><?php echo $adresse; ?></textarea><br/><br/>
            Téléphone: <input type="text" name="phone" value="<?php echo $phone; ?>"><br/><br/>
            <input type="hidden" name="id" value="<?php echo $id; ?>">
            <center><input type="image" src="button.png"></center>
        </form>
        <br/><br/><a href="afficher_prof.php?nomcl=<?php echo $ligne['nom']; ?>">Revenir à la page précédente !</a>

<?php
    }
}

if (isset($_POST['adresse'])) {
    if ($_POST['adresse'] != "" && $_POST['phone'] != "") {
        $id = $_POST['id'];
        $phone = $conn->real_escape_string(htmlspecialchars($_POST['phone']));
        $adresse = $conn->real_escape_string(nl2br(htmlspecialchars($_POST['adresse'])));
        $stmt = $conn->prepare("UPDATE prof SET adresse = ?, telephone = ? WHERE numprof = ?");
        $stmt->bind_param("ssi", $adresse, $phone, $id);
        $stmt->execute();
?>
        <script language="JavaScript"> alert("Modifié avec succès!"); </script>
<?php
        echo '<br/><br/><a href="modif_prof.php?modif_prof=' . $id . '">Revenir à la page précédente !</a>';
    } else {
?>
        <script language="JavaScript"> alert("Erreur! Vous devez remplir tous les champs"); </script>
<?php
        echo '<br/><br/><a href="index.php?">Revenir à la page principale !</a>';
    }
}

if (isset($_GET['supp_prof'])) {
    $id = $_GET['supp_prof'];
    $stmt = $conn->prepare("DELETE FROM prof WHERE numprof = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
?>
    <script language="JavaScript"> alert("Supprimé avec succès!"); </script>
<?php
    echo '<br/><br/><a href="index.php?">Revenir à la page principale !</a>';
}

$conn->close();
?>
</pre>
</div>
