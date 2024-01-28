<?php
session_start();
include('cadre.php');
include_once('config.php');

// $host = "localhost";
// $user = "root";
// $password = "";
// $database = "gestion";

// $conn = new mysqli($host, $user, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

echo '<div class="corp">';
if (isset($_GET['modif_matiere'])) {
    $id = $_GET['modif_matiere'];
    $query = "SELECT * FROM matiere,classe WHERE classe.codecl=matiere.codecl AND codemat='$id'";
    $result = $conn->query($query);

    if ($result->num_rows > 0) {
        $ligne = $result->fetch_assoc();
        $nom = stripslashes($ligne['nommat']);
        $codecl = stripslashes($ligne['codecl']);
        $promo = $conn->query("SELECT promotion, nom FROM classe WHERE codecl='$codecl'");
        $promoData = $promo->fetch_assoc();
?>

<center><h1>Modifier une matière</h1></center>
<form action="modif_matiere.php" method="POST" class="formulaire">
    Matière: <input type="text" name="nommat" value="<?php echo $nom; ?>"><br/><br/>
    Classe: <?php echo $promoData['nom']; ?><br/><br/>
    Promotion: <?php echo $promoData['promotion']; ?><br/><br/>
    <input type="hidden" name="id" value="<?php echo $id; ?>">
    <center><input type="image" src="button.png"></center>
</form>

<?php
        echo '<br/><br/><a href="affiche_matiere.php?nomcl=' . $promoData['nom'] . '">Revenir à la page précédente !</a>';
    }
}

if (isset($_POST['nommat'])) {
    if ($_POST['nommat'] != "") {
        $id = $_POST['id'];
        $nom = $conn->real_escape_string(htmlspecialchars($_POST['nommat']));
        $conn->query("UPDATE matiere SET nommat='$nom' WHERE codemat='$id'");
?>
        <script language="JavaScript"> alert("Modifié avec succès!"); </script>
<?php
    } else {
?>
        <script language="JavaScript"> alert("Erreur! Vous devez remplir tous les champs"); </script>
<?php
    }

    echo '<br/><br/><a href="modif_matiere.php?modif_matiere=' . $id . '">Revenir à la page précédente !</a>';
}

if (isset($_GET['supp_matiere'])) {
    $id = $_GET['supp_matiere'];
    $conn->query("DELETE FROM matiere WHERE codemat='$id'");
?>
    <script language="JavaScript"> alert("Supprimé avec succès!"); </script>
<?php
    echo '<br/><br/><a href="index.php">Revenir à la page principale!</a>';
}

$conn->close();
?>
</div>
