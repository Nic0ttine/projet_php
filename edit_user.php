<?php
session_start();
require "fonctions.php";
requireAdmin(); // Sécurité max
$pdo = getDB();

$message = "";

// 1. Vérifier qu'on a bien un ID dans l'URL (ex: edit_user.php?id=5)
if (!isset($_GET['id'])) {
    die("ID utilisateur manquant.");
}

$id = $_GET['id'];

// 2. Si le formulaire est soumis (Mise à jour)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom = trim($_POST['nom']);
    $email = trim($_POST['email']);
    $role_id = $_POST['role_id']; // C'est ici qu'on change le grade !

    if (!empty($nom) && !empty($email)) {
        // Mise à jour SQL
        $stmt = $pdo->prepare("UPDATE users SET nom = ?, email = ?, role_id = ? WHERE id = ?");
        $stmt->execute([$nom, $email, $role_id, $id]);
        $message = "Utilisateur modifié avec succès !";
        // On redirige vers la liste après modification
        header("Location: admin.php"); 
        exit;
    } else {
        $message = "Veuillez remplir les champs.";
    }
}

// 3. Récupérer les infos actuelles de l'utilisateur pour pré-remplir le formulaire
$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$id]);
$user = $stmt->fetch();

if (!$user) {
    die("Utilisateur introuvable.");
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Modifier Utilisateur</title>
</head>
<body>

<h2>Modifier l'utilisateur : <?php echo htmlspecialchars($user['nom']); ?></h2>

<a href="admin.php">Retour à la liste</a>
<br><br>

<form method="POST">
    <label>Nom :</label><br>
    <input type="text" name="nom" value="<?php echo htmlspecialchars($user['nom']); ?>" required><br><br>

    <label>Email :</label><br>
    <input type="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required><br><br>

    <label>Rôle :</label><br>
    <select name="role_id">
        <option value="2" <?php if ($user['role_id'] == 2) echo 'selected'; ?>>Utilisateur</option>
        <option value="1" <?php if ($user['role_id'] == 1) echo 'selected'; ?>>Administrateur</option>
    </select>
    <br><br>

    <button type="submit">Enregistrer les modifications</button>
</form>

</body>
</html>