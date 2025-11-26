<?php
session_start();
require "fonctions.php";

// 1. Sécurité : Seul l'admin passe !
requireAdmin();
$pdo = getDB();

$message = "";
// 2. Traitement de la suppression d'un utilisateur
if (isset($_POST['supprimer_id'])) {
    $id_a_supprimer = $_POST['supprimer_id'];
    
    // On empêche l'admin de se supprimer lui-même (sécurité bête mais utile)
    if ($id_a_supprimer == $_SESSION['user_id']) {
        $message = "Vous ne pouvez pas supprimer votre propre compte ici.";
    } else {
        $stmt = $pdo->prepare("DELETE FROM users WHERE id = ?");
        $stmt->execute([$id_a_supprimer]);
        $message = "Utilisateur supprimé avec succès.";
    }
}

// 3. Récupérer TOUS les utilisateurs pour les afficher
// On fait une requête simple.
$stmt = $pdo->query("SELECT * FROM users ORDER BY id DESC");
$users = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Administration</title>
</head>
<body>
    <div class="container">


        <h2>Interface Administrateur</h2>
        <p>Bienvenue <strong><?php echo htmlspecialchars($_SESSION['user_nom']); ?></strong>.</p>
        <a href="profil.php">Retour au profil</a> | <a href="logout.php">Se déconnecter</a>
        <br><br>

        <a href="admin_add.php" class="btn-blue">Ajouter un nouvel utilisateur</a><br><br>

        <?php if ($message): ?>
            <p style="color: green; font-weight: bold;"><?php echo $message; ?></p>
        <?php endif; ?>

        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nom</th>
                    <th>Email</th>
                    <th>Adresse</th>
                    <th>Rôle</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($users as $u): ?>
                <tr>
                    <td><?php echo $u['id']; ?></td>
                    <td><?php echo htmlspecialchars($u['nom']); ?></td>
                    <td><?php echo htmlspecialchars($u['email']); ?></td>
                    <td><?php echo htmlspecialchars($u['adresse']); ?></td>
                    <td>
                        <?php echo ($u['role_id'] == 1) ? '<strong>ADMIN</strong>' : 'Utilisateur'; ?>
                    </td>
                    <td>
                        <a href="edit_user.php?id=<?php echo $u['id']; ?>" class="btn-blue">Modifier</a>
                        
                        <?php if ($u['id'] != $_SESSION['user_id']): ?>
                            <form method="POST" style="display:inline;" onsubmit="return confirm('Confirmer la suppression ?');">
                                <input type="hidden" name="supprimer_id" value="<?php echo $u['id']; ?>">
                                <button type="submit" class="btn-red">X</button>
                            </form>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

    </div>
</body>
</html>