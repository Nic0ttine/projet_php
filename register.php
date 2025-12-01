<?php
session_start();
require "fonctions.php";

$pdo = getDB();
$message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // 1. Récupération et nettoyage
    $nom = trim($_POST['nom']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
	$adresse = trim($_POST['adresse']);
    $confirm_password = $_POST['confirm_password'];

    // 2. Vérifications
    if ($nom === "" || $email === "" || $password === "" || $adresse === "") {
        $message = "Tous les champs sont obligatoires.";
    }

    elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $message = "Email invalide.";
    }

    // Regex pour le mail
    elseif (!preg_match("/^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/", $email)) {
        $message = "Format d'email invalide.";
    }

    // Regex pour le mot de passe (PDF: 8 chars, lettres + chiffres)
    elseif (!preg_match('/^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]{8,}$/', $password)) {
        $message = "Le mot de passe doit faire 8 caractères avec 1 lettre et 1 chiffre.";
    }
    elseif (emailExiste($pdo, $email)) {
        $message = "Cet email existe déjà.";
    }
    else {
        // 3. Inscription
        $passwordHash = password_hash($password, PASSWORD_DEFAULT);

        // On appelle la fonction mise à jour avec l'adresse
        if (creerUtilisateur($pdo, $nom, $email, $passwordHash, $adresse)) {
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
    <link rel="stylesheet" href="style.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription</title>
</head>
<body>
    <?php include 'header.php'; ?>
    <div class="container">

        <h2>Inscription</h2>

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
    
            <label>Mot de passe :</label><br>
            <input type="password" name="password" required><br><br>

            <label>Confirmer le mot de passe :</label><br>
            <input type="password" name="confirm_password" required><br><br>
    
            <button type="submit">S'inscrire</button>
        <p>Déjà un compte ? <a href="login.php">Se connecter</a></p>
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