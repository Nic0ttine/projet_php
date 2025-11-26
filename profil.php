<?php
session_start();
require "fonctions.php";

// On vérifie que la personne est connectée, sinon on la vire
requireLogin();

$pdo = getDB();

// On récupère les infos fraîches de l'utilisateur connecté
// (On utilise l'ID stocké en session pour faire la requête)
$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$_SESSION['user_id']]);
$user = $stmt->fetch();

// Traitement de la suppression du compte
if (isset($_POST['supprimer_compte'])) {
    $stmt = $pdo->prepare("DELETE FROM users WHERE id = ?");
    $stmt->execute([$_SESSION['user_id']]);

    // Une fois supprimé, on détruit la session et on redirige
    session_destroy();
    header("Location: register.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Mon Profil</title>
</head>
<body>
    <div class="container">

        <h2>Bienvenue sur votre espace, <?php echo htmlspecialchars($user['nom']); ?> !</h2>
        <!--OBLIGATOIRE pour la sécurité (évite les failles XSS si quelqu'un a mis du script dans son nom).-->

        <h3>Vos informations :</h3>
        <ul>
            <li><strong>Email :</strong> <?php echo htmlspecialchars($user['email']); ?></li>
            <li><strong>Adresse :</strong> <?php echo htmlspecialchars($user['adresse']); ?></li>
            <li><strong>Rôle :</strong> <?php echo ($user['role_id'] == 1) ? 'Administrateur' : 'Utilisateur'; ?></li>
        </ul>

        <br>
        <a href="logout.php">Se déconnecter</a>

        <hr>
    
        <h3>Zone de danger</h3>
        <p>Vous pouvez supprimer définitivement votre compte.</p>
        <form method="POST" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer votre compte ?');">
            <button type="submit" name="supprimer_compte" style="color: red;">Supprimer mon compte</button>
        </form>
    </div>
</body>
</html>