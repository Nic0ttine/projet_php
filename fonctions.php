<?php

// ---------------------------------------
// Connexion PDO à la base de données
// ---------------------------------------
function getDB() {
    // Le PDF demande la base "gestion_users"
    $host = "localhost";
    $dbname = "userauth";
    $username = "root";
    $password = "";

    try {
        $pdo = new PDO(
            "mysql:host=$host;dbname=$dbname;port=3306;charset=utf8",
            $username,
            $password,
            [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            ]
        );
        return $pdo;
    } catch (PDOException $e) {
        die("Erreur de connexion BDD : " . $e->getMessage());
    }
}



// ---------------------------------------
// Vérifie si un email existe déjà
// ---------------------------------------
function emailExiste($pdo, $email) {
    $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->execute([$email]);
    return $stmt->rowCount() > 0;
}



// ---------------------------------------
// Inscrire un utilisateur
// ---------------------------------------
function creerUtilisateur($pdo, $nom, $email, $adresse, $passwordHash) {
    $sql = "INSERT INTO users (nom, email,password , adresse) VALUES (?, ?, ?, ?)";
    $stmt = $pdo->prepare($sql);
    return $stmt->execute([$nom, $email, $adresse, $passwordHash]);
}



// ---------------------------------------
// Récupérer un utilisateur par email
// ---------------------------------------
function getUserByEmail($pdo, $email) {
    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    return $stmt->fetch();
}



// ---------------------------------------
// Vérifier si un utilisateur est connecté
// ---------------------------------------
function isLogged() {
    return isset($_SESSION['user_id']);
}



// ---------------------------------------
// Bloquer une page si non connecté
// ---------------------------------------
function requireLogin() {
    if (!isLogged()) {
        header("Location: login.php");
        exit;
    }
}

// ---------------------------------------
// Bloquer si ce n'est pas un ADMIN
// ---------------------------------------
function requireAdmin() {
    // 1. D'abord, on vérifie si la personne est connectée
    requireLogin(); 
    
    // 2. Ensuite, on vérifie son rôle (1 = Admin selon la BDD)
    if ($_SESSION['user_role'] != 1) {
        // Si pas admin, on arrête tout ou on redirige
        header("Location: profil.php");
        exit;
    }
}


?>