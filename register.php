<?php
session_start();
require "fonctions.php";

$pdo = getDB();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // 1. Récupération et nettoyage
    $nom = trim($_POST['nom']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
	$address = trim($_POST['address']);
    $confirm_password = $_POST['confirm_password'];

    // 2. Vérifications
    if ($nom === "" || $email === "" || $password === "" || $adress === "") {
        die("Tous les champs sont obligatoires.");
    }

    elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        die("Email invalide.");
    }

    // Regex pour le mail
    elseif (!preg_match("/^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/", $email)) {
        die("Format d'email invalide.");
    }

    // Regex pour le mot de passe (PDF: 8 chars, lettres + chiffres)
    elseif (!preg_match('/^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]{8,}$/', $password)) {
        die("Le mot de passe doit faire 8 caractères avec 1 lettre et 1 chiffre.");
    }
    elseif (emailExiste($pdo, $email)) {
        die("Cet email existe déjà.");
    }
    else {
        // 3. Inscription
        $passwordHash = password_hash($password, PASSWORD_DEFAULT);

        // On appelle la fonction mise à jour avec l'adresse
        if (creerUtilisateur($pdo, $nom, $email, $passwordHash, $address)) {
        echo "Inscription réussie. <a href='login.php'>Se connecter</a>";
        } else {
        echo "Erreur lors de l'inscription.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Inscription</title>
</head>
<body>
<h2>Inscription</h2>

<?php if ($message): ?>
    <p style="color:red;"><?php echo $message; ?></p>
<?php endif; ?>

<form method="POST">
    <label>Nom :</label><br>
    <input type="text" name="nom" required><br><br>
    
    <label>Email :</label><br>
    <input type="email" name="email" required><br><br>
	
    <label>Adresse :</label><br>
    <input type="text" name="adresse" required><br><br>
    
    <label>Mot de passe :</label><br>
    <input type="password" name="password" required><br><br>

    <label>Confirmer le mot de passe :</label><br>
    <input type="password" name="confirm_password" required><br><br>
    
    <button type="submit">S'inscrire</button>
</form>
</body>
</html>