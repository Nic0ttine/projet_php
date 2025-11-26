"# projet_php" 
# Système de Gestion d'Utilisateurs (PHP Procédural)

Ce projet est une application web complète de gestion d'utilisateurs développée en **PHP procédural** (sans Programmation Orientée Objet), conformément aux objectifs pédagogiques du module.

Il permet de gérer l'inscription, la connexion, la sécurisation des sessions et propose une interface d'administration pour gérer les rôles et les comptes.

## 🚀 Fonctionnalités

### Partie Publique & Utilisateur
* **Inscription sécurisée :**
    * Vérification des champs (Nom, Email, Adresse, Mot de passe).
    * Validation de l'email et du mot de passe via **Regex**.
    * Hashage du mot de passe avec `password_hash` (Argon2/Bcrypt).
    * Vérification de l'unicité de l'email.
* **Connexion (Login) :**
    * Vérification des identifiants.
    * Gestion des sessions PHP.
    * Redirection automatique selon le rôle (Admin vs User).
* **Espace Profil :**
    * Affichage des informations personnelles (incluant l'adresse).
    * Possibilité de **supprimer son propre compte**.
    * Déconnexion sécurisée.

### Partie Administration (Back-office)
* Accessible uniquement aux utilisateurs ayant le rôle **Admin**.
* **Tableau de bord :** Liste complète des utilisateurs inscrits.
* **CRUD Utilisateurs :**
    * **Ajouter** un utilisateur (avec choix du rôle).
    * **Modifier** un utilisateur (Nom, Email, changement de Rôle).
    * **Supprimer** un utilisateur.

## 🛠️ Choix Techniques & Sécurité

Le projet respecte les consignes strictes de développement :
* **Langage :** PHP (Style procédural).
* **Base de données :** MySQL.
* **Interface BDD :** Utilisation de **PDO** pour l'abstraction.
* **Sécurité :**
    * Requêtes préparées (`Prepared Statements`) pour contrer les injections SQL.
    * `htmlspecialchars()` pour contrer les failles XSS.
    * `trim()` pour le nettoyage des entrées.
    * Contrôle d'accès strict (Redirection si non connecté ou non admin).

## 📂 Structure du projet

* `db.php` / `fonctions.php` : Connexion BDD et fonctions réutilisables (sécurité, helpers).
* `register.php` : Formulaire d'inscription.
* `login.php` : Formulaire de connexion.
* `profil.php` : Espace membre (User).
* `admin.php` : Tableau de bord principal (Admin).
* `admin_add.php` : Création manuelle d'utilisateur par l'admin.
* `edit_user.php` : Modification d'un utilisateur.
* `logout.php` : Script de déconnexion.
* `gestion_users.sql` : Script SQL de création de la base de données.

## ⚙️ Installation

1.  **Cloner le projet** ou télécharger les fichiers dans votre dossier serveur (ex: `C:\laragon\www\projet`).
2.  **Base de données :**
    * Ouvrez phpMyAdmin ou HeidiSQL.
    * Créez une base de données nommée `gestion_users`.
    * Importez le fichier `gestion_users.sql`.
3.  **Configuration :**
    * Vérifiez les identifiants dans `fonctions.php` (fonction `getDB`) si nécessaire.
4.  **Lancement :**
    * Accédez au projet via votre navigateur (ex: `http://localhost/projet`).

## 👤 Nicolas
