<?php
session_start();
require "fonctions.php";
requireAdmin(); // Sécurité : Seul l'admin peut voir cette page
$pdo = getDB();

$message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    // 1. Récupération
    $nom = trim($_POST['nom']);
    $email = trim($_POST['email']);
    $adresse = trim($_POST['adresse']);
    $password = $_POST['password'];
    $confirm = $_POST['confirm_password'];
    $role_id = $_POST['role_id']; // L'admin peut choisir le rôle tout de suite

    // 2. Validations
    if (empty($nom) || empty($email) || empty($adresse) || empty($password) || empty($confirm)) {
        $message = "Tous les champs sont obligatoires.";
    }
    elseif ($password !== $confirm) {
        $message = "Les mots de passe ne correspondent pas.";
    }
    elseif (emailExiste($pdo, $email)) {
        $message = "Cet email est déjà utilisé.";
    }
    else {
        // 3. Création (Insertion manuelle pour inclure le role_id)
        $passwordHash = password_hash($password, PASSWORD_DEFAULT);
        
        // On fait une requête spécifique ici pour inclure le rôle
        $stmt = $pdo->prepare("INSERT INTO users (nom, email, adresse, password, role_id) VALUES (?, ?, ?, ?, ?)");
        
        if ($stmt->execute([$nom, $email, $adresse, $passwordHash, $role_id])) {
            // Succès : on retourne à la liste des utilisateurs
            header("Location: admin.php");
            exit;
        } else {
            $message = "Erreur lors de la création.";
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
    <title>Ajouter un utilisateur</title>
</head>
<body>
    <?php include 'header.php'; ?>
    <div class="container">

        <h2>Ajouter un nouvel utilisateur</h2>
        <a href="admin.php">Retour au tableau de bord</a>
        <br><br>

        <?php if ($message): ?>
            <p style="color:red;"><?php echo $message; ?></p>
        <?php endif; ?>

        <form method="POST">
            <label>Nom :</label><br>
            <input type="text" name="nom" required><br><br>

            <label>Email :</label><br>
            <input type="email" name="email" required><br><br>

            <div class="address-group">
                <label>Adresse :</label>
                <input type="text" name="adresse" id="addressInput" placeholder="Tapez une adresse..." autocomplete="off" required>
                <ul id="suggestions"></ul>
            </div>

            <label>Rôle :</label><br>
            <select name="role_id">
                <option value="2">Utilisateur (Standard)</option>
                <option value="1">Administrateur</option>
            </select>
            <br><br>

            <label>Mot de passe :</label><br>
            <input type="password" name="password" required><br><br>

            <label>Confirmer mot de passe :</label><br>
            <input type="password" name="confirm_password" required><br><br>

            <button type="submit">Créer l'utilisateur</button>
        </form>

    </div>
    <script>
    const input = document.getElementById('addressInput');
    const suggestions = document.getElementById('suggestions');

    input.addEventListener('input', async () => {
        const query = input.value.trim();

        if (query.length < 3) {
            suggestions.innerHTML = '';
            return;
        }

        try {
            const response = await fetch(`https://api-adresse.data.gouv.fr/search/?q=${encodeURIComponent(query)}&limit=5`);
            const data = await response.json();

            suggestions.innerHTML = '';
            data.features.forEach(feature => {
                const li = document.createElement('li');
                li.textContent = feature.properties.label;
                li.addEventListener('click', () => {
                    input.value = feature.properties.label;
                    suggestions.innerHTML = '';
                });
                suggestions.appendChild(li);
            });
        } catch (error) {
            console.error('Erreur API BAN:', error);
        }
    });

    // Petite astuce en plus : cacher la liste si on clique ailleurs
    document.addEventListener('click', (e) => {
        if (e.target !== input && e.target !== suggestions) {
            suggestions.innerHTML = '';
        }
    });
    </script>
</body>
</html>