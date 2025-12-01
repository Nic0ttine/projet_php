<?php
session_start(); // Obligatoire pour utiliser $_SESSION [cite: 25, 65]
require "fonctions.php";

$pdo = getDB();
$message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    if ($email === "" || $password === "") {
        die("Veuillez remplir tous les champs.");
    } else {
        // 1. On récupère l'utilisateur par son email
        $user = getUserByEmail($pdo, $email);

        // 2. Vérification du mot de passe
        if ($user && password_verify($password, $user['password'])) {
            // 3. ON STOCKE LES INFOS IMPORTANTES EN SESSION
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_nom'] = $user['nom'];
            $_SESSION['user_role'] = $user['role_id'];
            // 4. Redirection intelligente selon le rôle
            if ($user['role_id'] == 1) {
                header("Location: admin.php");
            } else {
                header("Location: profil.php");
            }
            exit;
        } else {
            $message = "Email ou mot de passe incorrect.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="style.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion</title>
</head>
<body>
    <?php include 'header.php'; ?>

    <div class="container"><?php include 'header.php'; ?>

    <div class="container">

        <h2>Connexion</h2>
    
        <?php if ($message): ?>
            <p style="color:red;"><?php echo $message; ?></p>
        <?php endif; ?>

        <form method="POST">
            <label>Email :</label><br>
            <input type="email" name="email" required><br><br>

            <label>Mot de passe :</label><br>
            <input type="password" name="password" required><br><br>

            <button type="submit">Se connecter</button>
        </form>
    
        <p>Pas encore de compte ? <a href="register.php">Inscrivez-vous</a></p>
    </div>
</body>
</html>